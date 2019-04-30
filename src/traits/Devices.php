<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\WallPlateAP;
use \UnifiAPI\Device;
use GuzzleHttp\Exception\ClientException;

trait Devices {

	public function device($mac_address, $wanted_fields = []) {
		return $this->build_device($mac_address, $wanted_fields);
	}

	private function devices_basic() {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/device-basic');
	}

	public function all_devices($wanted_fields = []) {
		$devices = $this->devices_basic();
		return array_map(function ($device) use ($wanted_fields) {
			return $this->build_device($device->mac, $wanted_fields);
		}, $devices);
	}

	private function build_device($mac, $wanted_fields) {
		try {
			list($full_info) = $this->api->get('/api/s/' . $this->site_id . '/stat/device', ['macs' => [$mac]]);
		} catch (ClientException $e) {
			return null;
		}
		$device = new \StdClass;
		// always have fields used by this library in the wanted fields by default
		$wanted_fields = array_unique(
			array_merge(
				$wanted_fields,
				['model', 'mac', 'switch_vlan_enabled', '_id', 'adopted', 'version']
			)
		);
		foreach ($wanted_fields as $key) {
			if (isset($full_info->$key)) {
				$device->$key = $full_info->$key;
			}
		}
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

}
