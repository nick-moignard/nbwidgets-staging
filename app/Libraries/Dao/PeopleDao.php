<?php

namespace App\Libraries\Dao;

use App\Models\People;

class PeopleDao
{
    public function select()
    {
        $people = People::all();

        return $people;
    }
    public function insert($params)
    {
        $person = People::create($params);

        return $person;
    }

    public function get($id)
    {
        $person = People::where('id', $id)->get();

        return $person;
    }

    public function first($id)
    {
        $person = People::where('person_id', $id)->first();

        return $person;
    }

    public function update($request, $id)
    {
        $person = People::find($id);

        return $person->update($request);
    }
    public function update_image($data)
    {
        $person = People::where([['nation_id', '=', $data['nation_id']], ['person_id', '=', $data['person_id']]])->get()->first();
        if ($person) {
            $person->profile_image = $data['profile_image'];
            $person->save();
            return $person;
        }
        return;
    }
    public function delete($id)
    {
        $person = People::find($id);

        return $person->delete();
    }

    public function getPersonDetail($person_id, $nation_id = null)
    {
        $where[] = ['nation_id',$nation_id];
        if($nation_id) $where[] = ['person_id',$person_id];
        $people = People::where($where)
        ->join('nations','nations.id','=','people.nation_id')
        ->select('people.secondary_address as address2', 'people.tertiary_address as address3', 'people.city as city',
                'people.country as country','people.country_code as country_code','people.email','people.employer','people.facebook',
                'people.first_name','people.primary_address as home_address','people.id','people.industry','people.last_name','people.linkedin as linked_in',
                'people.mobile','nations.slug as nation_slug','people.nation_tag','people.occupation','people.number_page as page','people.person_id',
                'people.phone','people.profile_image','people.state','people.tags','people.twitter','people.work_phone','people.zip')
        ->get()
        ->first();
        return $people;
    }

    public function get_count_by_tag($tag){
        return People::where('nation_tag',$tag)->count();
    }
}
