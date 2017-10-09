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

	public function enable_management_vlan() {
		// https://unifi:8443/api/s/qv7d8zqs/rest/device/59d26e8ce4b06feea2ec218e
		// PUT request
		// mgmt_network_id : 59ce6df8e4b06feea2ebf2ec
		// switch_vlan_enabled: true
	}

}
