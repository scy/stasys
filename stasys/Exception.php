<?php

/**
 * Base class for all stasys exceptions.
 */
class stasys_Exception extends Exception { }

/**
 * Something could not be accessed.
 */
class stasys_AccessException extends stasys_Exception { }

/**
 * A file or directory could not be accessed.
 */
class stasys_FileAccessException extends stasys_Exception { }

/**
 * Invalid input.
 */
class stasys_InputException extends stasys_Exception { }

/**
 * Invalid parameter passed to a stasys method.
 */
class stasys_ParameterException extends stasys_InputException {

	/**
	 * Construct a new stasys_ParameterException.
	 *
	 * @param string    $param    The parameter name, without leading “$”.
	 * @param string    $msg      Informational message.
	 * @param int       $code     A numeric error code.
	 * @param Exception $previous The exception that led to this exception.
	 */
	public function __construct($param, $msg = 'invalid input', $code = 0, Exception $previous = null) {
		parent::__construct("\$$param: $msg", $code, $previous);
	}

}

/**
 * Insufficient permissions for something.
 */
class stasys_PermissionsException extends stasys_Exception { }
