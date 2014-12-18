<?php

namespace Mondo\AppBundle\Translator;

use Symfony\Component\Translation\Translator;

class MyTranslator {
	private static $instance;
	private $dic;

	private function __construct() {
		$this->dic = array();
	}

	public static function getInstance() {
		if(self::$instance === NULL) self::$instance = new MyTranslator();
		return self::$instance;
	}

	public function addTranslator($name, ITranslateable $it) {
		$this->dic[$name] = $it;
	}

	private function prTrans($name, $msg) {
		return $this->dic[$name]->getTranslator()->trans($msg);
	}

	public static function trans($name, $msg) {
		return self::$instance->prTrans($name, $msg);
	}
}

