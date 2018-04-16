<?php

namespace UnifiAPI;

class WallPlateAP extends Device {

	public function enable_port_vlan($network) {
		if (!isset($network->_id)) {
			throw new \Exception('[enable_port_vlan] network provided does not have an _id');
		}
		$this->api->put('/api/s/' . $this->site_id .'/rest/device/' . $this->_id, ['mgmt_network_id' => $network->_id, 'switch_vlan_enabled' => true]);
	}

	/**
	 * 	Unfortunately you can't set the ports individually, you have to pass both through.
	 */
	public function set_port_vlans($poe_port_conf, $data_port_conf) {
		if (!$this->switch_vlan_enabled) {
			throw new \Exception('[set_port_networks] this ap does not have switch_vlans enabled, please call enable_port_vlan() first with a management VLAN');
		}
		if (!isset($poe_port_conf->_id) || !isset($data_port_conf->_id)) {
			throw new \Exception('[set_port_networks] one or more networks provided do not have an _id');
		}
		$data = ['port_overrides' => []];
		$data['port_overrides'][] = ['name' => 'PoE Out + Data', 'op_mode' => 'switch', 'port_idx' => '1', 'portconf_id'  => $poe_port_conf->_id];
		$data['port_overrides'][] = ['name' => 'Data', 'op_mode' => 'switch', 'port_idx' => '2', 'portconf_id'  => $data_port_conf->_id];
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, $data);
	}

	public function clear_port_networks() {
		if (!$this->switch_vlan_enabled) {
			throw new \Exception('[set_port_networks] this ap does not have switch_vlans enabled, please call enable_port_vlan() first with a management VLAN');
		}
		$data = ['port_overrides' => []];
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, $data);
	}

}