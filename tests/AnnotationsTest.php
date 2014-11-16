<?php

use Devonzara\Breadcrumbs\Annotations\Scanner;

class AnnotationsTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function it_should_generate_breadcrumbs()
	{
		require_once __DIR__ . '/fixtures/OnlyController.php';
		require_once __DIR__ . '/fixtures/ExceptController.php';

		foreach (['except', 'only'] as $method)
		{
			$annotationScanner = Scanner::create(['App\Http\Controllers\\'.ucwords($method).'Controller']);

			$renderedDefinitions = str_replace(PHP_EOL, "\n", $annotationScanner->getBreadcrumbDefinitions());

			$expectedDefinitions = trim(file_get_contents(__DIR__."/results/{$method}.controller.annotations.php"));

			$this->assertEquals($expectedDefinitions, $renderedDefinitions);
		}
	}

}
