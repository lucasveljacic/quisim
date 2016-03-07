<?php


class Quiniela_Simulation_Factory
{
    const NO_HAY_2_SIN_3 = 'no_hay_2_sin_3';

    /**
     * Create
     *
     * @param $sid
     *
     * @return Quiniela_Simulation_Base
     */
    public function create($sid)
    {
        switch($sid) {
            case self::NO_HAY_2_SIN_3:
                return new Quiniela_Simulation_Method_NoHay2Sin3();
                break;
        }
    }
}
