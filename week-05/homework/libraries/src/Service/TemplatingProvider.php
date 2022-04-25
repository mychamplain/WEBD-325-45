<?php
/**
 * Joomla! Framework Website
 *
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Change\Calculator\Service;

use Joomla\Application\AbstractApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Change\Calculator\Renderer\FrameworkExtension;
use Change\Calculator\Renderer\FrameworkTwigRuntime;
use Joomla\Preload\PreloadManager;
use Joomla\Renderer\RendererInterface;
use Joomla\Renderer\TwigRenderer;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\Profiler\Profile;
use Twig\RuntimeLoader\ContainerRuntimeLoader;

/**
 * Templating service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/TemplatingProvider.php
 */
class TemplatingProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	public function register(Container $container): void
	{
		$container->alias(RendererInterface::class, 'renderer')
			->alias(TwigRenderer::class, 'renderer')
			->share('renderer', [$this, 'getRendererService'], true);

		$container->alias(Environment::class, 'twig.environment')
			->share('twig.environment', [$this, 'getTwigEnvironmentService'], true);

		$container->alias(DebugExtension::class, 'twig.extension.debug')
			->share('twig.extension.debug', [$this, 'getTwigExtensionDebugService'], true);

		$container->alias(FrameworkExtension::class, 'twig.extension.framework')
			->share('twig.extension.framework', [$this, 'getTwigExtensionFrameworkService'], true);

		$container->alias(LoaderInterface::class, 'twig.loader')
			->share('twig.loader', [$this, 'getTwigLoaderService'], true);

		$container->alias(Profile::class, 'twig.profiler.profile')
			->share('twig.profiler.profile', [$this, 'getTwigProfilerProfileService'], true);

		$container->alias(FrameworkTwigRuntime::class, 'twig.runtime.framework')
			->share('twig.runtime.framework', [$this, 'getTwigRuntimeFrameworkService'], true);

		$container->alias(ContainerRuntimeLoader::class, 'twig.runtime.loader')
			->share('twig.runtime.loader', [$this, 'getTwigRuntimeLoaderService'], true);

		$this->tagTwigExtensions($container);
	}

	/**
	 * Get the `renderer` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  RendererInterface
	 */
	public function getRendererService(Container $container): RendererInterface
	{
		return new TwigRenderer($container->get('twig.environment'));
	}

	/**
	 * Get the `twig.environment` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Environment
	 */
	public function getTwigEnvironmentService(Container $container): Environment
	{
		$debug = false;

		$environment = new Environment(
			$container->get('twig.loader'),
			['debug' => $debug]
		);

		// Add the runtime loader
		$environment->addRuntimeLoader($container->get('twig.runtime.loader'));

		// Add the Twig extensions
		$environment->setExtensions($container->getTagged('twig.extension'));

		// add international
		$environment->addExtension(new IntlExtension());

		// Add a global tracking the debug states
		$environment->addGlobal('appDebug', false);
		$environment->addGlobal('fwDebug', $debug);

		return $environment;
	}

	/**
	 * Get the `twig.extension.debug` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DebugExtension
	 */
	public function getTwigExtensionDebugService(Container $container): DebugExtension
	{
		return new DebugExtension;
	}

	/**
	 * Get the `twig.extension.framework` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  FrameworkExtension
	 */
	public function getTwigExtensionFrameworkService(Container $container): FrameworkExtension
	{
		return new FrameworkExtension;
	}

	/**
	 * Get the `twig.loader` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  FilesystemLoader
	 */
	public function getTwigLoaderService(Container $container): FilesystemLoader
	{
		return new FilesystemLoader([LPATH_TEMPLATES]);
	}

	/**
	 * Get the `twig.profiler.profile` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Profile
	 */
	public function getTwigProfilerProfileService(Container $container): Profile
	{
		return new Profile;
	}

	/**
	 * Get the `twig.runtime.framework` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  FrameworkTwigRuntime
	 */
	public function getTwigRuntimeFrameworkService(Container $container): FrameworkTwigRuntime
	{
		return new FrameworkTwigRuntime(
			$container->get(AbstractApplication::class),
			$container->get(PreloadManager::class)
		);
	}

	/**
	 * Get the `twig.runtime.loader` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  ContainerRuntimeLoader
	 */
	public function getTwigRuntimeLoaderService(Container $container): ContainerRuntimeLoader
	{
		return new ContainerRuntimeLoader($container);
	}

	/**
	 * Tag services which are Twig extensions
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 */
	private function tagTwigExtensions(Container $container): void
	{
		$container->tag('twig.extension', ['twig.extension.framework']);
	}
}