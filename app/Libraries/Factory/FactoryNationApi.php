<?php

namespace App\Libraries\Factory;
use App\Libraries\Facade\NationApiConexion;

class FactoryNationApi
{
	public  function getDAO($dao)
	{
		if($dao=='NationApiConexion')
		{
			return new NationApiConexion();
		}
		else
		{
			\abort(500,'Undefined DAO');
		}

	}
    
}