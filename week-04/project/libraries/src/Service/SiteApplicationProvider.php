<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Service;

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\Web\WebClient;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;

use Octoleo\CMS\Controller\HomepageController;
use Octoleo\CMS\Controller\WrongCmsController;
use Octoleo\CMS\Controller\PageController;
use Octoleo\CMS\Model\PageModel;
use Octoleo\CMS\View\Page\PageHtmlView;
use Octoleo\CMS\Application\SiteApplication;

use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Route;
use Joomla\Router\Router;
use Joomla\Router\RouterInterface;
use Psr\Log\LoggerInterface;

/**
 * Application service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/ApplicationProvider.php
 */
class SiteApplicationProvider implements ServiceProviderInterface
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
		/*
		 * Application Classes
		 */

		// This service cannot be protected as it is decorated when the debug bar is available
		$container->alias(SiteApplication::class, AbstractWebApplication::class)
			->share(AbstractWebApplication::class, [$this, 'getSiteApplicationClassService']);

		/*
		 * Application Helpers and Dependencies
		 */

		// This service cannot be protected as it is decorated when the debug bar is available
		$container->alias(ContainerControllerResolver::class, ControllerResolverInterface::class)
			->share(ControllerResolverInterface::class, [$this, 'getControllerResolverService']);

		$container->share(WebClient::class, [$this, 'getWebClientService'], true);

		// This service cannot be protected as it is decorated when the debug bar is available
		$container->alias(RouterInterface::class, 'application.router')
			->alias(Router::class, 'application.router')
			->share('application.router', [$this, 'getApplicationRouterService']);

		$container->share(Input::class, [$this, 'getInputClassService'], true);

		/*
		 * MVC Layer
		 */

		// Controllers
		$container->alias(HomepageController::class, 'controller.homepage')
			->share('controller.homepage', [$this, 'getControllerHomepageService'], true);

		$container->alias(PageController::class, 'controller.page')
			->share('controller.page', [$this, 'getControllerPageService'], true);

		$container->alias(WrongCmsController::class, 'controller.wrong.cms')
			->share('controller.wrong.cms', [$this, 'getControllerWrongCmsService'], true);

		// Models
		$container->alias(PageModel::class, 'model.page')
			->share('model.page', [$this, 'getModelPageService'], true);

		// Views
		$container->alias(PageHtmlView::class, 'view.page.html')
			->share('view.page.html', [$this, 'getViewPageHtmlService'], true);
	}

	/**
	 * Get the `application.router` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  RouterInterface
	 */
	public function getApplicationRouterService(Container $container): RouterInterface
	{
		$router = new Router;

		/*
		 * CMS Admin Panels
		 */
		$router->get(
			'/wp-admin',
			WrongCmsController::class
		);

		$router->get(
			'/wp-admin/*',
			WrongCmsController::class
		);

		$router->get(
			'wp-login.php',
			WrongCmsController::class
		);

		/*
		 * Web routes
		 */
		$router->addRoute(new Route(['GET', 'HEAD'], '/', HomepageController::class));

		$router->get(
			'/:view',
			PageController::class
		);

		$router->get(
			'/:view',
			PageController::class
		);

		$router->get(
			'/:view/:details',
			PageController::class
		);

		return $router;
	}

	/**
	 * Get the `controller.homepage` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  HomepageController
	 */
	public function getControllerHomepageService(Container $container): HomepageController
	{
		return new HomepageController(
			$container->get(RendererInterface::class),
			$container->get(Input::class),
			$container->get(SiteApplication::class)
		);
	}

	/**
	 * Get the `controller.page` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  PageController
	 */
	public function getControllerPageService(Container $container): PageController
	{
		return new PageController(
			$container->get(PageHtmlView::class),
			$container->get(Input::class),
			$container->get(SiteApplication::class)
		);
	}

	/**
	 * Get the `controller.wrong.cms` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  WrongCmsController
	 */
	public function getControllerWrongCmsService(Container $container): WrongCmsController
	{
		return new WrongCmsController(
			$container->get(Input::class),
			$container->get(SiteApplication::class)
		);
	}

	/**
	 * Get the Input class service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Input
	 */
	public function getInputClassService(Container $container): Input
	{
		return new Input($_REQUEST);
	}

	/**
	 * Get the `model.page` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  PageModel
	 */
	public function getModelPageService(Container $container): PageModel
	{
		return new PageModel($container->get(DatabaseInterface::class));
	}

	/**
	 * Get the WebApplication class service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  SiteApplication
	 */
	public function getSiteApplicationClassService(Container $container): SiteApplication
	{
		$application = new SiteApplication(
			$container->get(ControllerResolverInterface::class),
			$container->get(RouterInterface::class),
			$container->get(Input::class),
			$container->get('config'),
			$container->get(WebClient::class)
		);

		$application->httpVersion = '2';

		// Inject extra services
		$application->setDispatcher($container->get(DispatcherInterface::class));
		$application->setLogger($container->get(LoggerInterface::class));

		return $application;
	}

	/**
	 * Get the controller resolver service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  ControllerResolverInterface
	 */
	public function getControllerResolverService(Container $container): ControllerResolverInterface
	{
		return new ContainerControllerResolver($container);
	}

	/**
	 * Get the `view.page.html` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  PageHtmlView
	 */
	public function getViewPageHtmlService(Container $container): PageHtmlView
	{
		$view = new PageHtmlView(
			$container->get('model.page'),
			$container->get('renderer')
		);

		$view->setLayout('page.twig');

		return $view;
	}

	/**
	 * Get the web client service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  WebClient
	 */
	public function getWebClientService(Container $container): WebClient
	{
		/** @var Input $input */
		$input          = $container->get(Input::class);
		$userAgent      = $input->server->getString('HTTP_USER_AGENT', '');
		$acceptEncoding = $input->server->getString('HTTP_ACCEPT_ENCODING', '');
		$acceptLanguage = $input->server->getString('HTTP_ACCEPT_LANGUAGE', '');

		return new WebClient($userAgent, $acceptEncoding, $acceptLanguage);
	}
}
