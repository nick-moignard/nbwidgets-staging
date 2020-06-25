<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Libraries\Factory\AbstractFactory;
use App\Models\Sender;
use App\Models\Log;
use App\Models\People;

class CheckManual extends Command
{

    private $factory;
    private $api;
    private $dao;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:manual';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check in every moment the nation updates to specific tag created manually';

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

        $senders = Sender::where('manual','=',1)->where('execute','=',0)->orderBy('created_at','DESC')->chunk(100, function ($sender) use ($today) {

            foreach ($sender as $s) {
                People::where('nation_id',$s->nation_id)->where('nation_tag',$s->tag)->delete();

                //$this->dao->deactivatePeople($s->nation_id, $s->tag);

                $page = 1;
                $count = 0;

                try {
                    AbstractFactory::getFactory('DAO')
                                    ->getDAO('NationPagesDao')
                                    ->deactivatePages($s->nation_id, $s->tag);

                    $mytag = str_replace(' ', '%20', $s->tag);

                    $temp_url = 'https://'.$s->slug.'.nationbuilder.com';
                    $next = '/api/v1/tags/'.$mytag.'/people?limit=50';


                    $daoPage = AbstractFactory::getFactory('DAO')->getDAO('NationPagesDao');
                    $daoPeople = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao');

                    while ($next != null) {

                        $data = [
                            'nation_id' => $s->nation_id,
                            'nation_tag' => $s->tag,
                            'number_page' => $page,
                            'page_url' => $next
                        ];

                        $daoPage->insert($data);

                        $url =  $temp_url.$next.'&access_token='.$s->access_token;
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
                                $industry = null;
                                if(isset($person->industry)){
                                    $industry = $person->industry;
                                }

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
                                        'nation_id'         => $s->nation_id,
                                        'nation_tag'        => $s->tag,
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

                                     People::updateOrCreate(['person_id' => $person->id,'nation_id' => $s->nation_id],$insertData);
                            }

                            $next = $response->next;

                        } else {
                            $next = null;
                        }

                        $page++;

                        $s->current = $count;
                        $s->page = $page;
                    }

                    //$this->dao->deleteCache($s->nation_id);
                    $temp_url = 'https://'.$s->slug.'.nationbuilder.com/api/v1/people/count?access_token='.$s->access_token;

                    $response = $this->api->get($temp_url);
                    //  $this->dao->deleteCache($nation_id);
                    $this->dao->update(['people_count' => $count], $s->nation_id);
                    $details_dao = AbstractFactory::getFactory('DAO')->getDAO('NationDetailsDao');

                    $dataLog = [
                        'user_id' => $s->user_id,
                        'nation_id' => $s->nation_id,
                        'description' => 'Cache Refreshed Nation "'.$s->slug.'"'
                    ];

                    Log::create($dataLog);

                    $s->execute = 1;
                    $s->save();

                } catch (Exception $e) {

                }
            }
        });
    }
}
