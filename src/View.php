<?php

/**
 * @link https://github.com/ManifestWebDesign/DABL
 * @link http://manifestwebdesign.com/redmine/projects/dabl
 * @author Manifest Web Design
 * @license    MIT License
 */

namespace Dabl\View;

use ArrayObject;
use Dabl\View\Exception\DirectoryNotFoundException;
use Dabl\View\Exception\ViewNotFoundException;

class View {

	protected static $directories = array();

	/**
	 * @var string
	 */
	protected $viewFile;

	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @param string $directory
	 */
	public static function addDirectory($directory) {
		$directory = str_replace('\\', '/', $directory);
		if (!is_dir($directory)) {
			throw new DirectoryNotFoundException("Directory '$directory' not found");
		}
		if (stripos(strrev($directory), '/') !== 0) {
			$directory .= '/';
		}
		if (!in_array($directory, self::$directories)) {
			self::$directories[] = $directory;
		}
	}

	/**
	 * @return array
	 */
	public static function getDirectories() {
		return self::$directories;
	}

	public function __toString() {
		return $this->render(true);
	}

	/**
	 * View constructor.
	 * @param string $view_file
	 * @param array $params
	 */
	public function __construct($view_file = null, $params = array()) {
		$this->setFile($view_file);
		$this->setParams($params);
	}

	/**
	 * @param type $view_file
	 * @param type $params
	 * @param type $return_output
	 * @return View
	 */
	public static function create($view_file = null, $params = array()) {
		return new self($view_file, $params);
	}

	/**
	 * @param string $view
	 * @param array|ArrayObject $params
	 * @param boolean $return_output
	 * @return string|null
	 */
	public static function load($view = null, $params = array(), $return_output = false) {
		return self::create($view, $params)->render($return_output);
	}

	/**
	 * @param string $view_file
	 * @return View
	 */
	public function setFile($view_file = null) {

		// normalize slashes
		$view_file = str_replace('\\', '/', $view_file);
		$view_file = trim($view_file, '/');

		foreach (self::$directories as &$directory) {
			if (is_file($directory . $view_file . '.php')) {
				$this->viewFile = $directory . $view_file . '.php';
				return $this;
			} elseif (is_dir($directory . $view_file)) {
				$this->viewFile = $view_file . '/index.php';
				return $this;
			}
		}

		// raise error if file doesn't exist
		throw new ViewNotFoundException(
			"View '$view_file' not found in directories: "
				. implode(', ', self::$directories)
		);
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return $this->viewFile;
	}

	/**
	 * @param array $params
	 * @return View
	 */
	public function setParams(&$params = null) {
		$this->params = $params;
		return $this;
	}

	/**
	 * @return array|ArrayObject
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @param boolean $return_output
	 * @return string|null
	 */
	public function render($return_output = false) {
		$params = &$this->params;

		if ($return_output) {
			ob_start();
		}

		// $params['my_var'] shows up as $my_var
		foreach ($params as $_var => &$_value) {
			$$_var = &$_value;
		}

		require $this->viewFile;

		if ($return_output) {
			return ob_get_clean();
		}
	}

}