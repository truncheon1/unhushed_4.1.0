<?php

namespace App\Http\Controllers\Arcade;
use App\Http\Controllers\Controller;

class ArcadeController extends Controller
{
    //Arcade
    public function arcade($path = 'educators'){
        return view('free-content.arcade')->with('path', get_path($path));
    }
        //Friendzone
        public function friendzone($path = 'educators'){
            return view('free-content.arcade.friendzone')->with('path', get_path($path));
        }
        public function friendzone_play($path = 'educators'){
            return view('free-content.arcade.friendzone-play')->with('path', get_path($path));
        }
        //Living Things
        public function living_things($path = 'educators'){
            return view('free-content.arcade.living-things')->with('path', get_path($path));
        }
        public function living_things_play($path = 'educators'){
            return view('free-content.arcade.living-things-play')->with('path', get_path($path));
        }
        //Madlibs Game in \Arcade\MadlibsController
        //Ringerangaroo
        public function ringerangaroo($path = 'educators'){
            return view('free-content.arcade.ringerangaroo')->with('path', get_path($path));
        }
        public function ringerangaroo_play($path = 'educators'){
            return view('free-content.arcade.ringerangaroo-play')->with('path', get_path($path));
        }
        //Space Sharks in \Arcade\SpaceSharksController
        //Lifespan Game
        public function lifespan($path = 'educators'){
            return view('free-content.arcade.lifespan')->with('path', get_path($path));
        }
        public function lifespan_play($path = 'educators'){
            return view('free-content.arcade.lifespan-play')->with('path', get_path($path));
        }
}
