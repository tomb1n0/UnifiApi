<?php

namespace UnifiAPI\Traits;

use \UnifiAPI\RadiusProfile;

trait RadiusProfiles
{
    public function radiusProfiles($data = [])
    {
        $profiles = $this->api->get('/api/s/' . $this->site_id . '/rest/radiusprofile', $data);
        return array_map(function ($profile) {
            return new RadiusProfile($profile, $this->api);
        }, $profiles);
    }

    public function radiusProfileByName($name)
    {
        $profiles = $this->radiusProfiles();
        foreach ($profiles as $profile) {
            if (strtolower($profile->name) == strtolower($name)) {
                return $profile;
            }
        }
    }

    public function createRadiusProfile($data)
    {
        $this->api->post('/api/s/' . $this->site_id . '/rest/radiusprofile', $data);
    }
}
