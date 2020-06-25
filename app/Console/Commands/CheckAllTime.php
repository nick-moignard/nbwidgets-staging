<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Libraries\Factory\AbstractFactory;
use App\Models\Nation;
use App\Models\Log;
use App\Models\People;

class CheckAllTime extends Command
{

    private $factory;
    private $api;
    private $dao;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check in midnight the nation updates to specific tag';

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
        $this->api = $this->factory->getDAO('NationApiConexion');
        $this->dao = AbstractFactory::getFactory('DAO')->getDAO('NationDao');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = date('Y-m-d H:i:s');
        $nations = Nation::where('status','=',1)->get();

        foreach($nations as $nation){
            //$this->dao->deactivatePeople($nation->id, $nation->nation_details->tag);
            People::where('nation_id',$nation->id)->where('nation_tag',$nation->nation_details->tag)->delete();

            $page = 1;
            $count = 0;

            try {
                AbstractFactory::getFactory('DAO')
                                ->getDAO('NationPagesDao')
                                ->deactivatePages($nation->id, $nation->nation_details->tag);

                $mytag = str_replace(' ', '%20', $nation->nation_details->tag);

                $temp_url = 'https://'.$nation->slug.'.nationbuilder.com';
                $next = '/api/v1/tags/'.$mytag.'/people?limit=50';


                $daoPage = AbstractFactory::getFactory('DAO')->getDAO('NationPagesDao');
                $daoPeople = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao');

                while ($next != null) {

                    $data = [
                        'nation_id' => $nation->id,
                        'nation_tag' => $nation->nation_details->tag,
                        'number_page' => $page,
                        'page_url' => $next
                    ];

                    $daoPage->insert($data);

                    $url =  $temp_url.$next.'&access_token='.$nation->access_token;
                    $response = $this->api->get($url);

                    if (!empty($response)) {
                        foreach ($response->results as $person) {

                            $count += 1;
                            $city = null;
                            $country = '';
                            $home_address = null;
                            $address2 = null;
                            $address3 = null;
                            $zip = null;
                            $state = '';
                            $country_code = null;
                            $industry = $person->industry;

                            if ($person->primary_address != null) {

                                $city = $person->primary_address->city;
                                $country = $person->primary_address->country_code;
                                $home_address = $person->primary_address->address1;
                                $state = $person->primary_address->state;
                                $address2 = $person->primary_address->address2;
                                $address3 = $person->primary_address->address3;
                                $zip = $person->primary_address->zip;
                                $country_code = $person->primary_address->country_code;
                            }

                            if (isset($this->isoCountries[$country]))
                                $country = $this->isoCountries[$country];

                                $insertData = [
                                    'nation_id'         => $nation->nation_id,
                                    'nation_tag'        => $nation->tag,
                                    'number_page'       => $page,
                                    'first_name'        => $person->first_name,
                                    'last_name'         => $person->last_name,
                                    'industry'          => $industry,
                                    'city'              => $city,
                                    'country'           => $country,
                                    'state'             => $state,
                                    'profile_image'     => $person->profile_image_url_ssl,
                                    'occupation'        => $person->occupation,
                                    'employer'          => $person->employer,
                                    'email'             => $person->email,
                                    'twitter'           => $person->twitter_id,
                                    'linkedin'          => $person->linkedin_id,
                                    'facebook'          => $person->has_facebook,
                                    'person_id'         => $person->id,
                                    'phone'             => $person->phone,
                                    'work_phone'        => $person->work_phone_number,
                                    'mobile'            => $person->mobile,
                                    'primary_address'   => $home_address,
                                    'secondary_address' => $address2,
                                    'tertiary_address'  => $address3,
                                    'zip'               => $zip,
                                    'country_code'      => $country_code,
                                    'tags'              => json_encode($person->tags)
                                ];

                                People::updateOrCreate(['person_id' => $person->id,'nation_id' => $nation->id],$insertData);
                        }

                        $next = $response->next;

                    } else {
                        $next = null;
                    }

                    $page++;
                }

                //$this->dao->deleteCache($nation->id);
                $temp_url = 'https://'.$nation->slug.'.nationbuilder.com/api/v1/people/count?access_token='.$nation->access_token;

                $response = $this->api->get($temp_url);
                //  $this->dao->deleteCache($nation->id);
                $this->dao->update(['people_count' => $count], $nation->id);
                $details_dao = AbstractFactory::getFactory('DAO')->getDAO('NationDetailsDao');

                $dataLog = [
                    'user_id' => 1,
                    'nation_id' => $nation->id,
                    'description' => 'Cache Refreshed Nation "'.$nation->name.'"'
                ];

                Log::create($dataLog);

            } catch (Exception $e) {

            }
        }
    }
}
