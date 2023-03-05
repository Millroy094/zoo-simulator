<?php 

namespace App\Ecosystem\Species;

use App\Ecosystem\Species\Animal;

class Elephant extends Animal {

    protected const LIFE_IN_BORDERLAND = 70;

    protected function calculateAnimalStatus( $animal ) : string {

        $status = self::ANIMAL_STATUS_ALIVE;

        if ( $animal->type === self::TYPE_ELEPHANT && $animal->previous_health < self::LIFE_IN_BORDERLAND && $animal->current_health < self::LIFE_IN_BORDERLAND ) {
            $status = self::ANIMAL_STATUS_DEAD;
        } else if ( $animal->type === self::TYPE_ELEPHANT && $animal->previous_health > self::LIFE_IN_BORDERLAND && $animal->current_health < self::LIFE_IN_BORDERLAND ) {
            $status = self::ANIMAL_STATUS_CANNOT_WALK;
        }

        return $status;
    } 

}