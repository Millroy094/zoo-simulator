<?php 

namespace App\Ecosystem\Species;

use App\Ecosystem\Species\Animal;

class Giraffe extends Animal {

    protected const LIFE_IN_BORDERLAND = 50;

    protected function calculateAnimalStatus( $animal ) : string {

        $status = self::ANIMAL_STATUS_ALIVE;

        if ( $animal->type === self::TYPE_GIRAFFE && $animal->current_health < self::LIFE_IN_BORDERLAND ) {
            $status = self::ANIMAL_STATUS_DEAD;
        }

        return $status;
    } 

}