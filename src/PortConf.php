<?php

namespace UnifiAPI;

class PortConf extends UnifiElement
{
    public function delete()
    {
        $this->api->delete('/api/s/' . $this->site_id . '/rest/portconf/' . $this->_id);
    }
}
