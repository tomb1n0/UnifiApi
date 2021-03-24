<?php

namespace UnifiAPI;

class Device extends UnifiElement
{
    protected $icons = [
        'default' => 'uap/missing/grid@2x.png',
        'uap' => 'uap/missing/grid@2x.png',
        'BZ2' => 'uap/BZ2/grid@2x.png',
        'BZ2LR' => 'uap/BZ2/grid@2x.png',
        'p2N' => 'uap/p2N/grid@2x.png',
        'U2HSR' => 'uap/U2HSR/grid@2x.png',
        'U2IW' => 'uap/U2IW/grid@2x.png',
        'U2L48' => 'uap/BZ2/grid@2x.png',
        'U2Lv2' => 'uap/BZ2/grid@2x.png',
        'U2M' => 'uap/default/grid@2x.png',
        'U2O' => 'uap/U2O/grid@2x.png',
        'U2S48' => 'uap/BZ2/grid@2x.png',
        'U2Sv2' => 'uap/BZ2/grid@2x.png',
        'U5O' => 'uap/U2O/grid@2x.png',
        'U7E' => 'uap/U7E/grid@2x.png',
        'U7EDU' => 'uap/U7EDU/grid@2x.png',
        'U7Ev2' => 'uap/U7E/grid@2x.png',
        'U7HD' => 'uap/default/grid@2x.png',
        'U7IW' => 'uap/U7IW/grid@2x.png',
        'U7IWP' => 'uap/U7IW/grid@2x.png',
        'U7LR' => 'uap/default/grid@2x.png',
        'U7LT' => 'uap/default/grid@2x.png',
        'U7MP' => 'uap/U7O/grid@2x.png',
        'U7MSH' => 'uap/U7MSH/grid@2x.png',
        'U7NHD' => 'uap/U7NHD/grid@2x.png',
        'U7O' => 'uap/U7O/grid@2x.png',
        'UFLHD' => 'uap/UFLHD/grid@2x.png',
        'U7P' => 'uap/default/grid@2x.png',
        'U7PG2' => 'uap/default/grid@2x.png',
        'U7SHD' => 'uap/default/grid@2x.png',
        'UCMSH' => 'uap/default/grid@2x.png',
        'UCXG' => 'uap/default/grid@2x.png',
        'UHDIW' => 'uap/U7IW/grid@2x.png',
        'UAIW6' => 'uap/UAIW6/grid@2x.png',
        'UAE6' => 'uap/UAE6/grid@2x.png',
        'UAL6' => 'uap/UAL6/grid@2x.png',
        'UAM6' => 'uap/UAM6/grid@2x.png',
        'UALR6' => 'uap/UALR6/grid@2x.png',
        'UAP6' => 'uap/UAP6/grid@2x.png',
        'UALR6v2' => 'uap/UAP6/grid@2x.png',
        'UALR6v3' => 'uap/UAP6/grid@2x.png',
        'ULTE' => 'uap/ULTE/grid@2x.png',
        'ULTEPUS' => 'uap/ULTE/grid@2x.png',
        'ULTEPEU' => 'uap/ULTE/grid@2x.png',
        'UXSDM' => 'uap/UXSDM/grid@2x.png',
        'UXBSDM' => 'uap/UXBSDM/grid@2x.png',
        'UDMB' => 'uap/UDMB/grid@2x.png',
        'UP1' => 'uap/UP1/grid@2x.png',
        'UP6' => 'uap/UP6/grid@2x.png',
        'UBB' => 'ubb/UBB/grid@2x.png',
        'ugw' => 'ugw/missing/grid@2x.png',
        'UGW3' => 'ugw/UGW3/grid@2x.png',
        'UGW4' => 'ugw/UGW4/grid@2x.png',
        'UGWXG' => 'ugw/UGWXG/grid@2x.png',
        'usw' => 'usw/missing/grid@2x.png',
        'S216150' => 'usw/US16/grid@2x.png',
        'S224250' => 'usw/US24/grid@2x.png',
        'S224500' => 'usw/US24/grid@2x.png',
        'S248500' => 'usw/US48/grid@2x.png',
        'S248750' => 'usw/US48/grid@2x.png',
        'S28150' => 'usw/US8P150/grid@2x.png',
        'UDC48X6' => 'usw/UDC48X6/grid@2x.png',
        'US16P150' => 'usw/US16/grid@2x.png',
        'US24' => 'usw/US24/grid@2x.png',
        'US24P250' => 'usw/US24/grid@2x.png',
        'US24P500' => 'usw/US24/grid@2x.png',
        'US24PL2' => 'usw/US24/grid@2x.png',
        'US24PRO' => 'usw/US24PRO/grid@2x.png',
        'US24PRO2' => 'usw/US24PRO2/grid@2x.png',
        'US48' => 'usw/US48/grid@2x.png',
        'US48P500' => 'usw/US48/grid@2x.png',
        'US48P750' => 'usw/US48/grid@2x.png',
        'US48PL2' => 'usw/US48/grid@2x.png',
        'US48PRO' => 'usw/US48PRO/grid@2x.png',
        'US48PRO2' => 'usw/US48PRO2/grid@2x.png',
        'US6XG150' => 'usw/US6XG150/grid@2x.png',
        'US8' => 'usw/US8/grid@2x.png',
        'US8P150' => 'usw/US8P150/grid@2x.png',
        'US8P60' => 'usw/US8P60/grid@2x.png',
        'USC8' => 'usw/US8/grid@2x.png',
        'USC8P450' => 'usw/USC8P450/grid@2x.png',
        'USF5P' => 'usw/USF5P/grid@2x.png',
        'USXG' => 'usw/USXG/grid@2x.png',
        'USL8LP' => 'usw/USL8LP/grid@2x.png',
        'USL8A' => 'usw/USL8A/grid@2x.png',
        'USL16LP' => 'usw/USL16LP/grid@2x.png',
        'USL16P' => 'usw/USL16P/grid@2x.png',
        'USL24' => 'usw/USL24/grid@2x.png',
        'USL48' => 'usw/USL48/grid@2x.png',
        'USL24P' => 'usw/USL24P/grid@2x.png',
        'USL48P' => 'usw/USL48P/grid@2x.png',
        'USMINI' => 'usw/USMINI/grid@2x.png',
        'USPRPS' => 'usw/USPRPS/grid@2x.png',
        'USPPDUP' => 'usw/USPPDUP/grid@2x.png',
        'UAS' => 'uas/UAS/grid@2x.png',
        'UCK' => 'uas/UCK/grid@2x.png',
        'UCKG2' => 'uas/UCKG2/grid@2x.png',
        'UCKP' => 'uas/UCKP/grid@2x.png',
        'UMAD' => 'ua/UMAD/grid@2x.png',
        'UDM' => 'udm/UDM/grid@2x.png',
        'UDM-UAP' => 'udm/UDM-UAP/grid@2x.png',
        'UDM-USW' => 'udm/UDM-USW/grid@2x.png',
        'UDM-UGW' => 'udm/UDM-UGW/grid@2x.png',
        'UDMSE' => 'udm/UDMSE/grid@2x.png',
        'UDMSE-UAP' => 'udm/UDM-UAP/grid@2x.png',
        'UDMSE-USW' => 'udm/UDM-USW/grid@2x.png',
        'UDMSE-UGW' => 'udm/UDM-UGW/grid@2x.png',
        'UDMPRO' => 'udm/UDMPRO/grid@2x.png',
        'UDMPRO-USW' => 'udm/UDMPRO-USW/grid@2x.png',
        'UDMPRO-UGW' => 'udm/UDMPRO-UGW/grid@2x.png',
        'UXGPRO' => 'uxg/UXGPRO/grid@2x.png'
    ];

    public function adopt()
    {
        if ($this->adopted) {
            throw new \Exception('Device ' . $this->mac . ' already adopted');
        }
        $this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'cmd' => 'adopt']);
    }

    public function restart()
    {
        $this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['mac' => $this->mac, 'cmd' => 'restart', 'reboot_type' => 'soft']);
    }

    public function forget()
    {
        $this->api->post('/api/s/' . $this->site_id . '/cmd/sitemgr', ['mac' => $this->mac, 'cmd' => 'delete-device']);
    }

    public function upgrade()
    {
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

    public function upgrade_custom($model, $url)
    {
        if ($model != $this->model) {
            throw new \Exception('Not upgrading - device ' . $this->mac . ' is of type ' . $this->model . ' not ' . $model);
        }
        $this->api->post('/api/s/' . $this->site_id . '/cmd/devmgr', ['url' => $url, 'mac' => $this->mac, 'cmd' => 'upgrade-external']);
    }

    public function name($newname)
    {
        $this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, ["name" => $newname]);
    }

    public function wlangroup($newgroup_id)
    {
        $this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, [
            'wlangroup_id_na' => $newgroup_id,
            'wlangroup_id_ng' => $newgroup_id,
            'wlan_overrides' => []
        ]);
    }

    public function icon()
    {
        if (isset($this->model) && array_key_exists($this->model, $this->icons)) {
            return $this->icons[$this->model];
        }

        if (isset($this->type) && array_key_exists($this->type, $this->icons)) {
            return $this->icons[$this->type];
        }

        return $this->icons['default'];
    }
}
