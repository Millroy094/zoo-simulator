<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use App\Ecosystem\AnimalManager;
use App\Ecosystem\ZooTimeManager;

class ZooController extends Controller {

    private $animalManagement;

    public function __construct() {
        $this->animalManagement = new AnimalManager();
    }

    public function destroyZoo() {
        $this->animalManagement->deleteAnimals();
        return response()->json( [ 'animals' => json_decode(Animal::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ] , 200 );
    }

    public function createZoo() {
        ZooTimeManager::startZooLifeCycle();
        $this->animalManagement->createAnimals();
        return response()->json( [ 'animals' => json_decode(Animal::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 201  );
    }

    public function incrementHourAtZoo() {
        ZooTimeManager::incrementZooTime();
        $this->animalManagement->ageAnimals();
        return response()->json( [ 'animals' => json_decode(Animal::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 200  );

    }

    public function feedZoo() {
        $this->animalManagement->feedAnimals();
        return response()->json( [ 'animals' => json_decode(Animal::all()->toJson()), 'current_time' => ZooTimeManager::getZooTime() ], 200 );
    }
}
