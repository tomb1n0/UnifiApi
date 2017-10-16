<?php

namespace UnifiAPI;

class PortConf extends UnifiElement {

	public function delete() {
		throw new Exception('Cant delete a portconf, please use network->delete instead');
	}

}
