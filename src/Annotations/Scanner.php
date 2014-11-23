<?php namespace Devonzara\Breadcrumbs\Annotations;

use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

class Scanner {

	/**
	 * The classes to scan for annotations.
	 *
	 * @var string
	 */
	protected $scan;

	/**
	 * Create a new event scanner instance.
	 *
	 * @param  array  $scan
	 */
	public function __construct(array $scan)
	{
		$this->scan = $scan;

		foreach (Finder::create()->files()->in(__DIR__.'/Annotations') as $file)
		{
			AnnotationRegistry::registerFile($file->getRealPath());
		}
	}

	/**
	 * Create a new scanner instance.
	 *
	 * @param  array  $scan
	 * @return static
	 */
	public static function create(array $scan)
	{
		return new static($scan);
	}

	/**
	 * Convert the scanned annotations into breadcrumbs.
	 *
	 * @return string
	 */
	public function getBreadcrumbDefinitions()
	{
		$output = '<?php'.PHP_EOL.PHP_EOL;

		$reader = $this->getReader();

		foreach ($this->getBreadcrumbsInClasses($reader) as $breadcrumb)
		{
			$output .= $breadcrumb->toBreadcrumbDefinition().PHP_EOL.PHP_EOL;
		}

		return trim($output);
	}

	/**
	 * Scan the directory and generate the breadcrumb manifest.
	 *
	 * @param SimpleAnnotationReader $reader
	 * @return BreadcrumbCollection|static
	 */
	protected function getBreadcrumbsInClasses(SimpleAnnotationReader $reader)
	{
		$breadcrumbs = new BreadcrumbCollection;

		foreach ($this->getClassesToScan() as $class)
		{
			$breadcrumbs = $breadcrumbs->merge(
				$this->getBreadcrumbsInClass($class, new AnnotationSet($class, $reader))
			);
		}

		return $breadcrumbs;
	}

	/**
	 * Build the breadcrumbs for the given class.
	 *
	 * @param  string  $class
	 * @param  AnnotationSet  $annotations
	 * @return EndpointCollection
	 */
	protected function getBreadcrumbsInClass(ReflectionClass $class, AnnotationSet $annotations)
	{
		$breadcrumbs = new BreadcrumbCollection;

		foreach ($annotations->method as $method => $methodAnnotations)
			$this->addBreadcrumb($breadcrumbs, $class, $method, $methodAnnotations);

		foreach ($annotations->class as $annotation)
			$annotation->modifyCollection($breadcrumbs, $class);

		return $breadcrumbs;
	}

	/**
	 * Create a new breadcrumb in the collection.
	 *
	 * @param  BreadcrumbCollection  $breadcrumbs
	 * @param  ReflectionClass  $class
	 * @param  string  $method
	 * @param  array  $annotations
	 * @return void
	 */
	protected function addBreadcrumb(BreadcrumbCollection $breadcrumbs, ReflectionClass $class,
		$method, array $annotations)
	{

		$breadcrumb = new MethodBreadcrumb([
			'reflection' => $class, 'method' => $method, 'uses' => '\\'.$class->name.'@'.$method
		]);

		$breadcrumbs->push($breadcrumb);

		foreach ($annotations as $annotation)
			$annotation->modify($breadcrumb, $class->getMethod($method));
	}

	/**
	 * Get all of the ReflectionClass instances in the scan path.
	 *
	 * @return array
	 */
	protected function getClassesToScan()
	{
		$classes = [];

		foreach ($this->scan as $class)
		{
			try
			{
				$classes[] = new ReflectionClass($class);
			}
			catch (\Exception $e)
			{
				//
			}
		}

		return $classes;
	}

	/**
	 * Get an annotation reader instance.
	 *
	 * @return \Doctrine\Common\Annotations\SimpleAnnotationReader
	 */
	protected function getReader()
	{
		with($reader = new SimpleAnnotationReader)
			->addNamespace('Devonzara\Breadcrumbs\Annotations\Annotations');

		return $reader;
	}

}
