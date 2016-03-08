<?php

class Quiniela_Simulation_Method_UltimoAtrasado extends Quiniela_Simulation_Base
{
    private $digitoAtrasado = null;
    private $iteracion;
    private $apuesta;
    private $apuestaBase;
    private $factorProgresion;

    public function __construct()
    {
        parent::__construct();

        $this->apuestaBase = (float) $this->getArgument('apuesta', 1);
        $this->factorProgresion = (float) $this->getArgument('factor-progresion', 2);
    }

    public function getInicioSimulacion()
    {
        return $this->getArgument('start', $this->getConfig('inicioSimulaciones'));
    }

    public function getDescription()
    {
        $desc = "Metodo: Tomar el digitos mas atrasados y apostar con progresion de factor factor-progresion \n";
        $desc .= "Args: ".print_r($this->getArguments(), 1);
        return $desc;
    }

    public function executeSimulation($date, $investAcum, $incomeAcum, $profitAcum)
    {
        $invest = 0;
        $income = 0;
        $profit = 0;

        foreach ($this->getLoteriaIds() as $loteriaId) {

            $this->logger->debug("Loteria: $loteriaId ...");

            /**
             * obtenemos los numeros que se repitieron 2 veces en los ultimos 67 sorteos.
             */
            if ($this->digitoAtrasado === null) {
                $cantSorteos = 1000;
                $responseCant = 1;
                $digits = 1;
                $numeros = $this->calcularAtrasadosEnUltimosNSorteos(
                    $date, $loteriaId, $cantSorteos, $responseCant, $digits
                );
                $this->digitoAtrasado = $numeros[0]['numero'];
            }

            $this->logger->debug("Digito atrasados actual: {$this->digitoAtrasado}");

            $bGano = false;
            $invest = 0;
            $sorteoId = 1;
            while($sorteoId <= 4 && !$bGano) {
                $this->iteracion++;
                $invest += $this->apuesta;
                $bGano = $this->esGanador($this->digitoAtrasado, 1, $date, $loteriaId, $sorteoId);

                if ($bGano) {
                    $this->logger->debug(
                        "Numero ($this->digitoAtrasado) SORTEO: {$sorteoId} - IT: {$this->iteracion} ....... GANO !!!"
                    );
                } else {
                    $this->apuesta *= $this->factorProgresion;
                    $sorteoId++;
                }
            }

            $income += $bGano ? 7 * $this->apuesta : 0;
            $profit += $income - $invest;

            $investAcum += $invest;
            $incomeAcum += $income;
            $profitAcum += $profit;

            if ($bGano) {
                $this->apuesta = $this->apuestaBase;
                $this->digitoAtrasado = null;
                $this->iteracion = 0;
            }
        }

        $this->logger->debug("Invest Current/Acumulated: $invest/$investAcum");
        $this->logger->debug("Income Current/Acumulated: $income/$incomeAcum");
        $this->logger->debug("Profit Current/Acumulated: $profit/$profitAcum");

        return array(
            'income' => $income,
            'invest' => $invest,
            'profit' => $profit,
            'incomeAcum' => $incomeAcum,
            'investAcum' => $investAcum,
            'profitAcum' => $profitAcum
        );
    }
}
