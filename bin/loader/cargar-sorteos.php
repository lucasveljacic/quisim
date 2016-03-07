<?php
/**
 * Controller Service.
 *
 * PHP version 5.2+
 *
 * @category   Frontend
 * @package    Intraway.TvVod
 * @subpackage Service
 * @author     Lucas Veljacic <lucas.veljacic@intraway.com>
 * @copyright  2015 Intraway Corp.
 * @license    Intraway Corp. <http://www.intraway.com>
 * @link       http://www.intraway.com
 */

require_once realpath(dirname(__FILE__) .'/../../').'/vendor/autoload.php';

// All necesary code for signal handle
require_once realpath(dirname(__FILE__).'/../').'/signal_handler.php';

require_once realpath(__DIR__.'/../').'/util.php';

$kernel = Intraway_Common_Kernel::getInstance();
// El logger es opcional
$logger = $kernel->getLoggerForChannels('quiniela');

$i = 0;

$db = $kernel->getDatabaseConnection('dev');

$logger->debug("Start ...");

$files = glob(realpath(__DIR__."/../../").'/data/input/*.json');

foreach ($files as $file) {

    if (haveToEnd()) {
        exit(0);
    }

    try {
        $sorteos = json_decode(file_get_contents($file), 1);

        $db->beginTransaction();

        $date = $sorteos['date'];
        foreach ($sorteos['sorteos'] as $sorteoName => $sorteo) {

            if (!isset($sorteosMap[$sorteoName])) {
                throw new RuntimeException("El sorteo '$sorteoName' es desconocido!");
            }

            $sorteoId = $sorteosMap[$sorteoName];

            foreach ($sorteo['loterias'] as $loteriaName => $numeros) {

                if (!isset($loteriasMap[$loteriaName])) {
                    throw new RuntimeException("la loteria '$loteriaName' es desconocida!");
                }

                $loteriaId = $loteriasMap[$loteriaName];


                foreach ($numeros as $posicion => $numero) {

                    $sql = <<<SQL
                      insert into sorteo_numero (
                            loteria_id,
                            sorteo_id,
                            fecha,
                            posicion,
                            numero_completo,
                            numero
                            )
                     values (
                            :loteria_id,
                            :sorteo_id,
                            :fecha,
                            :posicion,
                            :numero_completo,
                            :numero
                             )
SQL;
                    $bnd = array(
                        'loteria_id' => $loteriaId,
                        'sorteo_id' => $sorteoId,
                        'fecha' => $date,
                        'posicion' => $posicion,
                        'numero_completo' => $numero,
                        'numero' => $numero % 100,
                    );

                    $db->executeWrite($sql, $bnd);

                }
            }
        }

        $db->commit();

        rename($file, str_replace('input', 'loaded', $file));

        $logger->info("processed file: ".$file);

    } catch (Exception $e) {
        $logger->info("ERROR in file: ".$file);

        $logger->error(__FILE__.' L.'.__LINE__." - Exception Cought '".get_class($e)."' - '{$e->getMessage()}'");
        $logger->error(__FILE__.' L.'.__LINE__.' - Exception Trace: '.$e->getTraceAsString());

        rename($file, str_replace('input', 'error', $file));

        $db->rollback();
    }

    //if ($i++ > 20) break;
}


$logger->info("End of Process");

exit(0);
