<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Entity\Directions;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg;

/**
 * Maps the "Route" part of the reply from the Google Maps Directions API
 *
 * @author Steven Nance <steven@devtrw.com>
 *
 * @url https://developers.google.com/maps/documentation/directions/#DirectionsResponses
 */
class Route
{
    /**
     * A short textual description for the route, suitable for naming
     * and disambiguating the route from alternatives.
     *
     * @var string
     */
    private $summary;

    /**
     *  An array which contains information about a leg of the route,
     *  between two locations within the given route. A separate leg will be
     *  present for each waypoint or destination specified. (A route with no
     *  way points will contain exactly one leg within the legs array.) Each
     * leg consists of a series of steps.
     *
     * @var Leg[]
     */
    private $legs;

    /**
     * An array indicating the order of any way points in the calculated route.
     * This way points may be reordered. If the request was passed
     * optimize:true within its way points parameter.
     *
     * @var array
     */
    private $waypointOrder;

    /**
     * An object holding an array of encoded points that represent an approximate
     * (smoothed) path of the resulting directions.
     *
     * @var array
     */
    private $overviewPolyline;

    /**
     * The viewport bounding box of this route.
     *
     * @var array
     */
    private $bounds;

    /**
     * The copyrights text to be displayed for this route.
     *
     * @var string
     */
    private $copyrights;

    /**
     * An array of warnings to be displayed when showing these directions.
     *
     * @var array
     */
    private $warnings;

    /**
     * Stores the total distance in meters to avoid recalculation
     *
     * @var int
     */
    private $totalDistance;

    /**
     * @param array $routeArray
     *
     * @return Route
     */
    public static function assembleFromArray($routeArray)
    {
        $route = new Route();

        foreach ($routeArray['legs'] as $leg) {
            $route->legs[] = Leg::assembleFromArray($leg);
        }

        $route->summary          = $routeArray['summary'];
        $route->waypointOrder    = $routeArray['waypoint_order'];
        $route->bounds           = $routeArray['bounds'];
        $route->copyrights       = $routeArray['copyrights'];
        $route->warnings         = $routeArray['warnings'];
        $route->overviewPolyline = (array) $routeArray['overview_polyline'];

        return $route;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return Leg[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @return array
     */
    public function getWaypointOrder()
    {
        return $this->waypointOrder;
    }

    /**
     * @return array
     */
    public function getOverviewPolyline()
    {
        return $this->overviewPolyline;
    }

    /**
     * @return array
     */
    public function getBounds()
    {
        return $this->bounds;
    }

    /**
     * @return string
     */
    public function getCopyrights()
    {
        return $this->copyrights;
    }

    /**
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Returns the distance for all routes in meters
     *
     * @return int
     */
    public function getTotalDistance()
    {
        if ($this->totalDistance === null) {
            $this->totalDistance = 0;
            foreach ($this->getLegs() as $leg) {
                $this->totalDistance += $leg->getDistance()['value'];
            }
        }

        return $this->totalDistance;
    }
}
