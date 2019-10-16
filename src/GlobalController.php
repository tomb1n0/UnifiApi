<?php

namespace UnifiAPI;

class GlobalController {

    protected $api;
    protected $controller;

    public function __construct($controller) {
        $this->controller = $controller;
        $this->api = $controller->api();
    }

    public function find_site_from_mac($mac_address) {
        foreach ($this->controller->sites() as $site) {
            if ($site->name == "default") continue;
            $controller = new Controller($site->desc, $this->api);
            if ($controller->device($mac_address)) {
                return $site;
            }
        }
    }

    /**
     * Execute the given closure against every unifi site we have
     */
    public function eachSite(\Closure $callback) {
        foreach ($this->controller->sites() as $site) {
            if ($site->name == "default") continue;
            call_user_func_array($callback, [new Controller($site->desc, $this->api), $site->desc]);
        }
    }

}