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
			throw new \Exception('[enable_port_vlan] native_networkconf_id not provided');
		}
		$this->api->put('/api/s/' . $this->site_id .'/rest/device/' . $this->_id, ['mgmt_network_id' => $network->native_networkconf_id, 'switch_vlan_enabled' => true]);
	}

	/**
	 * 	Unfortunately you can't set the ports individually, you have to pass both through.
	 * 	If you don't pass both through it will reset the missing port to default.
	 */
	public function set_wallplate_port_networks($poe_network, $data_network) {
		if (!isset($poe_network->_id) || !isset($data_network->_id)) {
			throw new \Exception('[set_port_network] invalid networks');
		}
		$data = ['port_overrides' => []];
		// PoE Out + Data
		$data['port_overrides'][] = ['name' => 'VOICE PORT', 'op_mode' => 'switch', 'port_idx' => '1', 'portconf_id'  => $poe_network->_id];
		// Data
		$data['port_overrides'][] = ['name' => 'DATA PORT', 'op_mode' => 'switch', 'port_idx' => '2', 'portconf_id'  => $data_network->_id];
		var_dump(json_encode($data));
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, $data);
	}

	public function clear_wallplate_port_networks() {
		$data = ['port_overrides' => []];
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, $data);
	}

}
