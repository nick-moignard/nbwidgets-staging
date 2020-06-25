<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Factory\AbstractFactory;
use App\Models\Log;
use App\Models\Nation;
use App\Models\NationPages;
use App\Models\People;
use App\Models\Sender;
use App\Models\Renovate;
use Exception;
use Auth;
use Config;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NationDetailsController;

class NationBuilderApiController extends Controller
{
    private $factory;
    private $api;
    private $dao;
    private $logDao;
    private $nation_details_dao;

    public $isoCountries = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, Democratic Republic',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island & Mcdonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran, Islamic Republic Of',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle Of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'s Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory, Occupied',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre And Miquelon',
        'VC' => 'Saint Vincent And Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia And Sandwich Isl.',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands, British',
        'VI' => 'Virgin Islands, U.S.',
        'WF' => 'Wallis And Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    public function __construct()
    {
        ini_set('max_execution_time', 1000);
        $this->factory = AbstractFactory::getFactory('Api');
        $this->factoryDao = AbstractFactory::getFactory('DAO');
        $this->api = $this->factory->getDAO('NationApiConexion');
        $this->dao = AbstractFactory::getFactory('DAO')->getDAO('NationDao');
        $this->logDao = $this->factoryDao->getDAO('LogDao');
        $this->nation_details_dao  = AbstractFactory::getFactory('DAO')->getDAO('NationDetailsDao');
    }

    /**
     * Generate Nation Token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate_token(Request $request)
    {
        $params = array(
            'code' => $request->code,
            'redirect_uri' => Config::get('app.url').'/nbcallback',
            'client_id' => $request->client["id"],
            'client_secret' => $request->client["secret"],
            'grant_type' => 'authorization_code'
        );

        $url = 'https://' .  $request->nation["slug"] . '.nationbuilder.com/oauth/token';

        $response = $this->api->post($url, $params);

        return response()->json(['status' => 'ok', 'data' => $response->access_token], 200);
    }

    public function clear_cache(Request $request)
    {
        $nation_id = $request->input('nation_id');
        $user_id = $request->input('user_id');
        $nation = $this->dao->first($nation_id);

        if(!$exist = Sender::where('nation_id','=',$nation_id)->where('execute','=',0)->first()){
            $sender = new Sender();
            $sender->nation_id      = $nation_id;
            $sender->user_id        = $user_id;
            $sender->manual         = 1;
            $sender->execute        = 0;
            $sender->current        = 0;
            $sender->page           = 0;
            $sender->tag            = $nation->nation_details->tag;
            $sender->slug           = $nation->slug;
            $sender->access_token   = $nation->access_token;
            $sender->save();

            return response()->json(['status' => 'ok'], 200);
        }else{
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function update_sync_members(Request $request)
    {
        $next_url = $request->input('next_url');
        $nation_id = $request->input('nation_id');
        $result = $this->dao->first($nation_id);
        $nation = $this->nation_details_dao->update($request->nation, $nation_id);
        $nation = $this->nation_details_dao->get($nation_id)[0];
        //Log::create(["user_id" => $request->user_id, "nation_id" => $nation->id, 'description' => 'Update Nation "' . $nation->name . '"']);
        $this->logDao->create($request->user_id,$nation_id,'Update Nation "'.$nation->name.'"');

        $url = 'https://'.$result->slug.'.nationbuilder.com'.$next_url.'&access_token='.$result->access_token;

        if(!$exist = Renovate::where('nation_id','=',$nation_id)->where('execute','=',0)->first()){
            $renovate = new Renovate();
            $renovate->nation_id     = $nation_id;
            $renovate->execute       = 0;
            $renovate->no_members    = 0;
            $renovate->no_nomembers  = 0;
            $renovate->next_url      = $next_url;
            $renovate->slug          = $result->slug;
            $renovate->access_token  = $result->access_token;
            $renovate->url           = $url;
            $renovate->save();

            return response()->json(['status' => 'ok'], 200);
        }else{
            return response()->json(['status' => 'ok','data' => $exist], 200);
        }
    }

    public function update_match_person(Request $request){
        $nation_id = $request->all()['nation_id'];
        $person_info = $request->all()['person_info'];
        $nation_hq_id = $request->all()['nation_hq_id'];

        $org_person_id = $person_info['id'];

        $sub_nation = $this->dao->get($nation_id)->first();


        $org_membership_url = 'https://' . $sub_nation->slug . '.nationbuilder.com/api/v1/people/' . $org_person_id . '/memberships?limit=50&access_token=' . $sub_nation->access_token;

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
        if (curl_error($curl)) {
            echo 'fail';
            return;
        }
        $membership_results = json_decode($curlResponse);
        $memberships = $membership_results->results;
        if (count($memberships) == 0) {
            echo 'no membership';
            return;
        }
        $nation_hq_id_array = json_decode($nation_hq_id);
        foreach ($nation_hq_id_array as $hq_id) {
            $hq_nation = $this->dao->get($hq_id)->first();

            $url = 'https://' . $hq_nation->slug . '.nationbuilder.com/api/v1/people/search?access_token=' . $hq_nation->access_token;
            if ($person_info['first_name'] != null && $person_info['first_name'] != '') {
                $url .= '&first_name=' . $person_info['first_name'];
            }
            if ($person_info['last_name'] != null && $person_info['last_name'] != '') {
                $url .= '&last_name=' . $person_info['last_name'];
            }
            if ($person_info['email'] != null && $person_info['email'] != '') {
                $url .= '&email=' . $person_info['email'];
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
            $curlResponse = curl_exec($curl);
            if (curl_error($curl)) {
                echo 'fail';
                return;
            }
            $people = json_decode($curlResponse);
            $person_hq_id = 0;
            if (count($people->results) == 1) {
                $person_hq_id = $people->results[0]->id;
            } else {
                foreach ($people->results as $person) {
                    if ($person->phone == $person_info['phone'] && $person->mobile == $person_info['mobile']) {
                        $person_hq_id = $person->id;
                        break;
                    }
                }
            }

            if ($person_hq_id == 0) {
                echo 'no match person';
                continue;
            }

            foreach ($memberships as $org_membership) {

                $params['membership']['name'] = $org_membership->name;
                $params['membership']['status'] = $org_membership->status;
                $params['membership']['status_reason'] = $org_membership->status_reason;
                $params['membership']['started_at'] = $org_membership->started_at;
                $params['membership']['expires_on'] = $org_membership->expires_on;

                $url = 'https://' . $hq_nation['nation_slug'] . '.nationbuilder.com/api/v1/people/' . $person_hq_id . '/memberships?access_token=' . $hq_nation['nation_auth'];;
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
                $curlResponse = curl_exec($curl);
                if (curl_error($curl)) {
                    echo 'fail';
                    return;
                }
                echo $curlResponse;
            }
        }
    }

    public function create_sync_member_log(Request $request)
    {
        $nation_id = $request->all()['nation_id'];
        $user_id = $request->all()['user_id'];

        $nation = $this->dao->get($nation_id)->first();
        //Log::create(["user_id" => $user_id, "nation_id" => $nation->id, 'description' => 'Sync Members in Nation "' . $nation->name . '"']);
        $this->logDao->create($user_id,$nation->id,'Sync Members in Nation "'.$nation->name.'"');
    }

    public function sync_image(Request $request)
    {
        $nation_id = $request->all()['nation_id'];
        $user_id = $request->all()['user_id'];
        $nation = $this->dao->get($nation_id)[0];
        $next = '/api/v1/people?limit=50';
        $url = 'https://' . $nation->slug . '.nationbuilder.com' . $next . '&access_token=' . $nation->access_token;
        $daoPeople = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao');
        while ($next != null) {
            $url = $url;
            $response = $this->api->get($url);
            if (!empty($response)) {
                foreach ($response->results as $person) {
                    $updateData = array(
                        'nation_id' => $nation->id,
                        'profile_image' => $person->profile_image_url_ssl,
                        'person_id' => $person->id,
                    );
                    $daoPeople->update_image($updateData);
                }
                $next = $response->next;
            } else {
                $next = null;
            }
        }
        //Log::create(["user_id" => $user_id, "nation_id" => $nation->id, 'description' => 'Sync Image Refreshed Nation "' . $nation->name . '"']);
        $this->logDao->create($user_id,$nation->id,'Sync Image Refreshed Nation "'.$nation->name.'"');
        return response()->json(['status' => 'ok'], 200);
    }

    // For cronjob start
    public function refreshAllNation()
    {
        $nations = AbstractFactory::getFactory('DAO')->getDAO('NationDetailsDao')->select();

        foreach ($nations as $result) {
            $cnt = $result->people_count;
            $nation_id = $result->nation_id;
            $nation = $this->dao->get($nation_id)->first();
            $this->dao->deleteCache($nation_id);


            $mytag = str_replace(' ', '%20', $nation->nation_details->tag);
            $temp_url = 'https://' . $nation->slug . '.nationbuilder.com';
            $next = '/api/v1/tags/' . $mytag . '/people?limit=50';
            $page = 1;
            $daoPage = AbstractFactory::getFactory('DAO')->getDAO('NationPagesDao');
            $daoPeople = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao');
            while ($next != null) {
                $daoPage->insert(['nation_id' => $nation->id, 'nation_tag' => $nation->nation_details->tag, 'number_page' => $page, 'page_url' => $next]);
                $url =  $temp_url . $next . '&access_token=' . $nation->access_token;
                $response = $this->api->get($url);
                if (!empty($response)) {
                    foreach ($response->results as $person) {
                        $city = null;
                        $country = '';
                        $home_address = null;
                        $address2 = null;
                        $address3 = null;
                        $zip = null;
                        $state = '';
                        $country_code = null;
                        $industry = null;
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
                        $insertData = array(
                            'nation_id' => $nation->id,
                            'nation_tag' => $nation->nation_details->tag,
                            'number_page' => $page,
                            'first_name' => $person->first_name,
                            'last_name' => $person->last_name,
                            'industry' => $industry,
                            'city' => $city,
                            'country' => $country,
                            'state' => $state,
                            'profile_image' => $person->profile_image_url_ssl,
                            'occupation' => $person->occupation,
                            'employer' => $person->employer,
                            'email' => $person->email,
                            'twitter' => $person->twitter_id,
                            'linkedin' => $person->linkedin_id,
                            'facebook' => $person->has_facebook,
                            'person_id' => $person->id,
                            'phone' => $person->phone,
                            'work_phone' => $person->work_phone_number,
                            'mobile' => $person->mobile,
                            'primary_address' => $home_address,
                            'secondary_address' => $address2,
                            'tertiary_address' => $address3,
                            'zip' => $zip,
                            'country_code' => $country_code,
                            'tags' => json_encode($person->tags)
                        );

                        $daoPeople->insert($insertData);
                    }
                    $next = $response->next;
                } else {
                    $next = null;
                }
                $page++;
            }
            $temp_url = 'https://' . $nation->slug . '.nationbuilder.com/api/v1/people/count?access_token=' . $nation->access_token;;
            $response = $this->api->get($temp_url);

            $this->dao->update(['people_count' => $response->people_count], $nation_id);
            //Log::create(["user_id" => 0, "nation_id" => $nation->id, 'description' => 'Cache Refreshed by Cron Nation "' . $nation->name . '"']);
            $this->logDao->create(0,$nation->id,'Cache Refreshed by Cron Nation "'.$nation->name.'"');
        }
    }
    //For cronjob end

    public function getAllPeopleList(Request $request)
    {
        $nation_id = $request->nation_id;
        $forum =  $request->forum;
        $result = $this->nation_details_dao->get($nation_id)[0];
        if ($result != null) {
            $result =  $this->dao->getAllNationCache($nation_id, $result->nation_details->tag, $forum,$request->industry ?? '');
            return response()->json(['status' => 'ok', 'data' => $result], 200);
        } else
            echo '';
    }

    public function getPeopleList(Request $request)
    {
        $nation_id = $request->nation_id;
        $page = $request->page;
        $nation = $this->nation_details_dao->get($nation_id)[0];

        if ($nation != null) {
            $peopleList =  $this->dao->getAllNationCacheByPage($nation_id, $nation->nation_details->tag, $page);
            return response()->json(['status' => 'ok', 'data' => $peopleList], 200);
        } else
            echo '';
    }

    public function getPersonDetail(Request $request)
    {
        $nation_id = $request->nation_id;
        $nation = $this->dao->get($nation_id)[0];
        $person_id = $request->person_id;

        $result = AbstractFactory::getFactory('DAO')->getDAO('PeopleDao')->getPersonDetail($person_id, $nation_id);
        if ($nation != null) {
            $params = array(
                'access_token' =>  $nation->access_token
            );

            $url = 'https://' . $nation->slug . '.nationbuilder.com/api/v1/people/' . $person_id . '?' . http_build_query($params);

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
            if (curl_error($curl)) {
                echo '';
            } else {
                $data = json_decode($curlResponse);

                if(isset($data->person)){
                    if(isset($data->person->home_address->address1)){
                        $result->home_address = $data->person->home_address;
                    }
                    if(isset($data->person->work_address->address1)){
                        $result->work_address = $data->person->work_address;
                    }
                    if(isset($data->person->bio)){
                        $result->bio = $data->person->bio;
                    }
                    if (isset($data->person->profile_content)){
                        $result->profile_content = $data->person->profile_content;
                    } else{
                        $result->profile_content = null;
                    }
                    if(isset($data->person->twitter_login)){
                    $result->twitter = $data->person->twitter_login;
                    }
                    if(isset($data->person->occupation)){
                    $result->occupation = $data->person->occupation;
                    }
                    if(isset($data->person->industry)){
                    $result->industry = $data->person->industry;
                    }
                    if(isset($data->person->assistant_name)){
                    $result->assistant_name = $data->person->assistant_name;
                    }
                    if(isset($data->person->assistant_phone_number)){
                    $result->assistant_phone_number = $data->person->assistant_phone_number;
                    }
                    if(isset($data->person->assistant_email)){
                    $result->assistant_email = $data->person->assistant_email;
                    }
                    if (!empty($data->person->facebook_username)){
                        $result->facebook = $data->person->facebook_username;
                    } else{
                        $result->facebook = $data->person->facebook_profile_url;
                    }
                }
            }
            curl_close($curl);
        }
        return response()->json($result);
    }

    public function getPDFDetail(Request $request)
    {
        $nation_id = $request->nation_id;
        $nation = $this->nation_details_dao->get($nation_id);
        return response()->json(['status' => 'ok', 'data' => $nation[0]], 200);
    }

    public function update_image(Request $request)
    {
        if ($request->hasFile('logo')) {
            $path = base64_encode(file_get_contents($request->file('logo')));
        } else {
            $path = '';
        }
        $nation = Nation::where('slug', $request->nation_slug)->get()->first()->update(['logo' => $path]);
    }

    public function getPDFLogo(Request $request)
    {
        $nation_id = $request->nation_id;
        $result = $this->dao->first($nation_id);
        return response()->json(['status' => 'ok', 'data' => $result->logo], 200);
    }

    public function activate($id)
    {
        Nation::find($id)->update(["status" => 1]);
    }

    public function update_logo(Request $request)
    {
        if ($request->hasFile('logo')) {
            //Eliminar Anterior
            Storage::delete('/storage/logo.png');
            //Guardar Imagen
            $path = $request->file('logo')->storeAs('/storage', 'logo.'.$request->file('logo')->getClientOriginalExtension());

        } else {
            $path = '';
            return response()->json(['status' => 'fail'], 500);
        }
        return response()->json(['status' => 'ok'], 200);
    }
}
