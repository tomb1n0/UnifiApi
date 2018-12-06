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

	public function online_clients_for_vlan($vlan_id) {
		$raw_clients = $this->api->get('/api/s/' . $this->site_id . '/stat/sta');
		return array_map(
			function ($client) {
				return new Client($client, $this->api);
			},
			array_filter($raw_clients, function ($client) use ($vlan_id) {
				return isset($client->vlan) && $client->vlan == (int) $vlan_id;
			})
		);
	}

}