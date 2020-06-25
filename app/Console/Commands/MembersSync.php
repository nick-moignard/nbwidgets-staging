<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Libraries\Factory\AbstractFactory;
use App\Models\Renovate;
//use App\Models\Log;
use App\Models\People;
use App\Models\NationDetails;
use App\Models\Nation;
use Log;
use Exception;

class MembersSync extends Command
{

    private $factory;
    private $api;
    private $dao;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync members to nation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', 1000);

        parent::__construct();

        $this->factory = AbstractFactory::getFactory('Api');
        $this->factoryDao = AbstractFactory::getFactory('DAO');
        $this->api = $this->factory->getDAO('NationApiConexion');
        $this->dao = AbstractFactory::getFactory('DAO')->getDAO('NationDao');
        $this->logDao = $this->factoryDao->getDAO('LogDao');

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = date('Y-m-d H:i:s');
        $renovates = Renovate::where('execute','=',0)->get();
        $nation = NationDetails::pluck('membership_sync','nation_id')->all();

        foreach($renovates as $renovate){
            $membership_sync = $nation[$renovate->nation_id];
            $this->getPersonSync($renovate->url,$renovate->nation_id,$membership_sync,$renovate->slug,$renovate->id);
        }
    }

    public function getPersonSync($url,$nation_id,$membership,$slug,$id){
        $renovate = Renovate::find($id);
        $nation = Nation::find($nation_id);

        $count = 0;

        $cookiesIn = '';
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,     // return web page
            // CURLOPT_HEADER         => true,     //return headers in  addition to content
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            // CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_SSL_VERIFYPEER => true,     // Validate SSL Certificates
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_COOKIE         => $cookiesIn
        );
        curl_setopt_array($curl, $options);
        $curlResponse = curl_exec($curl);
        $responses = json_decode($curlResponse);

        $url_next = 'https://'.$slug.'.nationbuilder.com'.$responses->next.'&access_token='.$renovate->access_token;
        if(!isset($responses->next)){
            $renovate->execute = true;
        } else{
            $renovate->url = $url_next;
        }

        foreach($responses->results as $key => $response){
            $member = $this->updateMatchPerson($nation_id,$response,$membership);
            if($member){
                $renovate->no_members +=1;

            }else{
                $renovate->no_nomembers +=1;
            }
            $renovate->save();

            $count++;
        }

        $nation->member_count = $renovate->no_members;
        $nation->save();
    }


    public function updateMatchPerson($nation_id,$person_info,$nation_hq_id){
        try{
            $org_person_id = $person_info->id;
            $sub_nation = $this->dao->first($nation_id);


            $org_membership_url = 'https://'.$sub_nation->slug.'.nationbuilder.com/api/v1/people/'.$org_person_id.'/memberships?limit=200&access_token='.$sub_nation->access_token;

            $cookiesIn = '';
            $curl = curl_init();
            $options = array(
                CURLOPT_URL => $org_membership_url,
                CURLOPT_RETURNTRANSFER => true,     // return web page
                // CURLOPT_HEADER         => true,     //return headers in  addition to content
                CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                CURLOPT_ENCODING       => "",       // handle all encodings
                CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                CURLOPT_TIMEOUT        => 120,      // timeout on response
                // CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_SSL_VERIFYPEER => true,     // Validate SSL Certificates
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_COOKIE         => $cookiesIn
            );
            curl_setopt_array($curl, $options);
            $curlResponse = curl_exec($curl);

            $membership_results = json_decode($curlResponse);
            $memberships = $membership_results->results;

            if (count($memberships) != 0) {

                $hq_nation = $this->dao->first($nation_hq_id);

                $url = 'https://'.$hq_nation->slug.'.nationbuilder.com/api/v1/people/search?access_token='.$hq_nation->access_token;

                if ($person_info->first_name != null && $person_info->first_name != '') {
                    $url.='&first_name='.$person_info->first_name;
                }
                if ($person_info->last_name != null && $person_info->last_name != '') {
                    $url.='&last_name='.$person_info->last_name;
                }
                if ($person_info->email != null && $person_info->email != '') {
                    $url.='&email='.$person_info->email;
                }

                $url = $this->encodeURI($url);

                $curl = curl_init();
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,     // return web page
                    // CURLOPT_HEADER         => true,     //return headers in  addition to content
                    CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                    CURLOPT_ENCODING       => "",       // handle all encodings
                    CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                    CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                    CURLOPT_TIMEOUT        => 120,      // timeout on response
                    // CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                    CURLINFO_HEADER_OUT    => true,
                    CURLOPT_SSL_VERIFYPEER => true,     // Validate SSL Certificates
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                    CURLOPT_COOKIE         => $cookiesIn
                );
                curl_setopt_array($curl, $options);
                $peopleCurl = curl_exec($curl);

                $people = json_decode($peopleCurl);

                $person_hq_id = 0;
                if (count($people->results) == 1) {
                    $person_hq_id = $people->results[0]->id;
                } else {
                    foreach ($people->results as $person) {
                        if ($person->phone == $person_info->phone && $person->mobile == $person_info->mobile) {
                            $person_hq_id = $person->id;
                            break;
                        }
                    }
                }

                foreach ($memberships as $org_membership) {
                    $name           = isset($org_membership->name) ? $org_membership->name : '';
                    $status         = isset($org_membership->status) ? $org_membership->status : '';
                    $status_reason  = isset($org_membership->status_reason) ? $org_membership->status_reason : '';
                    $started_at     = isset($org_membership->started_at) ? $org_membership->started_at : '';
                    $expires_on     = isset($org_membership->expires_on) ? $org_membership->expires_on : '';

                    $params['membership']['name'] = $name;
                    $params['membership']['status'] = $status;
                    $params['membership']['status_reason'] = $status_reason;
                    $params['membership']['started_at'] = $started_at;
                    $params['membership']['expires_on'] = $expires_on;

                    $url = 'https://'.$hq_nation->slug.'.nationbuilder.com/api/v1/people/'.$person_hq_id.'/memberships?access_token='.$hq_nation->access_token;

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => json_encode($params),
                        CURLOPT_HTTPHEADER => array(
                            "x-rapidapi-host: google-translate1.p.rapidapi.com",
                            "x-rapidapi-key: d1ec636ac5msh35885a399298175p14f2e4jsn92997c76c589",
                            "Content-Type: application/json"
                        ),
                    ));

                    $memberCurl = curl_exec($curl);
                    Log::info($memberCurl);
                }
                Log::info('=== Membership sync successful ===');
                return true;
            }else{
                return false;
            }
        }catch(Exception $e){
            Log::info('--Log Sync Error Begin--');
            Log::info($e->getMessage());
            Log::info('--Log Sync Error End--');
        }
    }

    public function encodeURI($url) {
        $unescaped = array(
            '%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
            '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
        );
        $reserved = array(
            '%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
            '%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
        );
        $score = array(
            '%23'=>'#'
        );
        return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));
    }
}
