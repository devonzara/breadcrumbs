<?php namespace Devonzara\Breadcrumbs;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Devonzara\Breadcrumbs\Annotations\Scanner;

class BreadcrumbsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Determines if we will auto-scan in the local environment.
	 *
	 * @var bool
	 */
	protected $scanWhenLocal = true;

	/**
	 * The controllers to scan for breadcrumb annotations.
	 *
	 * @var array
	 */
	protected $scan = [];

	/**
	 * An instance of Breadcrumbs.
	 *
	 * @var \Devonzara\Breadcrumbs\Breadcrumbs
	 */
	protected $breadcrumbs;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('breadcrumbs', '\Devonzara\Breadcrumbs\Breadcrumbs');
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('devonzara/breadcrumbs', 'breadcrumbs', __DIR__);

		$this->breadcrumbs = $this->app['breadcrumbs'];

		$this->scan = array_unique(
			array_merge($this->scan, $this->breadcrumbs->config('scan'))
		);

		$this->loadBreadcrumbs();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['breadcrumbs'];
	}

	/**
	 * Load the application routes.
	 *
	 * @return void
	 */
	protected function loadBreadcrumbs()
	{
		if ($this->app->environment('local') && $this->scanWhenLocal)
		{
			$this->scanBreadcrumbs();
		}

		if ( ! empty($this->scan) && $this->breadcrumbs->breadcrumbsAreScanned())
		{
			$this->loadScannedBreadcrumbs();
		}
	}

	/**
	 * Scan the routes and write the scanned routes file.
	 *
	 * @return void
	 */
	protected function scanBreadcrumbs()
	{
		if (empty($this->scan)) return;

		$scanner = new Scanner($this->scan);
		$scans = $scanner->getBreadcrumbDefinitions();

		file_put_contents(
			$this->breadcrumbs->getScannedBreadcrumbsPath(), $scans
		);
	}

	/**
	 * Load the scanned application routes.
	 *
	 * @return void
	 */
	protected function loadScannedBreadcrumbs()
	{
		$this->app->booted(function()
		{
			$breadcrumbs = $this->breadcrumbs;

			require $breadcrumbs->getScannedBreadcrumbsPath();
		});
	}

}
