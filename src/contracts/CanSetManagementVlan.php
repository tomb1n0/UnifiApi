<?php

namespace UnifiAPI\Contracts;

interface CanSetManagementVlan
{
    public function enable_port_vlan($network);
}
