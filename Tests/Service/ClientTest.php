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
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Response;
use Devtrw\Bundle\GoogleMapsBundle\Service\Directions;
use Guzzle\Tests\GuzzleTestCase;
use Guzzle\Service\Builder\ServiceBuilder;

/**
 * Tests for the Directions service
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class ClientTest extends GuzzleTestCase
{
    /**
     * @var Directions
     */
    private $directionsService;

    /**
     * @var GoogleMapsClient
     */
    private $googleMapsClient;

    /**
     * Sets up the directions service and mocks a response
     */
    public function setup()
    {
        $this->directionsService = ServiceExtensionTest::getDirectionsService();
        $this->googleMapsClient  = $this->directionsService->getClient();
    }

    /**
     * Test that the client properly throws a symfony 403 exception
     */
    public function test403()
    {
        $this->setMockResponse($this->googleMapsClient, __DIR__.'/../mock/403.http');
        $this->setExpectedException(
            'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException'
        );
        $this->directionsService->getDirections(new Request('', ''));
    }

    /**
     * Test that the client properly throws a symfony 404 exception
     */
    public function test404()
    {
        $this->setMockResponse($this->googleMapsClient, __DIR__.'/../mock/404.http');
        $this->setExpectedException(
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
            'The Google Maps API could not be reached.'
        );
        $this->directionsService->getDirections(new Request('', ''));
    }

    /**
     * Test that the client properly throws a symfony http exception
     */
    public function test500()
    {
        $this->setMockResponse($this->googleMapsClient, __DIR__.'/../mock/500.http');
        $this->setExpectedException(
            'Symfony\Component\HttpKernel\Exception\HttpException'
        );
        $this->directionsService->getDirections(new Request('', ''));
    }
}
