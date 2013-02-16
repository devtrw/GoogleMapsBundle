<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Tests\Service;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Response;
use Devtrw\Bundle\GoogleMapsBundle\Service\Directions;
use \Devtrw\Bundle\GoogleMapsBundle\Client\GoogleMapsClient AS Client;
use Guzzle\Http\Url;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Tests for the Directions service
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class DirectionsTest extends ServiceTest
{
    /**
     * Fetches the service
     *
     * @return Directions
     */
    protected function getService()
    {
        return ServiceExtensionTest::getDirectionsService();
    }

    /**
     * Validates that the request response is being properly built
     *
     * @return Response
     */
    public function testGetDirections()
    {
        $this->setMockResponse(
            $this->getClient(),
            __DIR__.'/../mock/directions/ok.json.http'
        );

        $response = $this->getService()->getDirections(new Request('', ''));

        $this->assertInstanceOf(
            'Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Response',
            $response
        );
    }

    /**
     * Ensure that the request is properly formatted based on the passed in params
     */
    public function testRequestFormat()
    {
        $okMock = __DIR__.'/../mock/directions/ok.json.http';
        $request = new Request(
            '1234 Some Example Ave. Portland, OR 97213',
            '5678 Another Example Rd, New York NY'
        );

        //Defaults are applied properly
        $this->validateNextRequest($request);
        $this->setMockResponse($this->getClient(), $okMock);
        $this->getService()->getDirections($request);

        //Sensor, mode and waypoints are properly set
        $request->setHasSensor();
        $request->setMode(Request::MODE_BICYCLING);
        $request->setWaypoints(['Some Address Waypoint', 'Another Waypoint']);
        $this->validateNextRequest($request);
        $this->setMockResponse($this->getClient(), $okMock);
        $this->getService()->getDirections($request);

        //Ensure waypoint optimization is properly set
        $request->optimizeWaypoints();
        $this->validateNextRequest($request);
        $this->setMockResponse($this->getClient(), $okMock);
        $this->getService()->getDirections($request);
    }

    /**
     * Tests the error responses
     *
     * @param string $status
     * @param string $mockedResponse
     * @param string $statusMessage
     *
     * @dataProvider errorProvider
     */
    public function testErrors($status, $mockedResponse, $statusMessage)
    {
        $this->setMockResponse($this->getClient(), $mockedResponse);
        $response = $this->getService()->getDirections(new Request('', ''));

        $this->assertEquals($status, $response->getStatus());
        $this->assertEquals($statusMessage, $response->getStatusMessage());
    }

    /**
     * Provides data for the testErrors method
     *
     * @return array
     */
    public function errorProvider()
    {
        return [
            [
                'NOT_FOUND',
                __DIR__.'/../mock/directions/not-found.json.http',
                'At least one of the locations specified in the requests\'s origin, destination, or '
                    . 'waypoints could not be geocoded.'
            ],
            [
                'ZERO_RESULTS',
                __DIR__.'/../mock/directions/zero-results.json.http',
                'No route could be found between the origin and destination.'
            ],
            [
                'MAX_WAYPOINTS_EXCEEDED',
                __DIR__.'/../mock/directions/max-waypoints-exceeded.json.http',
                'Too many waypoints were provided in the request The maximum allowed waypoints'
                    . ' is 8, plus the origin, and destination. ( Google Maps API for Business customers may contain '
                    . 'requests with up to 23 waypoints.)'
            ],
            [
                'INVALID_REQUEST',
                __DIR__.'/../mock/directions/invalid-request.json.http',
                'The provided request was invalid. Common causes of this status include an invalid '
                    . 'parameter or parameter value.'
            ],
            [
                'OVER_QUERY_LIMIT',
                __DIR__.'/../mock/directions/over-query-limit.json.http',
                'The service has received too many requests from your application within the allowed '
                    . 'time period.'
            ],
            [
                'REQUEST_DENIED',
                __DIR__.'/../mock/directions/request-denied.json.http',
                'The service denied use of the directions service by your application.'
            ],
            [
                'UNKNOWN_ERROR',
                __DIR__.'/../mock/directions/unknown-error.json.http',
                'The directions request could not be processed due to a server error. The request may '
                    . 'succeed if you try again.'
            ]
        ];
    }
}
