<?php

namespace UnifiAPI;

class Device extends UnifiElement {

	public function adopt() {
		// first of all check if we're already adopted
		if ($this->adopted) {
			throw new \Exception('Device ' . $this->mac . ' already adopted');
		}
		$this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'cmd' => 'adopt']);
	}

	public function forget() {
		$this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['mac' => $this->mac, 'cmd' => 'delete-device']);
	}

	public function enable_port_vlan($network) {
		if (!isset($network->native_networkconf_id)) {
			throw new \Exception('native_networkconf_id not provided');
		}
		$this->api->put('/api/s/' . $this->site_id .'/rest/device/' . $this->_id, ['mgmt_network_id' => $network->native_networkconf_id, 'switch_vlan_enabled' => true]);
	}

}
