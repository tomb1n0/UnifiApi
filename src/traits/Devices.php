<?php

namespace UnifiAPI\Traits;

use GuzzleHttp\Exception\ClientException;

trait Devices
{
    // Can't use ::class as we need to support php 5.4 :(
    protected $models = [
        'Default' => '\UnifiAPI\Device',

        // Wallplate APs
        'U2IW' => '\UnifiAPI\Device', // doesn't support management vlan so just use regular device class
        'U7IW' => '\UnifiAPI\WallPlateAP',
        'U7IWP' => '\UnifiAPI\WallPlateAP'
    ];

    public function device($mac_address, $wanted_fields = [])
    {
        $devices = $this->build_devices([$mac_address], $wanted_fields, 1);
        return $devices[0];
    }

    private function devices_basic()
    {
        return $this->api->get('/api/s/' . $this->site_id . '/stat/device-basic');
    }

    public function device_index()
    {
        return $this->devices_basic();
    }

    public function devices_by_mac($macs, $wanted_fields = [], $chunk_size = 40)
    {
        return $this->build_devices($macs, $wanted_fields, $chunk_size);
    }

    //$chunk_size allows tradeoff between max memory usage and number of api requests(speed)
    // smaller -> more memory efficient but slower
    public function all_devices($wanted_fields = [], $chunk_size = 40)
    {
        $devices = $this->devices_basic();
        $macs = array_map(function ($device) {
            return $device->mac;
        }, $devices);
        return $this->build_devices($macs, $wanted_fields, $chunk_size);
    }

    private function build_devices($all_macs, $wanted_fields, $chunk_size)
    {
        // always have fields used by this library in the wanted fields by default
        $wanted_fields = array_unique(
            array_merge(
                $wanted_fields,
                ['model', 'mac', 'switch_vlan_enabled', '_id', 'adopted', 'version']
            )
        );
        $out = [];
        foreach (array_chunk($all_macs, $chunk_size) as $macs) {
            try {
                $devices = $this->api->get('/api/s/' . $this->site_id . '/stat/device', ['macs' => $macs]);
            } catch (ClientException $e) {
                return null;
            }
            foreach ($devices as $full_info) {
                $device = new \StdClass;
                foreach ($wanted_fields as $key) {
                    if (isset($full_info->$key)) {
                        $device->$key = $full_info->$key;
                    }
                }

                $modelClass = array_key_exists($device->model, $this->models)
                    ? $this->models[$device->model]
                    : $this->models['Default'];

                $out[] = new $modelClass($device, $this->api);
            }
            unset($devices);
        }
        return $out;
    }

    public function unadopted_devices($wanted_fields = [])
    {
        $devices = $this->devices_basic();
        $macs = array_map(function ($device) {
            return !$device->adopted ? $device->mac : null;
        }, $devices);
        return $this->build_devices(array_filter($macs), $wanted_fields, 100 /*unadopted devices are much smaller*/);
    }
}
