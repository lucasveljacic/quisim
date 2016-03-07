<?php
header('Content-type: text/plain');
require_once __DIR__.'/../../vendor/autoload.php';

$kernel = Intraway_Common_Kernel::getInstance();
$logger = $kernel->getLoggerForChannels('quiniela');
$db = $kernel->getDatabaseConnection('dev');

$simulationId = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : 1;
$resourceId = isset($_REQUEST['resourceId']) ? $_REQUEST['resourceId'] : 'invest';

$resourceFunction = strtolower($resourceId).'Serie';

if (!is_callable($resourceFunction)) {
    echo '[]';
    exit(1);
}

$resourceIdSerie = $resourceFunction($simulationId, $resourceId);
echo $resourceIdSerie;


/**
 * RESOURCE SERIES FUNCTIONS
 */

function investSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'invest');
}

function incomeSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'income');
}

function profitSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'profit');
}

function investAcumSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'invest_acum');
}

function incomeAcumSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'income_acum');
}

function profitAcumSerie($simulationId) {
    return _readColumnSimulationResource($simulationId, 'profit_acum');
}

function _readColumnSimulationResource($simulationId, $resourceId)
{
    global $db;

    $sql = <<<SQL
            select concat(
                    'Date.UTC(',year(fecha),',',month(fecha) - 1,',',dayofmonth(fecha),'),',
                    {{DATA_TYPE_COL}}
                    ) as 'resourceIdSerie'
              from simulation_item
             where simulation_id = :simulationId
             order by fecha asc
SQL;

    $sql = str_replace('{{DATA_TYPE_COL}}', $resourceId, $sql);

    $bnd = array(
        'simulationId' => $simulationId,
    );

    $res = $db->executeRead($sql, $bnd);

    return '[['.implode('],[', array_column($res, 'resourceIdSerie')).']]';
}