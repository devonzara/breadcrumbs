<?php namespace Devonzara\Breadcrumbs\Annotations;

trait BreadcrumbTrait {

	/**
	 * Determine if the ancestor applies to a given method.
	 *
	 * @param  string  $method
	 * @param  array   $ancestor
	 * @return bool
	 */
	protected function ancestorAppliesToMethod($method, $ancestor)
	{
		if ( ! empty($ancestor['only']) && ! in_array($method, $ancestor['only']))
		{
			return false;
		}
		elseif ( ! empty($ancestor['except']) && in_array($method, $ancestor['except']))
		{
			return false;
		}

		return true;
	}

	/**
	 * Implode the given list into a comma separated string.
	 *
	 * @param  array  $array
	 * @return string
	 */
	protected function implodeArray(array $array)
	{
		$results = [];

		foreach ($array as $key => $value)
		{
			if (is_string($key))
			{
				$results[] = "'".$key."' => '".$value."'";
			}
			else
			{
				$results[] = "'".$value."'";
			}
		}

		return count($results) > 0 ? implode(', ', $results) : '';
	}

}
