<?php
All pending changes were saved and applied successfully to 78:8a:20:80:9b:f9.


require_once('vendor/autoload.php');

// replace these with your own details.
define('UNIFI_URL', 'https://url:8443');
define('UNIFI_SITE', 'test_site');
define('UNIFI_USERNAME', 'administrator');
define('UNIFI_PASSWORD', 'password');

// get an instance of the api
$api = new UnifiAPI\API(UNIFI_URL, UNIFI_SITE, UNIFI_USERNAME, UNIFI_PASSWORD);

// you can use the api directly if you know the URLS.
$sites = $api->get('api/self/sites');

// otherwise you can get an instance of the controller
$controller = $api->controller();

// you can now call some helpful methods on the controller
$devices = $controller->unadopted_devices();

foreach ($devices as $device) {
	// devices are returned as their own object which have a ref to the API
	$device->adopt();
}

// you can fetch the networks from the controller, optionally providing data to filter on such as the VLAN number. pretty cool!
// this isn't implemeneted everywhere (e.g. devices doesn't seem to allow taking an array to filter on for some reason)
$networks = $controller->networks(['vlan' => 10]);
