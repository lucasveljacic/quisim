<?php

class Quiniela_Simulation_Method_NoHay2Sin3 extends Quiniela_Simulation_Base
{
    public function getInicioSimulacion()
    {
        return $this->getArgument('start', $this->getConfig('inicioSimulaciones'));
    }

    public function getDescription()
    {
        $desc = "Metodo: Tomar los repetidos por unica vez en los ultimos 67 sorteos y armar redoblonas con las combinaciones\n";
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

            $apuesta = (float) $this->getArgument('apuesta', 1);
            $premios = (int) $this->getArgument('redoblona-premios', 10);

            /**
             * obtenemos los numeros que se repitieron 2 veces en los ultimos 67 sorteos.
             */
            $numeros = $this->calcularNumeros2VecesEnUltimas67($date, $loteriaId);

            $this->logger->debug("numeros encontrados: ".implode(' - ', $numeros));

            /**
             * Armamos todas las combinaciones para las apuestas
             */
            $redoblonas = array();
            $cant = count($numeros);
            for ($i = 0; $i < $cant; $i++) {
                for ($j = 0; $j < $cant; $j++) {
                    if ($j == $i) {
                        continue;
                    }

                    $redoblonas[] = array(
                        'cabeza' => $numeros[$i],
                        'premios' => $numeros[$j],
                    );

                }
            }
            $this->logger->debug("Numeros: ".$cant);
            $this->logger->debug("Redoblonas calculadas: ".count($redoblonas));


            /**
             * Calculamos si se hubiese ganado en el siguiente sorteo
             * Para ganar, el primer numero tiene que salir en la posicion 1 y el
             * otro en las posiciones 2-20
             */
            $ganadas = 0;
            $k = 0;
            $redoblonasApostadas = 0;
            foreach ($redoblonas as $redoblona) {
                $k++;

                foreach ($this->getSorteoIds() as $sorteoId) {

                    // chequeamos que haya valores para calcular la simulacion.
                    if ($this->isDataForDate($loteriaId, $sorteoId, $date) === false) {
                        // no hay datos para ese dia
                        $this->logger->warning(
                            "[NODATA] No hay datos para el Dia: '.$date. ' Para el sorteo '$sorteoId' de la loteria '$loteriaId'!"
                        );

                        continue;
                    }

                    $bRedoblonaGanadora = $this->redoblonaGanadora(
                        $redoblona['cabeza'], $redoblona['premios'], $date, $premios, $loteriaId, $sorteoId
                    );

                    if ($bRedoblonaGanadora) {
                        $this->logger->debug(
                            "Apuesta $k: {$redoblona['cabeza']}-{$redoblona['premios']} ".
                            " Loteria: '$loteriaId' Sorteo: '$sorteoId' - GANO !!!"
                        );

                        $ganadas++;
                    }

                    $redoblonasApostadas++;
                }
            }

            $this->logger->debug("Ganadas: $ganadas");

            $invest += $apuesta * $redoblonasApostadas;
            $income += $ganadas * $apuesta * 70 * (70 / $premios);
            $profit += $income - $invest;

            $investAcum += $invest;
            $incomeAcum += $income;
            $profitAcum += $profit;
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
