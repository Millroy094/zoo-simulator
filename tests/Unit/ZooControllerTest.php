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

        // Animal health was less by a number between 0 - 20
        $this->assertLessThanOrEqual($firstAnimal['previous_health'], $firstAnimal['current_health']);

    }

    public function test_feed_zoo(): void {
        $response = $this->call('PUT','/api/feed' );
        $response->assertStatus( 200 );

        $animals = $response->decodeResponseJson()['animals'];

        $firstAnimal = $animals[0];
  
        // Animal health is greater than or equal previous health
        $this->assertGreaterThanOrEqual($firstAnimal['previous_health'], $firstAnimal['current_health']);
    
    }

    public function test_remove_all_animals_in_zoo(): void {
        $response = $this->call('DELETE','/api/destroy' );
        $response->assertStatus( 200 );

        $animals = $response->decodeResponseJson()['animals'];
        // All animals are deleted
        $this->assertCount(0, $animals);

    }
}
