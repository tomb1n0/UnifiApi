<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\Client;

trait RadiusProfiles {

	public function radiusProfiles($data = []) {
		$profiles = $this->api->get('/api/s/' . $this->site_id . '/rest/radiusprofile', $data);
		return array_map(function ($profile) {
			return new \UnifiAPI\RadiusProfile($profile, $this->api);
		}, $profiles);
	}

}
