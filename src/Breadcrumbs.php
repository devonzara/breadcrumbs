<?php namespace Devonzara\Breadcrumbs;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Foundation\Application;
use Devonzara\Breadcrumbs\Exceptions\UndefinedBreadcrumbException;

class Breadcrumbs
{

	/**
	 * The application instance.
	 *
	 * @var \Illuminate\Contracts\Foundation\Application
	 */
	private $app;

	/**
	 * The application's router.
	 *
	 * @var \Illuminate\Routing\Router
	 */
	private $router;

	/**
	 * A Collection of crumbs.
	 *
	 * @var \Illuminate\Support\Collection
	 */
	protected $crumbs;

	/**
	 * Current trail of breadcrumbs.
	 *
	 * @var array
	 */
	protected $currentTrail;

	/**
	 * They key of route we're currently building.
	 *
	 * @var string
	 */
	protected $currentKey;

	/**
	 * The actual <title> of the current page.
	 *
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * A buffer for the insert method.
	 *
	 * @var array
	 */
	protected $buffer;

	/**
	 * Initialize the instance.
	 *
	 * @param  \Illuminate\Contracts\Foundation\Application  $app
	 * @param  \Illuminate\Support\Collection                $collection
	 */
	function __construct(Application $app, Collection $collection)
	{
		$this->app = $app;

		$this->router = $app['router'];

		$this->crumbs = $collection->make();

		$this->currentTrail = $collection->make();

		$this->setPageTitle();
	}

	/**
	 * Add the route to the Collection of breadcrumbs.
	 *
	 * @param  string  $key
	 * @param  string  $name
	 * @param  string  $action
	 * @param  string  $parent
	 */
	public function add($key, $name, $action, $parent)
	{
		$this->crumbs->put($key, compact('name', 'action', 'parent'));
	}

	/**
	 * Get the breadcrumb trail matching the current action.
	 *
	 * @return array
	 */
	public function getBreadcrumbs()
	{
		if ( ! $this->currentRouteHasBreadcrumbs()) return [];

		return $this->getBreadcrumbsFor($this->currentKey);
	}

	/**
	 * Get the breadcrumb trail for the specified key.
	 *
	 * @param  string  $key
	 * @param  array   ...$args
	 * @return array
	 * @throws \Devonzara\Breadcrumbs\Exceptions\UndefinedBreadcrumbException
	 */
	public function getBreadcrumbsFor($key)
	{
		if ( ! $this->crumbs->get($this->currentKey = $key))
		{
			throw new UndefinedBreadcrumbException("Breadcrumb [{$key}] is undefined.");
		}

		$args = array_slice(func_get_args(), 1);

		$this->buildCrumbs($this->currentKey, (array) $args);

		return $this->currentTrail->toArray();
	}

	/**
	 * Check if the current route has breadcrumbs defined.
	 *
	 * @return bool
	 */
	protected function currentRouteHasBreadcrumbs()
	{
		foreach ($this->crumbs as $key => $crumb)
		{
			if ($crumb['action'] == $this->router->currentRouteAction())
			{
				$this->currentKey = $key;

				return true;
			}
		}

		return false;
	}

	/**
	 * Compile the crumb trail for the specified key.
	 *
	 * @param  string  $key
	 * @param  array   $args
	 * @return void
	 */
	protected function buildCrumbs($key, array $args = null)
	{
		$crumb = $this->crumbs->get($key);
		$crumbParameters = (array) current($args);

		$name = $this->getName($crumb, $crumbParameters);
		$url = $this->getUrl($crumb, $crumbParameters);

		$this->prepend($key, $name, $url);

		if ($crumb['parent'])
		{
			$this->buildCrumbs($crumb['parent'], (array) array_shift($args));
		}
	}

	/**
	 * Build the name for the specified crumb.
	 *
	 * @param  string  $crumb
	 * @param  array   $crumbParameters
	 * @return string
	 */
	protected function getName($crumb, array &$crumbParameters)
	{
		$name = array_splice($crumbParameters, 0, 1);

		return preg_replace('/\{(.*?)\??\}/', current($name), $crumb['name']);
	}

	/**
	 * Generate the URL for the specified crumb.
	 *
	 * @param  string  $crumb
	 * @param  array   $crumbParameters
	 * @return string
	 */
	protected function getUrl($crumb, array &$crumbParameters)
	{
		$url = $this->app['url']->action($crumb['action'], $crumbParameters);

		return $url;
	}

	/**
	 * Push a new crumb onto the Collection.
	 *
	 * @param  string  $key
	 * @param  string  $name
	 * @param  string  $url
	 * @param  array   $extras
	 * @return void
	 */
	public function push($key, $name, $url, array $extras = null)
	{
		$this->currentTrail->put(
			$key, array_merge(compact('key', 'name', 'url'), (array) $extras)
		);
	}

	/**
	 * Push a new crumb on to the Collection.
	 *
	 * @param  string  $key
	 * @param  string  $name
	 * @param  string  $url
	 * @param  array   $extras
	 * @return void
	 */
	public function prepend($key, $name, $url, array $extras = null)
	{
		$payload = array_merge(compact('key', 'name', 'url'), (array) $extras);

		$this->buffer = ['key' => $key, 'data' => $payload];

		$this->insertAt(0);
	}

	/**
	 * Place the new crumb on the buffer.
	 *
	 * @param  string  $key
	 * @param  string  $name
	 * @param  string  $url
	 * @param  array   $extras
	 * @return $this
	 */
	public function insert($key, $name, $url, array $extras = null)
	{
		$payload = array_merge(compact('key', 'name', 'url'), (array) $extras);

		$this->buffer = ['key' => $key, 'data' => $payload];

		return $this;
	}

	/**
	 * Put the current buffer after the specified key.
	 *
	 * @param $key
	 */
	public function after($key)
	{
		$this->insertAt($this->getSliceIndex($key, true));
	}

	/**
	 * Put the current buffer before the specified key.
	 *
	 * @param $key
	 */
	public function before($key)
	{
		$this->insertAt($this->getSliceIndex($key));
	}

	/**
	 * Insert the crumb that's current on the buffer at the specified index.
	 *
	 * @param $index
	 * @return void
	 */
	protected function insertAt($index)
	{
		$count = $this->currentTrail->count();

		if ($index == $count)
		{
			$this->currentTrail->put($this->buffer['key'], $this->buffer['data']);
		}
		else
		{
			$items = $this->currentTrail->all();

			$crumbs = array_slice($items, 0, $index, true)
				+ [$this->buffer['key'] => $this->buffer['data']]
				+ array_slice($items, $index, $count - $index, true);

			$this->currentTrail = $this->currentTrail->make($crumbs);
		}

		unset($this->buffer);
	}

	/**
	 * Clear the current breadcrumb trail.
	 */
	public function clear()
	{
		unset($this->currentTrail);
	}

	/**
	 * Return the index to slice on.
	 *
	 * @param  string  $key
	 * @param  bool    $after
	 * @return int
	 */
	protected function getSliceIndex($key, $after = false)
	{
		$index = array_search($key, $this->currentTrail->keys());

		return $after ? ++$index : $index;
	}

	/**
	 * Return the Collection of crumbs.
	 *
	 * @return Collection
	 */
	public function getCrumbs()
	{
		return $this->currentTrail;
	}

	/**
	 * Set the title of the page.
	 *
	 * @param  string|null  $title
	 * @return void
	 */
	public function setPageTitle($title = null)
	{
		$this->pageTitle = $title ?: $this->config('page_title');
	}

	/**
	 * Get the title of the page.
	 *
	 * @return string
	 */
	public function getPageTitle()
	{
		return $this->pageTitle;
	}

	/**
	 * Fetch a value from the package's config.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function config($key, $default = null)
	{
		return $this->app['config']->get("breadcrumbs::{$key}", $default);
	}

	/**
	 * Determine if the breadcrumbs have been scanned.
	 *
	 * @return bool
	 */
	public function breadcrumbsAreScanned()
	{
		return $this->app['files']->exists($this->getScannedBreadcrumbsPath());
	}

	/**
	 * Get the path to the scanned breadcrumbs file.
	 *
	 * @return string
	 */
	public function getScannedBreadcrumbsPath()
	{
		return $this->app['path.storage'].'/framework/breadcrumbs.scanned.php';
	}

}
