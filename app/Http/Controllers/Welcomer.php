<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class Welcomer extends Controller
{
    // Main Welcome Index Pages
    public function educators(){
        return view('welcome')->with('path', 'educators');
    }
    public function organizations(){
        return view('organizations')->with('path', 'organizations');
    }
    public function parents(){
        return view('parents')->with('path', 'parents');
    }
    public function youth(){
        return view('youth')->with('path', 'youth');
    }
    public function notfound(){
        return view('errors.404');
    }

    public function testQueries(){

        $users = DB::table('users')
           ->whereIn('id', function ($query) {
               $query->select(DB::raw('user_id'))
                     ->from('user_permissions')
                     ->whereRaw('user_permissions.permission_id = 1'); //change 1 to whatever ID you have in the permissions table
           })
           ->get();

        var_dump($users);
    }

    //Active campaign testing is in \ActiveCampaignController
    //Authentication is in \User\AuthLogic
    //Users-Master Admin is in \User\MasterController
    //Users-Schools is in \User\SchoolController
    //Dashboard is in \HomeController
    //Backend is in \BackendController

    //// ABOUT ////
        //About
        public function about($path = 'educators'){
            return view('about.about')->with('path', get_path($path));
        }
        //Mission & Values
        public function values($path = 'educators'){
            return view('about.values')->with('path', get_path($path));
        }
        public function values_test($path = 'educators'){
            return view('about.values-test')->with('path', get_path($path));
        }
        //Team in About/TeamController
        //Apply in About/ApplyController
        //Social Media
        public function social($path = 'educators'){
            return view('about.social')->with('path', get_path($path));
        }
        //Standards in About/StandardsController
        //Terms of Use and other fun stuff
        public function impressum($path = 'educators'){
            return view('legal.impressum')->with('path', get_path($path));
        }
        public function privacy($path = 'educators'){
            return view('legal.privacy')->with('path', get_path($path));
        }
        public function terms($path = 'educators'){
            return view('legal.terms')->with('path', get_path($path));
        }
        public function purchases($path = 'educators'){
            return view('legal.purchases')->with('path', get_path($path));
        }
    //// SERVICES ////
        //Educator Services
        public function services($path = 'educators'){
            return view('services.services')->with('path', get_path($path));
        }
        //Org Services
            //Consulting
            public function consulting($path = 'organizations'){
                return view('org.consulting')->with('path', get_path($path));
            }
        //Curricula
        public function subscription_info($path = 'educators'){
            return view('services.subscription-info.subscription-info')->with('path', get_path($path));
        }
        //Frontend Services Trainings in \Backend\TrainingController
        //EESL in \Trainings\TseoController
        //FREE SESSIONS in \Trainings\BonusController
        //HCWP in \Trainings\RegisterController
        //HMHP in \Trainings\HmhpController
        //HS in \Trainings\HsController
        //MS in \Trainings\MsController
        //ES in \Trainings\EsController
        //SE&L in \Trainings\SealController
        //TSEO in \Trainings\TseoController
        //Parent Services in \Parents\ParentsController
        //Youth Services in \Youth\YouthController
        //Org Services in \Org\ServicesController

    //// FREE CONTENT ////
        //Free
        public function free($path){
            return view('free-content.free-content')->with('path', get_path($path));
        }
            //Arcade in \Arcade\ArcadeController
            //Dictionaries \References\in DictionaryController
            //Activities
            public function activities($path){
                return view('free-content.activities.free-activities')->with('path', get_path($path));
            }
                public function condoms($path){
                    return view('free-content.activities.condoms-for-facts-sake')->with('path', get_path($path));
                }
                public function explain_abortion($path){
                    return view('free-content.activities.explaining-abortion')->with('path', get_path($path));
                }
                public function explain_coronavirus($path){
                    return view('free-content.activities.explaining-coronavirus')->with('path', get_path($path));
                }
                public function menstruation($path){
                    return view('free-content.activities.periods')->with('path', get_path($path));
                }

    //// STORE in \Store\StorefrontController

    //// GIVE & GET INVOLVED ////
        //Give
        public function give($path = 'educators'){
            return view('involved.give')->with('path', get_path($path));
        }
            //Blog
                //still in routes

                //Blog in progress

    //Contact in ContactUsFormController
    //News
    public function news($path = 'educators'){
        return view('emails.news')->with('path', get_path($path));
    }
    public function subscribed($path = 'educators'){
        return view('emails.subscribed')->with('path', get_path($path));
    }
    public function unsubscribe($path = 'educators'){
        return view('emails.unsubscribe')->with('path', get_path($path));
    }
    public function unsubscribed($path = 'educators'){
        return view('emails.unsubscribed')->with('path', get_path($path));
    }
    //Other sites
    public function other($path = 'educators'){
        return view('more.other')->with('path', get_path($path));
    }
    //Videos
    public function brushing_time($path = 'educators'){
        return view('backend.videos.its-brushing-time')->with('path', get_path($path));
    }
    public function betsy($path = 'educators'){
        return view('backend.videos.betsy-goes-to-the-doctor')->with('path', get_path($path));
    }
    public function betsy2($path = 'educators'){
        return view('backend.videos.betsy-goes-to-the-doctor-w-commentary')->with('path', get_path($path));
    }

    //Testing
    public function template($path = 'educators'){
        return view('store.template')->with('path', get_path($path));
    }
    public function testLayout($path = 'educators'){
        return view('layout')->with('path', get_path($path));
    }

}
