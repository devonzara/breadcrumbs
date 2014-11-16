<?php namespace Devonzara\Breadcrumbs\Annotations;

trait BreadcrumbTrait {

	/**
	 * Determine if the ancestor applies to a given key.
	 *
	 * @param  string  $key
	 * @param  array   $ancestor
	 * @return bool
	 */
	protected function ancestorAppliesToKey($key, $ancestor)
	{
		if ( ! empty($ancestor['only']) && ! in_array($key, $ancestor['only']))
		{
			return false;
		}
		elseif ( ! empty($ancestor['except']) && in_array($key, $ancestor['except']))
		{
			return false;
		}

		return true;
	}

}
