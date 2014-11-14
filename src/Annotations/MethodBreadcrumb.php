<?php namespace Devonzara\Breadcrumbs\Annotations;

use Illuminate\Support\Collection;

class MethodBreadcrumb {

	use BreadcrumbTrait;

	/**
	 * The ReflectionClass instance for the controller class.
	 *
	 * @var \ReflectionClass
	 */
	public $reflection;

	/**
	 * The method that defines the breadcrumb.
	 *
	 * @var string
	 */
	public $method;

	/**
	 * The controller and method that defines the breadcrumb.
	 *
	 * @var string
	 */
	public $uses;

	/**
	 * The breadcrumb's identifier.
	 *
	 * @var string
	 */
	public $key;

	/**
	 * The breadcrumb's display name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The class level "inherited" ancestor.
	 *
	 * @var string
	 */
	public $classAncestor;

	/**
	 * The ancestor defined for a specific endpoint.
	 *
	 * @var string
	 */
	public $ancestor;

	/**
	 * Create a new route definition instance.
	 *
	 * @param  array  $attributes
	 */
	public function __construct(array $attributes = [])
	{
		foreach ($attributes as $key => $value)
			$this->{$key} = $value;
	}

	/**
	 * Create php string to output to our scanned file.
	 *
	 * @return string
	 */
	public function toBreadcrumbDefinition()
	{
		$crumb = sprintf(
			$this->getTemplate(), $this->key, addslashes($this->name), $this->uses, $this->getAncestor()
		);

		return $crumb;
	}

	/**
	 * Determine the ancestor of the endpoint.
	 *
	 * @return string
	 */
	protected function getAncestor()
	{
		$classAncestor = $this->getClassAncestorForMethod();

		$ancestor = $this->ancestor ?: $classAncestor;

		return $ancestor != $this->key ? $ancestor : null;
	}

	/**
	 * Determine if we have a class level ancestor to use or not.
	 *
	 * @return bool
	 */
	protected function getClassAncestorForMethod()
	{
		$useClass = $this->ancestorAppliesToMethod(
			$this->method, $this->classAncestor
		);

		return $useClass ? $this->classAncestor['name'] : null;
	}

	/**
	 * Get the template for the endpoint.
	 *
	 * @return string
	 */
	protected function getTemplate()
	{
		return '$breadcrumbs->add(\'%s\', \'%s\', \'%s\', \'%s\');';
	}

}
