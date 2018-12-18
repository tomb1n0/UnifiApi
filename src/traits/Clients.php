<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\Client;

trait Clients {
	
	public function online_clients($data = []) {
		return array_map(
			function ($client) {
				return new Client($client, $this->api);
			},
			$this->api->get('/api/s/' . $this->site_id . '/stat/sta', $data)
		);
	}

	public function filter_clients($key, $value) {
		$raw_clients = $this->api->get('/api/s/' . $this->site_id . '/stat/sta');
		$data = array_map(
			function ($client) {
				return new Client($client, $this->api);
			},
			array_filter($raw_clients, function ($client) use ($key, $value) {
				return isset($client->$key) && $client->$key == $value;
			})
		);
		return array_values($data);
	}

	public function online_clients_for_vlan($vlan_id) {
		return $this->filter_clients('vlan', (int) $vlan_id);
	}

	public function online_clients_for_ssid($ssid_name) {
		return $this->filter_clients('essid', $ssid_name);
	}

}