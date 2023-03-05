<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use App\Ecosystem\Species\Animal;

class ZooControllerTest extends TestCase {
    /**
    * A basic unit test example.
    */

    public function test_create_fifteen_animals_in_zoo(): void {
        $response = $this->call('POST','/api/create' );
        $response->assertStatus( 201 );


        $animals = $response->decodeResponseJson()['animals'];

        // Fifteen animals are created
        $this->assertCount(15, $animals);

    }

    public function test_increment_hour_in_zoo(): void {
        $response = $this->call('PUT','/api/increment-hour' );
        $response->assertStatus( 200 );

        $currentZooTime = $response->decodeResponseJson()['current_time'];
        $animals = $response->decodeResponseJson()['animals'];

        $firstAnimal = $animals[0];
        $zooCreationTime = $firstAnimal['zoo_creation_time'];

        $totalDurationInSeconds = Carbon::parse($currentZooTime)->diffInSeconds($zooCreationTime);
        $totalDurationInHours = (int) gmdate('H', $totalDurationInSeconds);

        // Zoo time is incremented by an hour
        $this->assertEquals(1, $totalDurationInHours);

        // The Animal has lived for an hour
        $this->assertEquals(1, $firstAnimal['life_span']);

        $healthDifference = $firstAnimal['previous_health'] - $firstAnimal['current_health'];

        if ($healthDifference !== 0 ) {
            // Animal health was decremented by a number between 0 - 20
            $this->assertGreaterThanOrEqual(Animal::MIN_HEALTH_REDUCTION, $healthDifference);
            $this->assertLessThanOrEqual(Animal::MAX_HEALTH_REDUCTION, $healthDifference);
        }

    }

    public function test_feed_zoo(): void {
        $response = $this->call('PUT','/api/feed' );
        $response->assertStatus( 200 );

        $zooCreationTime = $response->decodeResponseJson()['current_time'];
        $animals = $response->decodeResponseJson()['animals'];

        $firstAnimal = $animals[0];
        $healthDifference =  $firstAnimal['current_health'] - $firstAnimal['previous_health'];

        if ($healthDifference !== 0 ) {
             // Animal health was incremented by a number between 10 - 25
            $this->assertGreaterThanOrEqual(Animal::MIN_HEALTH_INCEMENT, $healthDifference);
            $this->assertLessThanOrEqual(Animal::MAX_HEALTH_INCEMENT, $healthDifference);
        }
    
    }

    public function test_remove_all_animals_in_zoo(): void {
        $response = $this->call('DELETE','/api/destroy' );
        $response->assertStatus( 200 );

        $animals = $response->decodeResponseJson()['animals'];
        // All animals are deleted
        $this->assertCount(0, $animals);

    }
}
