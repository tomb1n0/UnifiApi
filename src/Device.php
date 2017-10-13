<?php

namespace UnifiAPI;

class Device extends UnifiElement {

	public function adopt() {
		// first of all check if we're already adopted
		if ($this->adopted) {
			throw new \Exception('Device ' . $this->mac . ' already adopted');
		}
		$this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'cmd' => 'adopt']);
	}

	public function forget() {
		$this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['mac' => $this->mac, 'cmd' => 'delete-device']);
	}

}
