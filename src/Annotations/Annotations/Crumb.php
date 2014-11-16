<?php namespace Devonzara\Breadcrumbs\Annotations\Annotations;

use ReflectionMethod;
use Devonzara\Breadcrumbs\Annotations\MethodBreadcrumb;

/**
 * @Annotation
 */
class Crumb extends Annotation {

	/**
	 * Apply the annotation's settings to the given endpoint.
	 *
	 * @param  MethodBreadcrumb  $breadcrumb
	 * @param  ReflectionMethod  $method
	 * @return void
	 */
	public function modify(MethodBreadcrumb $breadcrumb, ReflectionMethod $method)
	{
		$values = isset($this->values['value']) ? $this->values['value'] : null;
		$ancestor = isset($this->values['ancestor']) ? $this->values['ancestor'] : null;

		$breadcrumb->ancestor = isset($ancestor) ? $ancestor : null;

		if (is_array($values))
		{
			$breadcrumb->key = array_values($values)[0];

			$breadcrumb->name = isset($values[1]) ? $values[1] : $breadcrumb->key;

			return;
		}

		$breadcrumb->key = $breadcrumb->name = $values;
	}

}
