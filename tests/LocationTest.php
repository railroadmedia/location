<?php

use Railroad\Location\Services\LocationService;
use Railroad\Location\Tests\LocationTestCase;

class LocationTest extends LocationTestCase
{
    /**
     * @var LocationService
     */
    private $classBeingTested;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classBeingTested = $this->app->make(LocationService::class);
    }

    public function test_country()
    {
        $country = $this->classBeingTested->getCity();

        $this->assertEquals('Abbotsford', $country);
    }

    public function test_region()
    {
        $region = $this->classBeingTested->getRegion();

        $this->assertEquals('British Columbia', $region);
    }

    public function test_latitude()
    {
        $latitude = $this->classBeingTested->getLatitude();

        $this->assertEquals(49.0384, $latitude);
    }

    public function test_longitude()
    {
        $longitude = $this->classBeingTested->getLongitude();

        $this->assertEquals(-122.3485, $longitude);
    }
}
