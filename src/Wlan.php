<?php

namespace UnifiAPI;

class Wlan extends UnifiElement
{
    public function update($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this->api->put('/api/s/' . $this->site_id . '/rest/wlanconf/' . $this->_id, $this);
    }
}
