<?php

namespace App\Http\Controllers;

use App\Ecosystem\Species\Animal;
use App\Ecosystem\Species\Monkey;
use App\Ecosystem\Species\Elephant;
use App\Ecosystem\Species\Giraffe;
use App\Models\Animal as AnimalModel;
use App\Ecosystem\ZooTimeManager;

class ZooController extends Controller {

    private $species;

    function __construct() {
        $this->species[Animal::TYPE_ELEPHANT] = new Elephant();
        $this->species[Animal::TYPE_MONKEY] = new Monkey();
        $this->species[Animal::TYPE_GIRAFFE] = new Giraffe();
    }

    public function destroyZoo() {
        
        $this->deleteAnimals();
        
        return response()->json( [ 'animals' => json_decode(AnimalModel::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ] , 200 );
    }

    public function createZoo() {
        
        ZooTimeManager::startZooLifeCycle();
        
        $this->deleteAnimals();

        $animalTypes = array( Animal::TYPE_ELEPHANT, Animal::TYPE_MONKEY, Animal::TYPE_GIRAFFE );
        $animalPopulationCount = array( 1, 2, 3, 4, 5 );

        foreach ( $animalTypes as $animalType ) {

            foreach ( $animalPopulationCount as $count ) {
                AnimalModel::create( [ 'name' => ucwords( $animalType ) . ' ' . $count, 'type' => $animalType, 'zoo_creation_time' =>  ZooTimeManager::getZooTime() ] );
            }

        }

        return response()->json( [ 'animals' => json_decode(AnimalModel::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 201  );
    }

    public function incrementHourAtZoo() {
        
        ZooTimeManager::incrementZooTime();
        
        foreach ( AnimalModel::all() as $animal ) {

            if (array_key_exists($animal->type, $this->species)) {
                $this->species[$animal->type]->age($animal);
            }

        }

        return response()->json( [ 'animals' => json_decode(AnimalModel::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 200  );

    }

    public function feedZoo() {
        
        foreach ( AnimalModel::all() as $animal ) {
            if (array_key_exists($animal->type, $this->species)) {
                $this->species[$animal->type]->feed($animal);
            }
        }

        return response()->json( [ 'animals' => json_decode(AnimalModel::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 200 );
    }

    private function deleteAnimals() {
        AnimalModel::truncate();
    }
}
