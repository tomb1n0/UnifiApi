<?php

namespace UnifiAPI;

class Controller {

	protected $config;
	protected $site;
	protected $site_id;
	protected $api;

	public function __construct($site_name, $api) {
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

	public function site_id() {
		return $this->site_id;
	}

	public function device($mac_address) {
		$device = $this->api->get('/api/s/' . $this->site_id . '/stat/device/' . $mac_address);
		return new Device($device, $this->api);
	}

	public function devices($data = null) {
		$devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device', $data);
		return array_map(function ($device) {
				// figure out the type of device we're working with and return an instance of that, otherwise just return the default "device" object.
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
		$network = $this->networks(['vlan' => $vlan_id]);
		if (!empty($network)) {
			$port_conf = $this->port_confs(['native_networkconf_id' => $network[0]->_id]);
			return $port_conf[0];
		}
		return $port_conf;
	}

	public function sites() {
		return $this->api->get('api/self/sites');
	}

	public function create_vlan($vlan_name, $vlan_number) {
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

}
