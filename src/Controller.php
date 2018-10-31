<?php

namespace UnifiAPI;

use GuzzleHttp\Exception\ClientException;


class Controller {

	protected $config;
	protected $site;
	protected $site_id;
	protected $api;

	public function __construct($site_name, &$api) {
		$this->api = $api;
		foreach ($this->sites() as $site) {
			if ($site->desc == $site_name) {
				$this->site = $site;
				$this->site_id = $site->name;
			}
		}
		if (!isset($this->site)) {
			throw new \Exception('No such site ' . $site_name . ' exists');
		}
	}

	public function api() {
		return $this->api;
	}

	public function site_id() {
		return $this->site_id;
	}

	public function online_clients() {
		return array_map(
			function ($client) {
				return new Client($client, $this->api);
			},
			$this->api->get('/api/s/' . $this->site_id . '/stat/sta')
		);
	}

	public function device($mac_address) {
		list($device) = $this->devices(['macs' => [$mac_address]]);
		return $device;
	}

	public function devices($data = null) {
		try {
			$devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device-basic', $data);
		} catch (ClientException $e) {
			return null;
		}
		return array_map(function ($device) {
				// figure out the type of device we're working with and return an instance of that, otherwise just return the default "device" object.
				$full_info = $this->api->get('/api/s/' . $this->site_id . '/stat/device', ['macs' => [$device->mac]]);
				$device->model = $full_info[0]->model;
				switch ($device->model) {
					case 'U2IW':
					case 'U7IW':
					case "U7IWP":
						return new WallPlateAP($device, $this->api);
						break;
					default:
						return new Device($device, $this->api);
						break;
				}

		}, $devices);
	}

	public function unadopted_devices() {
		$devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device');
		$ret_devs = [];
		foreach ($devices as $device) {
			if (!$device->adopted) {
				$ret_devs[] = new Device($device, $this->api);
			}
		}
		return $ret_devs;
	}

	/**
	 * Get networks from the controller, optionally providing some data to filter the networks on. (vlan, name etc)
	 */
	public function networks($data = null) {
		return array_map(function ($network) {
			return new Network($network, $this->api);
		}, $this->api->get('/api/s/' . $this->site_id . '/rest/networkconf', $data));
	}

	public function port_confs($data = null) {
		return array_map(function ($port_conf) {
			return new PortConf($port_conf, $this->api);
		}, $this->api->get('/api/s/' . $this->site_id . '/rest/portconf', $data));
	}

	public function port_conf_for_vlan($vlan_id) {
		$port_conf = null;
		$network = $this->network_conf_for_vlan($vlan_id);
		if (!is_nulL($network)) {
			$port_confs = $this->port_confs(['native_networkconf_id' => $network->_id]);
			if (!empty($port_confs)) {
				$port_conf = $port_confs[0];
			}
		}
		return $port_conf;
	}

	public function network_conf_for_vlan($vlan_id) {
		$network = null;
		// go off to the controller to fetch all networks but with this specific vlan id
		$networks = $this->networks(['vlan' => (string) $vlan_id]);
		if (!empty($networks)) {
			$network = $networks[0];
		}
		return $network;
	}

	public function get_switch_port_profile($native_vlan, $tagged_vlans = []) {
		$profile = null;
		$native_network_id = null;
		$native_network = $this->get_or_create_vlan($native_vlan);
		$native_network_id = $native_network->_id;
		$tagged_vlan_ids = [];
		sort($tagged_vlans);
		foreach ($tagged_vlans as $tag) {
			$network = $this->get_or_create_vlan($tag);
			$tagged_vlan_ids[] = $network->_id;
		}
		if (!is_null($native_network_id)) {
			$profiles = $this->port_confs(['native_networkconf_id' => $native_network_id, 'op_mode' => 'switch', 'tagged_networkconf_ids' => $tagged_vlan_ids]);
			if (!empty($profiles)) {
				$profile = $profiles[0];
			}
		}
		return $profile;
	}

	public function sites() {
		return $this->api->get('api/self/sites');
	}

	public function create_vlan($vlan_name, $vlan_number) {
		// prevent duplicate vlan
		$network = $this->network_conf_for_vlan($vlan_number);
		if (!is_null($network)) {
			throw new \Exception('Vlan ID ' . $vlan_number . ' already exists.');
		}
		$data = [
			'purpose' => 'vlan-only',
			'name' => $vlan_name,
			'vlan' => (string) $vlan_number,
			'enabled' => true,
			'is_nat' => true,
			'vlan_enabled' => true,
		];
		// create the new vlan, this can potentially throw an exception if the VLAN already exists.
		$this->api->post('/api/s/' . $this->site_id . '/rest/networkconf', $data);
	}

	public function get_or_create_vlan($vlan_id) {
		$network = $this->network_conf_for_vlan($vlan_id);

		if (!is_null($network)) {
			// we have this vlan already, go ahead and return it
			return $network;
		}
		// the vlan is null so we have to create it
		$this->create_vlan('Vlan ' . $vlan_id, $vlan_id);
		return $this->network_conf_for_vlan($vlan_id);
	}

	public function create_switch_port_profile($native_vlan_id, $tagged_vlan_ids = []) {
		if (in_array($native_vlan_id, $tagged_vlan_ids)) {
			throw new \Exception('Native vlan ID cannot be tagged');
		}
		// find the network for the native vlan
		$native_network = $this->get_or_create_vlan($native_vlan_id);
		$native_network_id = $native_network->_id;
		// now find all the tagged networks
		$tagged_networks_ids = [];
		sort($tagged_vlan_ids);
		foreach ($tagged_vlan_ids as $vlan_id) {
			$network = $this->get_or_create_vlan($vlan_id);
			$tagged_networks_ids[] = $network->_id;
		}
		$data = [
			'autoneg' => true,
			'dot1x_ctrl' => 'force_authorized',
			'forward' => 'customize',
			'lldpmed_enabled' => false,
			'name'=> 'Vlan ' . $native_vlan_id . ' Switch Port Profile',
			'native_networkconf_id' => $native_network_id,
			'op_mode' => 'switch',
			'tagged_networkconf_ids' => $tagged_networks_ids
		];
		$this->api->post('/api/s/' . $this->site_id . '/rest/portconf', $data);
	}

	public function get_or_create_switch_port_profile($native_vlan, $tagged_vlans = []) {
		$profile = $this->get_switch_port_profile($native_vlan, $tagged_vlans);
		if (!is_null($profile)) {
			return $profile;
		}
		// this profile is null so we need to go and create it
		$this->create_switch_port_profile($native_vlan, $tagged_vlans);
		return $this->get_switch_port_profile($native_vlan, $tagged_vlans);
	}

	public function health($data = []) {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/health', $data);
	}

}
