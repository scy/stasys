<?php

/**
 * Where stasys is installed.
 */
define('STASYS_ROOT', dirname(__FILE__));

/**
 * A shorter name for DIRECTORY_SEPARATOR.
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Autoloader for classes.
 *
 * A class like “stasys_Foo_Bar” will be searched for in the file
 * “<STASYS_ROOT>/stasys/Foo/Bar.php”.
 *
 * @param string $class The class name.
 *
 * @return null
 */
function __autoload($class) {
	$path = STASYS_ROOT . DS . str_replace('_', DS, $class) . '.php';
	require_once $path;
}
