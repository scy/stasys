<?php

/**
 * Source enumerator for files.
 */
class stasys_Source_Files extends stasys_Source_Enumerator {

	/**
	 * The next file to return.
	 */
	protected $currentFile = null;

	/**
	 * The directory handles, one for each subdirectory we’re currently in.
	 */
	protected $dir = array();

	/**
	 * The directory hierarchy we’re currently scanning.
	 *
	 * Could be implemented as a string to save memory, but currently isn’t.
	 */
	protected $dirname = array();

	/**
	 * The running number of $currentFile.
	 */
	protected $num = -1;

	/**
	 * The base path.
	 */
	protected $path = null;

	/**
	 * Create a new enumerator.
	 *
	 * @param string $path The path where the files live. Use slashes, not
	 *                     backslashes, even on Windows. If you use a relative
	 *                     path, it will be relative to STASYS_ROOT.
	 */
	public function __construct($path) {
		// Absolutize.
		$path = stasys_Util_Path::absolutize($path);
		// Check whether readable.
		if (!stasys_Util_Path::isReadableDir($path)) {
			throw new stasys_PermissionsException("“{$path}” is not a readable directory");
		}
		$this->path = $path;
	}

	/**
	 * Look for the next file to return.
	 */
	protected function getNext() {
		while (true) {
			// Are there no directories left in the hierarchy?
			if (count($this->dir) == 0) {
				break;
			}
			$file = readdir($this->dir[0]);
			// If there is no file left in this directory, move upwards.
			if ($file === false) {
				closedir($this->dir[0]);
				array_shift($this->dir);
				array_shift($this->dirname);
				continue;
			}
			// If the file is “.” or “..”, ignore it.
			if ($file == '.' || $file == '..') {
				continue;
			}
			$rel = $this->dirname[0] === '' ?
			       $file :
			       stasys_Util_Path::combine($this->dirname[0], $file);
			$abs = stasys_Util_Path::absolutize($rel, $this->path);
			// If the file is a readable directory, scan it.
			if (stasys_Util_Path::isReadableDir($abs)) {
				$handle = opendir($abs);
				if ($handle === false) {
					throw new stasys_FileAccessException("could not open directory handle for “{$abs}”");
				}
				array_unshift($this->dir, $handle);
				array_unshift($this->dirname, $rel);
				continue;
			}
			// Else, this is the current file.
			$this->currentFile = $rel;
			$this->num++;
			break;
		}
	}

	/**
	 * Return the current file.
	 */
	public function current() {
		return $this->currentFile;
	}

	/**
	 * Return the number of the current file.
	 */
	public function key() {
		return $this->num;
	}

	/**
	 * Move to the next element.
	 */
	public function next() {
		$this->getNext();
	}

	/**
	 * Rewind the iteration.
	 */
	public function rewind() {
		// Close any open handles.
		foreach ($this->dir as $handle) {
			closedir($handle);
		}
		// Start again by opening the top level.
		$handle = opendir($this->path);
		if ($handle === false) {
			throw new stasys_FileAccessException("could not open directory handle for “{$this->path}”");
		}
		$this->dir = array($handle);
		$this->dirname = array('');
		$this->num = -1;
		$this->getNext();
	}

	/**
	 * Whether we are currently on a valid element or done.
	 */
	public function valid() {
		// We are still valid if there is a directory left to scan.
		return count($this->dir) != 0;
	}

}
