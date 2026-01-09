<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
class FixLoginDate extends Command
{
    /* The name and signature of the console command. @var string */
    protected $signature = 'ac:fix';

    /* The console command description. @var string */
    protected $description = 'Tried to fix last login invalid date';

    /* Create a new command instance.  @return void */
    public function __construct(){
        parent::__construct();
    }

    /* Execute the console command. @return int */
    public function handle(){
        //Daily Update for Active Campaign Users
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));
        //query `users` table
        $users = User::whereNull('last_login')->get();
        foreach($users as $user){
            //search user
            dump($user->email);
            //search contact in AC database
            $result = $this->call_api('/api/3/contacts?email='.$user->email);
            if(is_array($result) && isset($result['contacts'])){
                $id = $result['contacts'][0]['id'];
                //found contact, update last login
                $payload = [
                    'contact'=>[
                        'fieldValues'=>[
                            [
                                'field'=> 10,
                                'value'=> ''
                            ],
                        ]
                    ]
                ];
                $update = $this->call_api('/api/3/contacts/'.$id, 'PUT', $payload);
            }
        }
    }

    public function call_api($_endpoint, $method = "GET", $payload = []){
        $_curl = \Config::get('services.activecampaign.url');
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $_curl.$_endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Api-Token: ".\Config::get('services.activecampaign.key')
          ],
        ]);

        if(count($payload)){
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return (json_decode($response, true));
        }
    }

    // cd /home/u638-lto3fgvnhiat/www/unhushed.org && php artisan ac:fix >> /dev/null 2>&1
}
