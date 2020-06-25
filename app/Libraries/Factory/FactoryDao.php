<?php

namespace App\Libraries\Factory;
use App\Libraries\Dao\NationDao;
use App\Libraries\Dao\NationDetailsDao;
use App\Libraries\Dao\NationPagesDao;
use App\Libraries\Dao\PeopleDao;
use App\Libraries\Dao\LogDao;
use App\Libraries\Dao\UserDao;
use App\Libraries\Dao\RoleDao;

class FactoryDAO
{
	public  function getDAO($dao)
	{
		if($dao=='NationDao')
		{
			return new NationDao();
		}
		else if($dao=='NationDetailsDao')
		{
			return new NationDetailsDao();
		}
		else if($dao=='NationPagesDao')
		{
			return new NationPagesDao();
		}
		else if($dao=='PeopleDao')
		{
			return new PeopleDao();
		}
		else if($dao=='LogDao')
		{
			return new LogDao();
		}
		else if($dao=='UserDao')
		{
			return new UserDao();
		}
		else if($dao=='RoleDao')
		{
			return new RoleDao();
		}
		else
		{
			\abort(500,'Undefined DAO');
		}

	}
    
}