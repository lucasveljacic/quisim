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

require_once realpath(__DIR__.'/../../').'/vendor/autoload.php';

// All necesary code for signal handle
require_once realpath(__DIR__.'/../').'/signal_handler.php';

$factory = new Quiniela_Simulation_Factory();
$simulation = $factory->create(Quiniela_Simulation_Factory::NO_HAY_2_SIN_3);

$dateTime = new DateTime($simulation->getInicioSimulacion());
$dateTime->add(new DateInterval('P30D'));

$simulationId = $simulation->create($simulation->getDescription());

$investAcum = 0;
$incomeAcum = 0;
$profitAcum = 0;

try {

    $dias = 0;
    while(!haveToEnd() && $dateTime->getTimestamp() < time() - 86400 * 30) {

        // chequeamos que no sea domingo
        if ($dateTime->format('w') != 0) {
            $dias++;
            $date = $dateTime->format('Y-m-d');
            $simulation->getLogger()->info("Fecha para la simulacion: ".$date);

            $response = $simulation->executeSimulation($date, $investAcum, $incomeAcum, $profitAcum);

            if (!empty($response)) {
                $invest = $response['invest'];
                $investAcum = $response['investAcum'];
                $income = $response['income'];
                $incomeAcum = $response['incomeAcum'];
                $profit = $response['profit'];
                $profitAcum = $response['profitAcum'];

                $creationItemResponse = $simulation->createItem(
                    $simulationId, $date, $invest, $investAcum, $income, $incomeAcum, $profit, $profitAcum
                );

                if ($creationItemResponse === false) {
                    throw new RuntimeException(
                        "Error al insertar el Item: ".
                        "$simulationId, $date, $invest, $investAcum, $income, $incomeAcum, $profit, $profitAcum"
                    );
                }
            }
        };

        $dateTime->add(new DateInterval('P1D'));
    }


} catch (Exception $e) {
    $simulation->getLogger()->error(__FILE__.' L.'.__LINE__." - Exception Cought '".get_class($e)."' - '{$e->getMessage()}'");
    $simulation->getLogger()->error(__FILE__.' L.'.__LINE__.' - Exception Trace: '.$e->getTraceAsString());

    try {
        $simulation->delete($simulationId);
    } catch (RuntimeException $e) {
        $simulation->getLogger()->error(__FILE__.' L.'.__LINE__." - Exception Cought '".get_class($e)."' - '{$e->getMessage()}'");
        $simulation->getLogger()->error(__FILE__.' L.'.__LINE__.' - Exception Trace: '.$e->getTraceAsString());
    }

    exit(1);
}

$simulation->getLogger()->info("End of Process");

exit(0);