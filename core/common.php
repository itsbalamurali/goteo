<?php

namespace {

	require_once("core/registry.php");


	class GoteoLogLevel {
		const DEBUG = 10;
		const INFO = 20;
		const NOTICE = 25;
		const WARNING = 30;
		const ERROR = 40;
		const CRITICAL = 50;
	};

	class TinyLogger {
		var $level_cutoff;

		public function __construct($cutoff = GoteLogLevel::DEBUG) {
			$this->level_cutoff = $cutoff;
		}

		public function log($msg, $level) {
			if($level >= $this->level_cutoff) {
				error_log($msg);
			}
		}
	}

	$logger = new TinyLogger(GoteoLogLevel::DEBUG);
	Goteo\Core\Registry::set('log', $logger);

    /**
     * Traza información sobre el recurso especificado de forma legible.
     *
    * @param    type mixed  $resource   Recurso
     */
    function trace ($resource = null) {
        echo '<pre>' . print_r($resource, 1) . '</pre>';
    }

    /**
     * Vuelca información sobre el recurso especificado de forma detallada.
     *
     * @param   type mixed  $resource   Recurso
     */
    function dump ($resource = null) {
        echo '<pre>' . var_dump($resource) . '</pre>';
    }

	function _log($msg, $level = GoteoLogLevel::DEBUG) {
		Goteo\Core\Registry::get('log')->log($msg, $level);
	}

    /**
     * Genera un mktime (UNIX_TIMESTAMP) a partir de una fecha (DATE/DATETIME/TIMESTAMP)
     * @param $str
     */
    function date2time ($str) {
    	list($date, $time) = explode(' ', $str);
    	list($year, $month, $day) = explode('-', $date);
    	list($hour, $minute, $second) = explode(':', $time);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        return $timestamp;
    }

    /**
     * Checkea si todos los indices del array son vacios
     * @param array $mixed
     * @return boolean
     */
    function array_empty($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $value) {
                if (!array_empty($value)) {
                    return false;
                }
            }
        }
        elseif (!empty($mixed)) {
            return false;
        }
        return true;
    }

    /**
     * Numberformat para importes
     */
    function amount_format($amount, $decs = 0) {
        return number_format($amount, $decs, ',', '.');
    }

    /*
     * Verifica si una cadena es sha1 
     */
    function is_sha1($str) {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
    }    
}