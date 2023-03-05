<?php 

namespace App\Ecosystem\Species;

use App\Ecosystem\ZooTimeManager;

Class Animal {
    
    public const TYPE_ELEPHANT = 'elephant';
    public const TYPE_MONKEY = 'monkey';
    public const TYPE_GIRAFFE = 'giraffe';

    protected const ANIMAL_STATUS_ALIVE = 'Alive';
    protected const ANIMAL_STATUS_CANNOT_WALK = "Can't Walk";
    protected const ANIMAL_STATUS_DEAD = 'Dead';

    public const HEALTH_MAX_CAP = 100;
    public const HEALTH_MIN_CAP = 0;

    public const MAX_HEALTH_REDUCTION = 20;
    public const MIN_HEALTH_REDUCTION = 0;

    public const MAX_HEALTH_INCEMENT = 25;
    public const MIN_HEALTH_INCEMENT = 10;

    protected const LIFE_IN_BORDERLAND = 1;

    private $increment;  

    public function feed($animal) : void {
        
        if (!isset($this->increment)) {
            $this->increment = rand( self::MIN_HEALTH_INCEMENT, self::MAX_HEALTH_INCEMENT );
        }
        
        if ( !$this->hasAnimalDeceased( $animal ) ) {

            $animal->previous_health = $animal->current_health;
            $animal->current_health += $this->increment;

            if ($animal->current_health > self::HEALTH_MAX_CAP) {
                $animal->current_health = self::HEALTH_MAX_CAP;
            }

            $animal->save();
        }

    }

    public function age($animal) : void {
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


    protected function calculateAnimalStatus( $animal ) : string {

        $status = self::ANIMAL_STATUS_ALIVE;

        if ( $animal->current_health < self::LIFE_IN_BORDERLAND ) {
            $status = self::ANIMAL_STATUS_DEAD;
        }

        return $status;
    }

    private function hasAnimalDeceased( $animal ) : bool {
        return $animal->status === self::ANIMAL_STATUS_DEAD;
    }
}