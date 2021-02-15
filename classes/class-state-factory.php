<?php

namespace mp_timetable\plugin_core\classes;

/**
 * Singleton factory
 */
class State_Factory {

	protected static $instance;
	protected $namespace;

	/**
	 * State_Factory constructor.
	 *
	 * @param string $namespace
	 */
	public function __construct($namespace = 'plugin_core') {
		$this->namespace = $namespace;
	}

	/**
	 * @return State_Factory
	 */
	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get register instance object
	 *
	 * @param null $value
	 *
	 * @return bool|\mp_timetable\plugin_core\classes\Model
	 */
	public function get_model($value = null) {
		$model = false;
		if ('model' == $value) {
			$model = Model::get_instance();
		} else {
			$class = "{$this->namespace}\classes\models\\" . ucfirst($value);
			if (class_exists($class)) {
				$model = $class::get_instance();
			}
		}
		return $model;
	}

	/**
	 * Get controller instance object
	 *
	 * @param null $value
	 *
	 * @return bool
	 */
	public function get_controller($value = null) {
		$controller = false;
		$class = "{$this->namespace}\classes\controllers\Controller_" . ucfirst($value);
		if (class_exists($class)) {
			$controller = $class::get_instance();
		}
		return $controller;
	}

	/**
	 *  Get Preprocessor instance object
	 *
	 * @param null $value
	 *
	 * @return bool
	 */
	public function get_preprocessor($value = null) {
		$preprocessor = false;
		$class = "{$this->namespace}\classes\preprocessors\Preprocessor_" . ucfirst($value);
		if (class_exists($class)) {
			$preprocessor = $class::get_instance();
		}
		return $preprocessor;
	}

}
