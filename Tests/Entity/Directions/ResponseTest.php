<?php

/*
 * Copyright (c) Steven Nance <steven@devtrw.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devtrw\Bundle\GoogleMapsBundle\Tests\Entity\Directions;

use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Request;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Response;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg;
use Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg\Step;
use Devtrw\Bundle\GoogleMapsBundle\Tests\Service\ServiceExtensionTest;
use Guzzle\Tests\GuzzleTestCase;

/**
 * Tests the Google API response entity
 *
 * @author Steven Nance <steven@devtrw.com>
 */
class ResponseTest extends GuzzleTestCase
{
    /**
     * Fetch a response from a mocked request so we can validate against it
     *
     * @return Response
     */
    private function getRequestResponse()
    {
        $this->setMockResponse(
            ServiceExtensionTest::getDirectionsService()->getClient(),
            __DIR__.'/../../mock/directions/ok.json.http'
        );

        return ServiceExtensionTest::getDirectionsService()
            ->getDirections(new Request('', ''));
    }
    /**
     * Tests that all values in the response entity are set/retrieved properly
     *
     * @return Route
     */
    public function testResponse()
    {
        $response = $this->getRequestResponse();

        //Ensure that the miles are properly summed
        $this->assertEquals(3426746, $response->getTotalDistance());
        $this->assertEquals(2129.28, $response->getTotalDistanceInMiles());

        $this->assertEquals('OK', $response->getStatus());
        $this->assertEquals(
            'The response contains a valid result.',
            $response->getStatusMessage()
        );

        $routes = $response->getRoutes();
        $this->assertEquals(1, count($routes));

        $route = $routes[0];
        $this->assertInstanceOf(
            'Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route',
            $route
        );

        return $route;
    }

    /**
     * Test the route entity
     *
     * @param Route $route
     *
     * @depends testResponse
     *
     * @return Leg
     */
    public function testRoute(Route $route)
    {
        $this->assertEquals(
            [
                'northeast' => ['lat' => 41.87811000000001, 'lng' => -87.62979000000001],
                'southwest' => ['lat' => 34.052360, 'lng' => -118.243560],
            ],
            $route->getBounds()
        );
        $this->assertEquals("Map data ©2013 Google", $route->getCopyrights());
        $this->assertEquals(
            [
                'points' => 'eir~FdezuOjoEzpC|lJbea@n}Lfub@deKhxVdoKbdExi`@d{D`uWxxErzQ`bPnsOleXnuQxhPz~KvfHxfGlbPlbZvj'
                    . 'H~pIx|Ch|V`lV||HjsVxPzfGr}IvRdmLjtC||JdtL|a_@`qd@`_CnbSplQpkDzcQx~NhtKbuKvlO`C`xZ~yHta|@_A|o\\tU'
                    . 'hcV`sOvrPtnBtfWvePrw`@lcOjqM|jh@nsDl~K`pAloUjyLdhi@f`Gprs@bqFtfQlgK~uIttNb{UbzVhdYpzTpqg@`sT|xdA'
                    . 'tiBhwPpoCv~TvqC`eGvdF`bJx{E`uG`bHjmi@~rC|qa@hpCzxP`fLbdJzuZvwa@~k_@hwNn{IrsQ`{Fz_[fbAfxc@`tIdvSh'
                    . 'p@rxd@|lHxaO~rKtwi@e`@`sn@v~Czfr@pgB`aLkuF_Bx}Gnu@f{D~kMlyZncq@n`WpmJxtJrzZzu^fqh@twe@ldx@ju\\ro'
                    . 'c@rjB|jSb|I|uYppBp`Mt`MneQvoWlfe@luIfvZzhQtbeAtgTby}AhgBvlQzuJbaEr~Jx\\hfAfyEth@fiM{|F|sm@ixDbyb'
                    . 'Aka@n{|AndEjdy@r_Lfup@jhBpi`@h}EraZh}OvtU~eFx`HpdDzqS|t@pzz@o@j{t@jcC|gsAjmC|yWkcDr{j@akApsy@pyB'
                    . 'deo@t`Ajpg@i~Fto{AqyE`dc@zjBlim@zcLz~p@diLns}@ioJbnWhQvqc@loIhqf@zhIzrnA~cVdyjAk_FjfoAigDvdoBsdC'
                    . 'nlvAebOvjg@hkHteW{`Ghs_@n{An~X`oGhb[xiEpjYt`Cnrs@obFxfRujDfaZikBnuXfm@vkNy{Un{_@q_V~dPitLfmQ{aJx'
                    . '}m@cbS`a_AdWb}SxnJtv`@btX|mYjxIdy[lhLbjV~d`@vl{Ad{Hl|WbtHruc@dxEd|Hup@piNwsIjwn@ujL`}n@inTfs`Bmp'
                    . 'Jrnu@jmD~tU}v@zcP}pGxcQ{cBbeZeM~ao@haFfp_@iOlgTclIz|t@spHjq[prD`lXhmOzlm@p|Dpzj@dd@bvv@wvG`op@nn'
                    . 'I|}Fh~NvfH`zh@l`D~bLtp@~`Kt{Pf_Cnxk@eiHvqRogNlcFeeHzmPxoCbnYzfI~{~@dvI`r}@vdGdgk@g_@z{R|Fxsb@e^p'
                    . 'hQi`@dfe@mxFhbOenQnnpBssBt{NnlBliH`aKncCzfVn}TrfH|r@nrQxqN`ef@jx^hfCpsBbuBkqBn~G_tA`wFstF`aO`uOp'
                    . 'lHzmHpiBz~Kay@dpWxp@xr_@cB`e\\d~Bxbd@'
            ],
            $route->getOverviewPolyline()
        );
        $this->assertEquals('I-40 W', $route->getSummary());
        $this->assertEmpty($route->getWarnings());
        $this->assertEquals([0, 1], $route->getWaypointOrder());

        $legs = $route->getLegs();
        $this->assertEquals(3, count($legs));

        $leg = $legs[0];
        $this->assertInstanceOf(
            'Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg',
            $leg
        );

        return $leg;
    }

    /**
     * Test the leg entity
     *
     * @param Leg $leg
     *
     * @depends testRoute
     *
     * @return Step
     */
    public function testLeg(Leg $leg)
    {
        $this->assertEquals(['text' => '583 mi', 'value' => 939028], $leg->getDistance());
        $this->assertEquals(
            ['text' => '8 hours 48 mins', 'value' => 31673],
            $leg->getDuration()
        );
        $this->assertEquals('Joplin, MO, USA', $leg->getEndAddress());
        $this->assertEquals(
            ['lat' => 37.084060, 'lng' => -94.51329000000001],
            $leg->getEndLocation()
        );
        $this->assertEquals('Chicago, IL, USA', $leg->getStartAddress());
        $this->assertEquals(
            ['lat' => 41.87811000000001, 'lng' => -87.62979000000001],
            $leg->getStartLocation()
        );

        $steps = $leg->getSteps();
        $this->assertEquals(12, count($steps));

        $step = $steps[0];
        $this->assertInstanceOf(
            'Devtrw\Bundle\GoogleMapsBundle\Entity\Directions\Route\Leg\Step',
            $step
        );

        return $step;
    }

    /**
     * Test the step entity
     *
     * @param Step $step
     *
     * @depends testLeg
     */
    public function testStep(Step $step)
    {
        $this->assertEquals(
            ['text' => '0.2 mi', 'value' => 269],
            $step->getDistance()
        );
        $this->assertEquals(
            ['text' => '1 min', 'value' => 38],
            $step->getDuration()
        );
        $this->assertEquals(
            ['lat' => 41.87570, 'lng' => -87.62969000000001],
            $step->getEndLocation()
        );
        $this->assertEquals(['points' => 'eir~FdezuOpFInFI'], $step->getPolyline());
        $expectedHTML = <<<HTML
Head <b>south</b> on <b>S Federal St</b> toward <b>W Van Buren St</b>
HTML;
        $this->assertEquals($expectedHTML, $step->getHtmlInstructions());
        $this->assertEquals(
            ['lat' => 41.87811000000001, 'lng' => -87.62979000000001],
            $step->getStartLocation()
        );
    }

    /**
     * Ensures only valid status are accepted
     */
    public function testValidStatusCode()
    {
        $this->setExpectedException('\InvalidArgumentException');
        Response::assembleFromArray(['status' => 'an-invalid-status-code']);
    }
}
