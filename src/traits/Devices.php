<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\WallPlateAP;
use \UnifiAPI\Device;
use GuzzleHttp\Exception\ClientException;

trait Devices {

	public function device($mac_address, $wanted_fields = []) {
		$devices = $this->build_devices([$mac_address], $wanted_fields, 1);
		return $devices[0];
	}

	private function devices_basic() {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/device-basic');
	}

	//$chunk_size allows tradeoff between max memory usage and number of api requests(speed)
	// smaller -> more memory efficient but slower
	public function all_devices($wanted_fields = [], $chunk_size = 40) {
		$devices = $this->devices_basic();
		$macs = array_map(function ($device) {
			return $device->mac;
		}, $devices);
		return $this->build_devices($macs, $wanted_fields, $chunk_size);
	}

	private function build_devices($all_macs, $wanted_fields, $chunk_size) {
		// always have fields used by this library in the wanted fields by default
		$wanted_fields = array_unique(
			array_merge(
				$wanted_fields,
				['model', 'mac', 'switch_vlan_enabled', '_id', 'adopted', 'version']
			)
		);
		$out = [];
		foreach (array_chunk($all_macs, $chunk_size) as $macs) {
			try {
				$devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device', ['macs' => $macs]);
			} catch (ClientException $e) {
				return null;
			}
			foreach ($devices as $full_info) {
				$device = new \StdClass;
				foreach ($wanted_fields as $key) {
					if (isset($full_info->$key)) {
						$device->$key = $full_info->$key;
					}
				}
				switch ($device->model) {
					case 'U2IW':
					case 'U7IW':
					case "U7IWP":
						$dev = new WallPlateAP($device, $this->api);
						break;
					default:
						$dev = new Device($device, $this->api);
						break;
				}
				$out[] = $dev;
			}
			unset($devices);
		}
		return $out;
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
