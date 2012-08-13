<?php
/**
 * This system is mostly a port of delayed_job
 *
 * @link http://github.com/tobi/delayed_job
 */
class DJBase {
    const CRITICAL = 4;

    const ERROR = 3;

    const WARN = 2;

    const INFO = 1;

    const DEBUG = 0;

    private static $log_level = self::DEBUG;

	public static function model() {
		return ClassRegistry::init('InfinitasJobs.InfinitasJob');
	}

    public static function setLogLevel($const) {
        self::$log_level = $const;
    }

    protected static function log($mesg, $severity=self::CRITICAL) {
        if ($severity >= self::$log_level) {
            printf("[%s] %s\n", date('Y-m-d H:i:s'), $mesg);
        }
    }
}