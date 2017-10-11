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

	public function devices() {
		$devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device');
		return array_map(function ($device) {
				return new Device($device, $this->api);
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

	public function networks() {
		return array_map(function ($network) {
			return new Network($network, $this->api);
		}, $this->api->get('/api/s/' . $this->site_id . '/rest/portconf'));
	}

	public function sites() {
		return $this->api->get('api/self/sites');
	}

	public function controller() {
		return $this->controller;
	}

}
