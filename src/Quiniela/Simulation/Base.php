<?php


abstract class Quiniela_Simulation_Base
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var Intraway_Common_Database_Adapter_Interface
     */
    protected $dbConn;

    /**
     * @var Intraway_Interface_Logger_Interface
     */
    protected $logger;

    /**
     * @var array
     */
    private $loteriaIds;

    /**
     * @var array
     */
    private $sorteoIds;

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return Intraway_Common_Database_Adapter_Interface
     */
    public function getDbConn()
    {
        return $this->dbConn;
    }

    /**
     * @return Intraway_Interface_Logger_Interface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return array
     */
    public function getLoteriaIds()
    {
        return $this->loteriaIds;
    }

    /**
     * @return array
     */
    public function getSorteoIds()
    {
        return $this->sorteoIds;
    }

    public function __construct()
    {
        $this->config = json_decode(file_get_contents(realpath(__DIR__ .'/../../../').'/config/config.json'), 1);

        $kernel = Intraway_Common_Kernel::getInstance();
        // El logger es opcional
        $this->logger = $kernel->getLoggerForChannels('quiniela');

        $this->dbConn = $kernel->getDatabaseConnection('dev');

        // load de los argumentos
        foreach ($_SERVER['argv'] as $arg) {
            $keyval = explode('=', $arg);
            if (count($keyval) == 2) {
                $this->arguments[ltrim($keyval[0], '--')] = trim($keyval[1], "\"");
            }
        }

        $loterias = $this->getArgument('loterias', 'nacional');
        if (empty($loterias)) {
            throw new InvalidArgumentException('Argument \'loterias\' is required!');
        }

        $loterias = explode(',', $loterias);
        $loteriaMap = $this->getConfig('loterias');
        foreach ($loterias as $loteria) {
            if (!isset($loteriaMap[$loteria])) {
                throw new InvalidArgumentException("La loteria \'$loteria\' es desconocida!");
            }
            $this->loteriaIds[] = $loteriaMap[$loteria];
        }

        $sorteoIds = $this->getArgument('sorteo', 'primera,matutino,vespertino,nocturno');
        if (empty($sorteoIds)) {
            throw new InvalidArgumentException('Argument \'sorteos\' is required!');
        }

        $sorteoIds = explode(',', $sorteoIds);
        $sorteoMap = $this->getConfig('sorteos');
        foreach ($sorteoIds as $sorteo) {
            if (!isset($sorteoMap[$sorteo])) {
                throw new InvalidArgumentException("La loteria \'$sorteo\' es desconocida!");
            }
            $this->sorteoIds[] = $sorteoMap[$sorteo];
        }
    }

    public function getConfig($key, $default = null)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }

    public function getArgument($argname, $default = '')
    {
        if (isset($this->arguments[$argname])) {
            return $this->arguments[$argname];
        }

        return $default;
    }

    function redoblonaGanadora($numCabeza, $numPremios, $date, $premios = 20, $loteriaId = null, $sorteoId = null)
    {
        $sql1 = <<<SQL
            select *
              from sorteo_numero
             where 1=1
               and posicion = 1
               and fecha = :fecha
               and numero = :numero
               {{FILTRO_LOTERIA}}
               {{FILTRO_SORTEO}}
SQL;

        $sql2 = <<<SQL
            select *
              from sorteo_numero
             where 1=1
               and posicion >= 2
               and posicion <= :premios
               and fecha = :fecha
               and numero = :numeroPremios
               {{FILTRO_LOTERIA}}
               {{FILTRO_SORTEO}}
SQL;

        $bnd = array(
            'fecha' => $date,
            'numero' => $numCabeza,
            'numeroPremios' => $numPremios,
            'premios' => $premios,
        );

        if ($loteriaId !== null) {
            $sql1 = str_replace('{{FILTRO_LOTERIA}}', 'and loteria_id = :loteriaId', $sql1);
            $sql2 = str_replace('{{FILTRO_LOTERIA}}', 'and loteria_id = :loteriaId', $sql2);
            $bnd['loteriaId'] = $loteriaId;
        } else {
            $sql1 = str_replace('{{FILTRO_LOTERIA}}', '', $sql1);
            $sql2 = str_replace('{{FILTRO_LOTERIA}}', '', $sql2);
        }

        if ($sorteoId !== null) {
            $sql1 = str_replace('{{FILTRO_SORTEO}}', 'and sorteo_id = :sorteoId', $sql1);
            $sql2 = str_replace('{{FILTRO_SORTEO}}', 'and sorteo_id = :sorteoId', $sql2);
            $bnd['sorteoId'] = $sorteoId;
        } else {
            $sql1 = str_replace('{{FILTRO_SORTEO}}', '', $sql1);
            $sql2 = str_replace('{{FILTRO_SORTEO}}', '', $sql2);
        }


        $res = $this->dbConn->executeRead($sql1, $bnd);
        $salioCabeza = (count($res) > 0);

        $salioPremios = false;
        if ($salioCabeza) {
            $res = $this->dbConn->executeRead($sql2, $bnd);
            $salioPremios = (count($res) > 0);
        }

        return ($salioCabeza && $salioPremios);
    }

    function esGanador($numero, $posicion, $fecha, $loteriaId, $sorteoId)
    {
        $sql = <<<SQL
            select *
              from sorteo_numero
             where 1=1
               and posicion = :posicion
               and fecha = :fecha
               and mod(numero_completo, :modulo) = :numero
               and sorteo_id = :sorteoId
               and loteria_id = :loteriaId
SQL;
        $bnd = array(
            'fecha' => $fecha,
            'modulo' => 10 * strlen($numero),
            'numero' => $numero,
            'loteriaId' => $loteriaId,
            'sorteoId' => $sorteoId,
            'posicion' => $posicion,
        );

        $res = $this->dbConn->executeRead($sql, $bnd);
        //$this->logger->debug(print_r($sql, 1));
        //$this->logger->debug(print_r($bnd, 1));

        return (count($res) == 1);
    }

    public function calcularAtrasadosEnUltimosNSorteos($date, $loteriaId, $cantSorteos, $responseCant, $digits = 1)
    {
        $cantSorteos = (int) $cantSorteos;
        $digits = (int) $digits;
        $mod = pow(10, $digits);

        $sql = <<<SQL

        	select mod(t.numero, $mod) as numero
              from (
                 select numero, fecha
                   from sorteo_numero
				  where fecha < :date
                    and posicion = 1
                    and loteria_id = :loteriaId
                  order by fecha desc, sorteo_id desc
                  limit $cantSorteos
                  ) t
             group by mod(t.numero, $mod)
             order by max(t.fecha) asc
SQL;
        $bnd = array(
            'date' => $date,
            'loteriaId' => $loteriaId,
        );

        $res = $this->dbConn->executeRead($sql, $bnd);

        return array_slice($res, 0, $responseCant);
    }

    function calcularRepetidosUnaVezEnUltimosNSorteos($date, $loteriaId, $n = 67)
    {
        $n = (int) $n;

        $sql = <<<SQL
            select t.numero
              from (
                 select numero
                   from sorteo_numero
				  where fecha < :date
                    and posicion = 1
                    and loteria_id = :loteriaId
                  order by fecha desc, sorteo_id desc
                  limit $n
                  ) t
             group by t.numero
			having count(t.numero) = 2
SQL;
        $bnd = array(
            'date' => $date,
            'loteriaId' => $loteriaId,
        );
        $res = $this->dbConn->executeRead($sql, $bnd);

        $numeros = array();
        foreach ($res as $item) {
            $numeros[] = $item['numero'];
        }

        return $numeros;
    }

    function createItem(
        $simulationId,
        $date,
        $invest,
        $investAcum,
        $income,
        $incomeAcum,
        $profit,
        $profitAcum
    ) {

        $sql = <<<SQL
        insert into simulation_item (
                simulation_id,
                fecha,
                invest,
                invest_acum,
                income,
                income_acum,
                profit,
                profit_acum
            )
             values (
                :simulationId,
                :fecha,
                :invest,
                :investAcum,
                :income,
                :incomeAcum,
                :profit,
                :profitAcum
            )
SQL;
        $bnd = array(
            'simulationId' => $simulationId,
            'fecha' => $date,
            'invest' => $invest,
            'investAcum' => $investAcum,
            'income' => $income,
            'incomeAcum' => $incomeAcum,
            'profit' => $profit,
            'profitAcum' => $profitAcum,
        );

        $this->dbConn->beginTransaction();
        $res = $this->dbConn->executeWrite($sql, $bnd);
        if ($res == 1) {
            $this->dbConn->commit();
            return true;
        } else {
            $this->dbConn->rollback();
            return false;
        }

    }

    function getLastInsertedId()
    {
        $sql = <<<SQL
        select LAST_INSERT_ID() as "lastInsertedId"
SQL;
        $bnd = array();

        $res = $this->dbConn->executeRead($sql, $bnd);
        return $res[0]['lastInsertedId'];
    }

    function isDataForDate($loteriaId, $sorteoId, $date)
    {
        $sql = <<<SQL
            select count(1) as hay_datos
              from sorteo_numero
             where 1=1
               and fecha = :date
               and posicion = 1
               and loteria_id = :loteriaId
               and sorteo_id = :sorteoId
               and numero is not null
SQL;
        $bnd = array(
            'date' => $date,
            'loteriaId' => $loteriaId,
            'sorteoId' => $sorteoId,
        );
        $res = $this->dbConn->executeRead($sql, $bnd);

        return ($res[0]['hay_datos'] == 1);
    }


    function create($description)
    {
        $sql = <<<SQL
        insert into simulation (description) values (:description)
SQL;
        $bnd = array(
            'description' => $description,
        );

        $this->dbConn->beginTransaction();
        if ($this->dbConn->executeWrite($sql, $bnd) == 1) {
            $this->dbConn->commit();
            return $this->getLastInsertedId();
        }
        $this->dbConn->rollback();

        throw new RuntimeException('Error al crear la simulacion!');
    }

    function delete($simulationId)
    {
       $sql = <<<SQL
        delete from simulation where id = :simulationId
SQL;
        $bnd = array(
            'simulationId' => $simulationId,
        );

        $this->dbConn->beginTransaction();
        if ($this->dbConn->executeWrite($sql, $bnd) == 1) {
            $this->dbConn->commit();
        }
        $this->dbConn->rollback();

        throw new RuntimeException('Error al eliminar la simulacion!');
    }

    public abstract function getInicioSimulacion();

    public abstract function getDescription();

    public abstract function executeSimulation($date, $investAcum, $incomeAcum, $profitAcum);
}
