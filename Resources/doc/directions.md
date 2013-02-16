Google Directions API Service
=============================

This bundle provides a service to interface with the directions API. There
is currently only one method, getDirections which takes a Request object
as it's parametor.


### Example Usage ###

```php
<?php

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route;

$orign       = "Some Origin Address";
$destination = "Some Destination";

$request = new Request($origin, $destination);

$request->setHasSensor();
$request->setMode(Request::MODE_WALKING);
$request->setWaypoints(["A waypoint for the trip]);
$request->optimizeWaypoints();

//...
$directionsService = $container->get('devtrw_google_maps.directions');
$response          = $directionsService->getDirections($request);

if ($response->getStatus == 'OK') {
    /**
     * @var Route[] $routes
     */
    $routes = $response->getRoutes();
    
    //Do something with the routs
}
```
