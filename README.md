# Unifi API

This package was designed to make it easy to work with a Unifi controller.

# Installation

Make sure you have [composer](https://getcomposer.org/) installed, then run `composer require tomb1n0/unifiapi` from the root of your project.

Make sure to then include the composer autoload file in your code.

```php
<?php
  
require_once('vendor/autoload.php');

// replace these with your own details.
define('UNIFI_URL', 'https://unifi.yourdomain.co.uk:8443');
define('UNIFI_SITE', 'Southampton');
define('UNIFI_USERNAME', 'admin');
define('UNIFI_PASSWORD', 'unifipassword');

// get an instance of the api
$api = new UnifiAPI\API(UNIFI_URL, UNIFI_SITE, UNIFI_USERNAME, UNIFI_PASSWORD);

// get a controller object
$controller = $api->controller();
```

# Examples

### Get controller health (number of APs online etc)

```php
$health = $controller->health();

$wlan_health = $health[0];
$wan_health = $health[1];
$www_health = $health[2];
$lan_health = $health[3];
$vpn_health = $health[4];

echo 'Num Adopted APs: ' . $wlan_health['num_adopted'];
```

### Fetching any unadopted WAPs

```php
// get any unadopted WAPs
$devices = $controller->unadopted_devices();

// Adopt each of them
foreach ($devices as $device) {
    $device->adopt();
}
```

