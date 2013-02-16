<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Tests\Entity\Directions;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test the request entity
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class RequestTest extends TestCase
{
    /**
     * Make sure mode is proper
     *
     * @expectedException InvalidArgumentException
     */
    public function testUnsupportedMode()
    {
        $request = new Request('', '');
        $request->setMode('invalid-mode');
    }


    /**
     * Transit isn't currently supported, make sure the user knows
     */
    public function testTransitMode()
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            'The transit mode is not yet supported.'
        );
        $request = new Request('', '');
        $request->setMode(Request::MODE_TRANSIT);
    }

    /**
     * Ensure the waypoints functions function properly
     */
    public function testWaypoints()
    {
        $waypoints = ['some','waypoints','to','use'];
        $request = new Request('', '');

        $request->optimizeWaypoints();
        $this->assertEmpty($request->getWaypointsString());
        $request->optimizeWaypoints(false);

        $request->setWaypoints($waypoints);
        $this->assertEquals($waypoints, $request->getWaypoints());

        $request->addWaypoint('added');
        $waypoints[] = 'added';
        $this->assertEquals($waypoints, $request->getWaypoints());

        $waypointString = implode($waypoints, '|');
        $this->assertEquals($waypointString, $request->getWaypointsString());

        $request->optimizeWaypoints();
        $this->assertEquals('optimize:true|'.$waypointString, $request->getWaypointsString());

        $request->clearWaypoints();
        $this->assertEmpty($request->getWaypoints());
    }
}
