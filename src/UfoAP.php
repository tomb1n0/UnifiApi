<?php

namespace UnifiAPI;

use UnifiAPI\Contracts\CanSetManagementVlan;
use UnifiAPI\Traits\SetsManagementVlan;

class UfoAP extends Device implements CanSetManagementVlan
{
    use SetsManagementVlan;
}
