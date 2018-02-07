<?php

namespace UnifiAPI;

class Device extends UnifiElement {

	public function adopt() {
		if ($this->adopted) {
			throw new \Exception('Device ' . $this->mac . ' already adopted');
		}
		$this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'cmd' => 'adopt']);
	}

	public function forget() {
		$this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['mac' => $this->mac, 'cmd' => 'delete-device']);
	}

	public function upgrade() {
		$firmwares = $this->api->post('/api/s/' . $this->site_id . '/cmd/firmware', ['cmd' => 'list-cached']);
		foreach ($firmwares as $firmware) {
			if ($firmware->device == $this->model) {
				if ($firmware->version != $this->version) {
					$this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'upgrade_to_firmware' => $firmware->version, 'cmd' => 'upgrade']);
				}
			}
		}
	}

}
