<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Service;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Response;

/**
 * This service is exposed to provide a simple interface with the Google Maps API
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class Directions extends MapsService
{
    /**
     * Make a request against the Google Maps API for directions
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getDirections(Request $request)
    {
        $response = $this->client->getDirections($request);

        return $this->assembleDirectionsResponse($response);
    }

    /**
     * @param array $response
     *
     * @return Response
     */
    private function assembleDirectionsResponse($response)
    {
        $directionsResponse = new Response();

        return $directionsResponse::assembleFromArray($response);
    }
}
