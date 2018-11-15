<?php

namespace UnifiAPI;

class UnknownDevice extends UnifiElement {

	public function __construct($data, &$api) {
		parent::__construct($data, $api);
		if (!isset($this->config->model)) $this->config->model = 'Unknown';
	}

	public function icon() {
		return 'Unknown.min.svg';
	}

}
