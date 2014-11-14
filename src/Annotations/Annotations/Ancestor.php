<?php namespace Devonzara\Breadcrumbs\Annotations\Annotations;

use ReflectionClass;
use ReflectionMethod;
use Devonzara\Breadcrumbs\Annotations\MethodBreadcrumb;
use Devonzara\Breadcrumbs\Annotations\BreadcrumbCollection;

/**
 * @Annotation
 */
class Ancestor extends Annotation {

	/**
	 * {@inheritdoc}
	 */
	public function modify(MethodBreadcrumb $breadcrumb, ReflectionMethod $method)
	{
		$breadcrumb->ancestor = array_merge($breadcrumb->ancestor, (array) $this->value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function modifyCollection(BreadcrumbCollection $breadcrumbs, ReflectionClass $class)
	{
		foreach ($breadcrumbs as $breadcrumb)
		{
			foreach ((array) $this->value as $ancestor)
			{
				$breadcrumb->classAncestor = [
					'name' => $ancestor, 'only' => (array) $this->only, 'except' => (array) $this->except
				];
			}
		}
	}

}
