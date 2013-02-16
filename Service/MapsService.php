<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Service;

use Devtrw\Bundle\GoogleMapsBundle\Client\GoogleMapsClient;

/**
 * A base class for services that interface with the Google Maps API
 *
 * @author Steven Nance <steven@devtrw.com>
 */
abstract class MapsService
{
    /**
     * Contains the client for interacting with google maps
     *
     * @var GoogleMapsClient
     */
    protected $client;

    /**
     * @param GoogleMapsClient $googleMapsClient
     */
    public function __construct(GoogleMapsClient $googleMapsClient)
    {
        $this->client = $googleMapsClient;
    }

    /**
     * Provides direct access to the API client
     *
     * @return GoogleMapsClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
