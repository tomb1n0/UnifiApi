<?php

namespace UnifiAPI;

use GuzzleHttp\Exception\ClientException;

class Controller {

	use Traits\Wlans;
	use Traits\Clients;
	use Traits\Devices;
	use Traits\Networks;
	use Traits\RadiusProfiles;

	protected $config;
	protected $site;
	protected $site_id;
	protected $api;

	public function __construct($site_name, &$api) {
		$this->api = $api;
		// check if we need to refresh our api instance
		if ($this->api->site_name() != $site_name) {
			$this->api->switch_site($site_name);
		}
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

	public function api() {
		return $this->api;
	}

	public function site_id() {
		return $this->site_id;
	}

	public function sites() {
		return $this->api->get('api/self/sites');
	}

	public function health($data = []) {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/health', $data);
	}

}
