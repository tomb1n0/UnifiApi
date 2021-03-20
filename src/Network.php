<?php

namespace UnifiAPI;

class Network extends UnifiElement
{
    public function delete()
    {
        $this->api->delete('/api/s/' . $this->site_id . '/rest/networkconf/' . $this->_id);
    }
}
