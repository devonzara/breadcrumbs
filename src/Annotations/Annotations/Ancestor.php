<?php namespace Devonzara\Breadcrumbs\Annotations\Annotations;

use ReflectionClass;
use Devonzara\Breadcrumbs\Annotations\BreadcrumbCollection;

/**
 * @Annotation
 */
class Ancestor extends Annotation {

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
