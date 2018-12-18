<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\RadiusProfile;

trait RadiusProfiles {

	public function radiusProfiles($data = []) {
		$profiles = $this->api->get('/api/s/' . $this->site_id . '/rest/radiusprofile', $data);
		return array_map(function ($profile) {
			return new RadiusProfile($profile, $this->api);
		}, $profiles);
	}

}
