<?php

// Global
$_BOOL_END = false;

if (extension_loaded('pcntl')) {
    declare(ticks = 1);
    /**
     * _signalHandler
     *
     * @param int $signo Signal
     *
     * @return void
     */
    function _signalHandler($signo)
    {
        global $_BOOL_END;
        switch ($signo) {
        case SIGTERM:
        case SIGINT:
            $_BOOL_END = true;
            _signalHandlerLogInfo('External signal received: '._signalHandlerSigno2signame($signo));
            break;
        }
    }
    pcntl_signal(SIGTERM, "_signalHandler");
    pcntl_signal(SIGINT, "_signalHandler");
}

/**
 * _sig__log_info
 *
 * @param string $msg Msg
 *
 * @return void
 */
function _signalHandlerLogInfo($msg)
{
    global $logger;
    // Si existe una variable global llamada logger logueo la senial recibida
    if ($logger && is_object($logger) && method_exists($logger, 'logInfo')) {
        $logger->logInfo($msg);
    }
}

/**
 * _sig__signo_to_signame
 *
 * @param int $signo Signal
 *
 * @return string
 */
function _signalHandlerSigno2signame($signo)
{
    $signame = $signo;
    switch ($signo) {
    case SIGINT:
        $signame = 'SIGINT';
        break;
    case SIGTERM:
        $signame = 'SIGTERM';
        break;
    case SIGUSR1:
        $signame = 'SIGUSR1';
        break;
    case SIGUSR2:
        $signame = 'SIGUSR2';
        break;
    }
    return $signame;
}

/**
 * have_to_end
 *
 * @return bool
 */
function haveToEnd()
{
    global $_BOOL_END;
    return $_BOOL_END;
}