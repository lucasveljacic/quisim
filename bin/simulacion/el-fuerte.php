<?php

function executeSimulation($date)
{
    global $logger,
        $investAcum,
        $incomeAcum,
        $profitAcum;


    $apuesta = 1;

    /**
     * obtenemos los numeros que se repitieron 2 veces en los ultimos 67 sorteos.
     */

    $numeros = calcularNumeros2VecesEnUltimas67($date, 1);

    $logger->debug("numeros encontrados: ".implode(' - ', $numeros));

    $cant = count($numeros);

    $logger->debug("Numeros: ".$cant);

    /**
     * Calculamos si se hubiese ganado en el siguiente sorteo
     * Para ganar, el primer numero tiene que salir en la posicion 1 y el
     * otro en las posiciones 2-20
     */
    $ganadas = 0;
    $k = 0;
    foreach ($redoblonas as $redoblona) {
        $k++;

        $bRedoblonaGanadora1 = redoblonaGanadora($redoblona['cabeza'], $redoblona['premios'], $date, 10, 1, 1);
        $bRedoblonaGanadora2 = redoblonaGanadora($redoblona['cabeza'], $redoblona['premios'], $date, 10, 1, 2);
        $bRedoblonaGanadora3 = redoblonaGanadora($redoblona['cabeza'], $redoblona['premios'], $date, 10, 1, 3);
        $bRedoblonaGanadora4 = redoblonaGanadora($redoblona['cabeza'], $redoblona['premios'], $date, 10, 1, 4);

        if ($bRedoblonaGanadora1) {
            $logger->debug("Apuesta $k: {$redoblona['cabeza']}-{$redoblona['premios']} Premio: 1 - GANO !!!");
            $ganadas++;
        }

        if ($bRedoblonaGanadora2) {
            $logger->debug("Apuesta $k: {$redoblona['cabeza']}-{$redoblona['premios']} Premio: 2 - GANO !!!");
            $ganadas++;
        }

        if ($bRedoblonaGanadora3) {
            $logger->debug("Apuesta $k: {$redoblona['cabeza']}-{$redoblona['premios']} Premio: 3 - GANO !!!");
            $ganadas++;
        }

        if ($bRedoblonaGanadora4) {
            $logger->debug("Apuesta $k: {$redoblona['cabeza']}-{$redoblona['premios']} Premio: 4 - GANO !!!");
            $ganadas++;
        }
    }

    $logger->debug("Ganadas: $ganadas");

    $invest = count($redoblonas) * $apuesta * 1;
    $income = $ganadas * $apuesta * 490;
    $profit = $income - $invest;

    $investAcum += $invest;
    $incomeAcum += $income;
    $profitAcum += $profit;

    $logger->debug("Invest Acumulated: $investAcum");
    $logger->debug("Income Acumulated: $incomeAcum");
    $logger->debug("Profit Acumulated: $profitAcum");

    return compact($income, $invest, $profit, $incomeAcum, $investAcum, $profitAcum);
}