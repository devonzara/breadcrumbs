<?php namespace Devonzara\Breadcrumbs;

use Illuminate\Support\Facades\Facade;

class BreadcrumbsFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'breadcrumbs';
	}

}
