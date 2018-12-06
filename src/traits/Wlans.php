<?php

namespace UnifiAPI\Traits;

trait Wlans {
	
	public function Wlans($data = []) {
		$wlans = $this->api->get('/api/s/' . $this->site_id . '/rest/wlanconf', $data);
		return array_map(function ($wlan) {
			return new \UnifiAPI\Wlan($wlan, $this->api);
		}, $wlans);
	}

}