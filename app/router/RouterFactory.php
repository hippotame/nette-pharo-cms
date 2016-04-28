<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('admin/<presenter>/<action>', 'Dashboard:default');

		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route('page/<id>[/<name>[.html]]', 'Page:default');
		$frontRouter[] = new Route('file/<id>/<filename>', 'File:download');
		$frontRouter[] = new Route('<presenter>[/<action=default>]/<name>.html');
		$frontRouter[] = new Route('docs/<name>.html', 'Docs:default');
		$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}

}
