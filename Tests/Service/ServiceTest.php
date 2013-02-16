<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Tests\Service;

use Devtrw\Bundle\GoogleMapsBundle\Client\GoogleMapsClient;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Devtrw\Bundle\GoogleMapsBundle\Service\MapsService;
use Guzzle\Common\Event;
use Guzzle\Http\Url;
use Guzzle\Service\Client;
use Guzzle\Tests\GuzzleTestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A base class for testing services that interact with guzzle clients
 *
 * @author Steven Nance <steven@devtrw.com>
 */
abstract class ServiceTest extends GuzzleTestCase implements EventSubscriberInterface
{
    /**
     * Storage for the client
     *
     * @var Client
     */
    protected $client;

    /**
     * The the request to validate the next call against, if set the call will
     * be validated against it
     *
     * @var Request|null
     */
    private $expectedRequest;

    /**
     * Adds the test case as an event subscriber
     */
    public function setup()
    {
        $this->getClient()->addSubscriber($this);
    }

    /**
     * Ensures the setup method can access the client
     *
     * @return MapsService
     */
    abstract protected function getService();

    /**
     * @return GoogleMapsClient
     */
    protected function getClient()
    {
        return $this->getService()->getClient();
    }

    /**
     * Subscribe to requests so we can validate that they are formed properly
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'request.before_send' => 'validateRequest'
        ];
    }

    /**
     * Validates the request event against the values stored
     * in self::expectedRequest. See self::validateNextRequest()
     *
     * @param Event $event
     */
    public function validateRequest(Event $event)
    {
        if (null !== $expected = $this->expectedRequest) {
            $this->expectedRequest = null;

            $url = new Url(GoogleMapsClient::DEFAULT_SCHEME, GoogleMapsClient::DEFAULT_ENDPOINT);
            $url->setPath('/maps/api/directions/' . GoogleMapsClient::DEFAULT_FORMAT);
            $query = [
                'origin'      => $expected->getOrigin(),
                'destination' => $expected->getDestination(),
                'sensor'      => $expected->hasSensor() ? 'true' : 'false',
                'mode'        => $expected->getMode()
            ];
            $waypointsString = $expected->getWaypointsString();
            if (!empty($waypointsString)) {
                $query['waypoints'] = $waypointsString;
            }
            $url->setQuery($query);

            $this->assertEquals((string) $url, $event['request']->getUrl());
        }
    }

    /**
     * Sets the next request to be validated against the supplied url
     *
     * @param Request $request
     */
    protected function validateNextRequest(Request $request)
    {
        $this->expectedRequest = $request;
    }
}
