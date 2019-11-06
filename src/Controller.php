<?php

namespace UnifiAPI;

use GuzzleHttp\Exception\ClientException;
use UnifiAPI\Exceptions\InvalidSiteException;

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
			throw new InvalidSiteException('No such site ' . $site_name . ' exists');
		}
	}

	public function api() {
		return $this->api;
	}

	public function site_id() {
		return $this->site_id;
	}

	public function site() {
		return $this->site;
	}

	public function sites() {
		return $this->api->get('api/self/sites');
	}

	public function health($data = []) {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/health', $data);
	}

	public function widgetHealth($data = []) {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/widget/health', $data)[0];
	}

	public function insights($hours = 1) {
		return $this->api->get('/api/s/' . $this->site_id . '/stat/rogueap?within=' . $hours . '&_limit=10000');
	}

	public function delete_site() {
		return $this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['site' => $this->site->_id, 'cmd' => 'delete-site']);
	}

	public function create_site($name) {
		foreach ($this->sites() as $site) {
			if ($site->desc == $name) {
				throw new InvalidSiteException('Site ' . $name . ' already exists');
			}
		}
		return $this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['desc' => $name, 'cmd' => 'add-site']);
	}

}
