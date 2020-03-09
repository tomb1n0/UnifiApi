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
					break;
				}
			}
		}
	}

	public function upgrade_custom($model,$url) {
		if ($model!=$this->model) {
			throw new \Exception('Not upgrading - device ' . $this->mac . ' is of type ' . $this->model. ' not '.$model);
		}
		$this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['url' => $url, 'mac' => $this->mac, 'cmd' => 'upgrade-external']);

	}

	public function name($newname) {
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, ["name" => $newname]);
	}

	public function wlangroup($newgroup_id) {
		$this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, [
			'wlangroup_id_na' => $newgroup_id,
			'wlangroup_id_ng' => $newgroup_id,
			'wlan_overrides' => []
		]);
	}

	public function icon() {
		return 'UniFi_AP_UAP-48.min.svg';
	}

}
