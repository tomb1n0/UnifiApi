<?php

namespace UnifiAPI\Traits;

trait SetsManagementVlan
{
    public function enable_port_vlan($network)
    {
        if (!isset($network->_id)) {
            throw new \Exception('[enable_port_vlan] network provided does not have an _id');
        }
        $this->api->put('/api/s/' . $this->site_id . '/rest/device/' . $this->_id, ['mgmt_network_id' => $network->_id, 'switch_vlan_enabled' => true]);
    }
}
