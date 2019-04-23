<?php

use App\Rules\City;
use App\Rules\Latitude;
use App\Rules\Longitude;

class RulesTest extends TestCase {

    public function testCity() {
        $rule = new City();

        $this->assertSame(false, $rule->passes('', 123));
        $this->assertSame(false, $rule->passes('', 'perm'));
        $this->assertSame(true, $rule->passes('', 'Perm'));
        $this->assertSame(true, $rule->passes('', 'New York'));
    }

    public function testLatitude() {
        $rule = new Latitude();

        $this->assertSame(false, $rule->passes('', 123));
        $this->assertSame(false, $rule->passes('', 'perm'));
        $this->assertSame(false, $rule->passes('', 180.2134125));
        $this->assertSame(true, $rule->passes('', 88.11234));
        $this->assertSame(false, $rule->passes('', 88.112));
        $this->assertSame(false, $rule->passes('', 90.1121));
        $this->assertSame(true, $rule->passes('', -88.1123));
        $this->assertSame(false, $rule->passes('', -90.1125));
    }

    public function testLongitude() {
        $rule = new Longitude();

        $this->assertSame(false, $rule->passes('', 123));
        $this->assertSame(false, $rule->passes('', 'perm'));
        $this->assertSame(false, $rule->passes('', 180.2134125));
        $this->assertSame(true, $rule->passes('', 179.11234));
        $this->assertSame(false, $rule->passes('', 88.112));
        $this->assertSame(true, $rule->passes('', 179.1123));
        $this->assertSame(true, $rule->passes('', -179.1123));
        $this->assertSame(false, $rule->passes('', -180.1123));
    }

}
