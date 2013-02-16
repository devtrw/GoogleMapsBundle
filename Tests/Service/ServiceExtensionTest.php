<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Tests\Service;

use Devtrw\Bundle\GoogleMapsBundle\Tests\app\TestKernel;

use PHPUnit_Framework_TestCase as TestCase;
use Devtrw\Bundle\GoogleMapsBundle\Service\Directions;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Tests for the Directions service
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class ServiceExtensionTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    protected static $diContainer;

    /**
     * @var Directions
     */
    protected static $directionsService;

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        if (null === static::$diContainer) {
            $kernel = new TestKernel('test', true);
            $kernel->boot();
            static::$diContainer = $kernel->getContainer();
        }

        return static::$diContainer;
    }

    /**
     * @return Directions
     */
    public static function getDirectionsService()
    {
        if (null === static::$directionsService) {
            static::$directionsService = static::getContainer()->get('devtrw_google_maps.directions');
        }

        return static::$directionsService;
    }

    /**
     * Tests to make sure the service container is properly instantiated
     */
    public function testServiceCreation()
    {
        $this->assertInstanceOf(
            'Symfony\Component\DependencyInjection\ContainerInterface',
            static::getContainer()
        );

        $directions = static::getDirectionsService();
        $this->assertInstanceOf(
            'Devtrw\Bundle\GoogleMapsBundle\Service\Directions',
            $directions
        );
    }
}
