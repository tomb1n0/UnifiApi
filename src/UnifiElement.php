<?php

namespace UnifiAPI;

abstract class UnifiElement {

	protected $config;
	protected $api;
	protected $site_id;

	public function __construct($data, $api) {
		$this->api = $api;
		$this->config = $data;
		$this->site_id = $api->controller()->site_id();
	}

	public function __get($key) {
		if (isset($this->config->$key)) {
			return $this->config->$key;
		}
		return null;
	}

	public function getData() {
		return $this->config;
	}

	public function __isset($key) {
		return isset($this->config->$key);
	}

	// proxy requests for the controller through our api object
	public function controller() {
		return $this->api->controller();
	}

}
