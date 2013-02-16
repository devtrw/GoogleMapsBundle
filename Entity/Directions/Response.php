<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Entity\Directions;

use BadMethodCallException;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route;
use InvalidArgumentException;

/**
 * A response object that maps the reply from the Google Maps Directions API
 *
 * @author Steven Nance <steven@devtrw.com>
 *
 * @url https://developers.google.com/maps/documentation/directions/#DirectionsResponses
 */
class Response
{
    /**
     * The number of meters per mile
     *
     * @var float
     */
    const METERS_PER_MILE = 1609.344;

    /**
     * The api response status
     *
     * @var string
     */
    private $status;

    /**
     * An array of status codes and their descriptions
     *
     * @var array
     */
    private $statusCodes;

    /**
     * An array of routes, see url below for more information
     *
     * @url https://developers.google.com/maps/documentation/directions/#Routes
     *
     * @var Route[]
     */
    private $routes;

    /**
     * Stores the total distance in meters to avoid having to calculate it again
     *
     * @var int
     */
    private $totalDistance;

    /**
     * Sets entity defaults and error code mapping
     */
    public function __construct()
    {
        $this->statusCodes = [
            'OK' => 'The response contains a valid result.',
            'NOT_FOUND' => 'At least one of the locations specified in the requests\'s origin, destination, or '
                . 'waypoints could not be geocoded.',
            'ZERO_RESULTS' =>  'No route could be found between the origin and destination.',
            'MAX_WAYPOINTS_EXCEEDED' => 'Too many waypoints were provided in the request The maximum allowed waypoints'
                . ' is 8, plus the origin, and destination. ( Google Maps API for Business customers may contain '
                . 'requests with up to 23 waypoints.)',
            'INVALID_REQUEST' => 'The provided request was invalid. Common causes of this status include an invalid '
                . 'parameter or parameter value.',
            'OVER_QUERY_LIMIT' => 'The service has received too many requests from your application within the allowed '
                . 'time period.',
            'REQUEST_DENIED' => 'The service denied use of the directions service by your application.',
            'UNKNOWN_ERROR' => 'The directions request could not be processed due to a server error. The request may '
                . 'succeed if you try again.'
        ];
        $this->routes = [];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Assembles a response from an array of responses
     *
     * @param array $responseArray
     *
     * @return Response
     *
     * @throws InvalidArgumentException
     */
    public static function assembleFromArray($responseArray)
    {
        $response = new Response();

        if (!array_key_exists($responseArray['status'], $response->statusCodes)) {
            throw new InvalidArgumentException(
                "The status code \"${responseArray['status']}\" is not a valid"
                . " google api response."
            );
        }
        $response->status = $responseArray['status'];

        foreach ($responseArray['routes'] as $route) {
            $response->routes[] = Route::assembleFromArray($route);
        }

        return $response;
    }

    /**
     * Returns the message associated with the status code
     *
     * @return string
     *
     * @throws BadMethodCallException
     */
    public function getStatusMessage()
    {
        return $this->statusCodes[$this->status];
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
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
            foreach ($this->getRoutes() as $route) {
                $this->totalDistance += $route->getTotalDistance();
            }
        }

        return $this->totalDistance;
    }


    /**
     * The total distance in miles, to the second decimal
     *
     * @return float
     */
    public function getTotalDistanceInMiles()
    {
        return number_format($this->getTotalDistance() / self::METERS_PER_MILE, 2, '.', '');
    }
}
