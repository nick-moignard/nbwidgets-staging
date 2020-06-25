<?php
namespace App\Libraries\Factory;

class AbstractFactory
{
	public static function getFactory($factory)
	{
		switch ($factory) {
            case 'DAO':
                return new FactoryDAO();
                break;
            case 'Api':
                return new FactoryNationApi();
                break;
            default:
                \abort(500,'Undefined Factory');
        }
	}
}
