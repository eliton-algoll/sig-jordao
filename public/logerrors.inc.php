<?php
/**
 * Display errors.
 */
ini_set('display_errors', false);
ini_set('html_errors', 0);

/**
 * Error reporting.
 */
error_reporting(-1);

/**
 * Shutdown handler.
 *
 * @return boolean
 */
function ShutdownHandler()
{
    if (@is_array($error = @error_get_last())) {
        return (@call_user_func_array('ErrorHandler', $error));
    }

    return true;
}

register_shutdown_function('ShutdownHandler');

function WriteOnErrorLog($name, $file, $line, $message)
{
//    $logDir = ROOT_DIR . DIRECTORY_SEPARATOR . 'log';
//
//    if (defined('LOG_DIR')) {
//        $logDir = LOG_DIR;
//    }

    $logFile = ROOT_DIR . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'errors.log';

//    if (!file_exists($logDir)) {
//        mkdir($logDir, 0755, true);
//    }

    $handle = fopen($logFile, 'a') or die('Unable to open log file: ' . $logFile);
    fwrite($handle, @sprintf("%s %s Error in file \"%s\" at line %d: %s\n", (new \DateTime())->format('Y-m-d H:i:s'), $name, $file, $line, $message));
    fclose($handle);
}

/**
 * @param $type
 * @param $message
 * @param $file
 * @param $line
 * @return bool
 * @throws Exception
 */
function ErrorHandler($type, $message, $file, $line)
{
    $_ERRORS = array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );

    if (!@is_string($name = @array_search($type, @array_flip($_ERRORS)))) {
        $name = 'E_UNKNOWN';
    }

    WriteOnErrorLog($name, $file, $line, $message);

    return true;
}

$old_error_handler = set_error_handler('ErrorHandler');
