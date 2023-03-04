<?php

namespace App\Ecosystem;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ZooTimeManager {
    const KEY = 'time_at_zoo';

    public static function getZooTime(): Carbon {
        if ( !Cache::has( self::KEY ) ) {
            self::startZooLifeCycle();
        }

        return Cache::get( self::KEY );
    }

    public static function startZooLifeCycle() {
        $zooTime = Carbon::now();
        Cache::forever( self::KEY, $zooTime );
    }

    public static function incrementZooTime( $hours = 1 ) {
        $zooTime = self::getZooTime();
        Cache::forever( self::KEY, $zooTime->addHours( $hours ) );
    }

}