<?php

namespace App\Ecosystem;

use App\Ecosystem\ZooTimeManager;
use App\Models\Animal;

Class AnimalManager {

    private const TYPE_ELEPHANT = 'elepant';
    private const TYPE_MONKEY = 'monkey';
    private const TYPE_GIRAFFE = 'giraffe';

    private const ANIMAL_STATUS_ALIVE = 'Alive';
    private const ANIMAL_STATUS_CANNOT_WALK = "Can't Walk";
    private const ANIMAL_STATUS_DEAD = 'Dead';

    const HEALTH_MAX_CAP = 100;
    const HEALTH_MIN_CAP = 0;

    const MAX_HEALTH_REDUCTION = 20;
    const MIN_HEALTH_REDUCTION = 0;

    const MAX_HEALTH_INCEMENT = 25;
    const MIN_HEALTH_INCEMENT = 10;

    public function createAnimals() {

        $this->deleteAnimals();

        $animalTypes = array( self::TYPE_ELEPHANT, self::TYPE_MONKEY, self::TYPE_GIRAFFE );
        $animalPopulationCount = array( 1, 2, 3, 4, 5 );

        foreach ( $animalTypes as $animalType ) {

            foreach ( $animalPopulationCount as $count ) {
                Animal::create( [ 'name' => ucwords( $animalType ) . ' ' . $count, 'type' => $animalType, 'zoo_creation_time' =>  ZooTimeManager::getZooTime() ] );
            }

        }

    }

    public function deleteAnimals() {
        Animal::truncate();
    }

    public function feedAnimals() {

        $heathIncrementByAnimalType = array();

        foreach ( Animal::all() as $animal ) {

            if ( !in_array( $animal->type, $heathIncrementByAnimalType ) ) {
                $heathIncrementByAnimalType[ $animal->type ] = rand( self::MIN_HEALTH_INCEMENT, self::MAX_HEALTH_INCEMENT );
            }

            if ( !$this->hasAnimalDeceased( $animal ) ) {

                $animal->previous_health = $animal->current_health;
                $animal->current_health += $heathIncrementByAnimalType[ $animal->type ];

                if ($animal->current_health > self::HEALTH_MAX_CAP) {
                    $animal->current_health = self::HEALTH_MAX_CAP;
                }

                $animal->save();
            }
        }
    }

    public function ageAnimals() {
        
        foreach ( Animal::all() as $animal ) {

            if ( !$this->hasAnimalDeceased( $animal ) ) {

                $decrement = rand( self::MIN_HEALTH_REDUCTION, self::MAX_HEALTH_REDUCTION );

                $animal->previous_health = $animal->current_health;
                $animal->current_health -= $decrement;

                if ($animal->current_health < self::HEALTH_MIN_CAP) {
                    $animal->current_health = self::HEALTH_MIN_CAP;
                }

                $animal->status = $this->calculateAnimalStatus( $animal );
                $animal->life_span = ZooTimeManager::getZooTime()->diffInHours( $animal->zoo_creation_time );
                $animal->save();

            }

        }
    }

    private function calculateAnimalStatus( $animal ) : string {

        $status = self::ANIMAL_STATUS_ALIVE;

        if ( $animal->type === self::TYPE_ELEPHANT && $animal->previous_health < 70 && $animal->current_health < 70 ) {
            $status = self::ANIMAL_STATUS_DEAD;
        } else if ( $animal->type === self::TYPE_ELEPHANT && $animal->previous_health > 70 && $animal->current_health < 70 ) {
            $status = self::ANIMAL_STATUS_CANNOT_WALK;
        } else if ( $animal->type === self::TYPE_MONKEY && $animal->current_health < 30 ) {
            $status = self::ANIMAL_STATUS_DEAD;
        } else if ( $animal->type === self::TYPE_GIRAFFE && $animal->current_health < 50 ) {
            $status = self::ANIMAL_STATUS_DEAD;
        }

        return $status;
    }

    private function hasAnimalDeceased( $animal ) : bool {
        return $animal->status === self::ANIMAL_STATUS_DEAD;
    }
}