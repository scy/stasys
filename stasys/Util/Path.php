<?php

/**
 * Utilities for file path parsing and manipulation.
 */
class stasys_Util_Path {

	/**
	 * Convert a relative path to an absolute one.
	 *
	 * If the path is relative, it will be converted to an absolute one by
	 * prepending $root. Note that relative expressions like “../” will _not_ be
	 * resolved. This will be implemented in the future. However, the path(s)
	 * specified do not necessarily have to exist.
	 *
	 * If the path is not relative, it will be returned unchanged.
	 *
	 * @todo Resolve relative expressions.
	 *
	 * @param string $path The path to absolutize.
	 * @param string $root The (absolute) path to prepend if $path is relative.
	 *
	 * @return string The absolutized path.
	 */
	public static function absolutize($path, $root = STASYS_ROOT) {
		// If $path is absolute, nothing needs to be done.
		if (self::isAbsolute($path)) {
			return $path;
		}
		// Check whether $root is actually absolute.
		if (!self::isAbsolute($root)) {
			throw new stasys_ParameterException('root', 'needs to be an absolute path');
		}
		// Return $path prepended with $root.
		return self::combine($root, $path);
	}

	/**
	 * Combine several relative paths with each other and possibly an absolute
	 * one.
	 *
	 * Can be called with several strings as parameters or an array of strings.
	 *
	 * If the paths contain an absolute one, it has to be the first one.
	 *
	 * @todo Sanity checks.
	 *
	 * @return string The paths combined with STASYS_DS.
	 */
	public static function combine() {
		$args = func_get_args();
		// If we have only one parameter and it is an array, use it as args.
		if (func_num_args() == 1 && is_array($args[0])) {
			$args = $args[0];
		}
		// Return the arguments, glued with slashes.
		return implode(STASYS_DS, $args);
	}

	/**
	 * Return whether $path is an absolute path.
	 *
	 * An absolute path starts with a slash character (“/”).
	 *
	 * @param string $path The path to check.
	 *
	 * @return bool True if it is absolute, false if not.
	 */
	public static function isAbsolute($path) {
		return $path[0] == '/';
	}

	/**
	 * Return whether $path is a readable directory.
	 *
	 * @param $path The path to check.
	 *
	 * @return bool Whether $path is a readable directory.
	 */
	public static function isReadableDir($path) {
		return is_dir($path) && is_readable($path);
	}

}
