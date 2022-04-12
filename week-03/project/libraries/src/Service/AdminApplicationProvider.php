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

use Joomla\Session\SessionInterface;
use Octoleo\CMS\Controller\DashboardController;
use Octoleo\CMS\Controller\LoginController;
use Octoleo\CMS\Controller\WrongCmsController;
use Octoleo\CMS\Model\DashboardModel;
use Octoleo\CMS\User\UserFactoryInterface;
use Octoleo\CMS\View\Admin\DashboardHtmlView;
use Octoleo\CMS\Application\AdminApplication;

use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Router;
use Joomla\Router\RouterInterface;
use Psr\Log\LoggerInterface;

/**
 * Application service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/ApplicationProvider.php
 */
class AdminApplicationProvider implements ServiceProviderInterface
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
		$container->alias(AdminApplication::class, AbstractWebApplication::class)
			->share(AbstractWebApplication::class, [$this, 'getAdminApplicationClassService']);

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
		$container->alias(DashboardController::class, 'controller.dashboard')
			->share('controller.dashboard', [$this, 'getControllerDashboardService'], true);

		$container->alias(LoginController::class, 'controller.login')
			->share('controller.login', [$this, 'getControllerLoginService'], true);

		$container->alias(WrongCmsController::class, 'controller.wrong.cms')
			->share('controller.wrong.cms', [$this, 'getControllerWrongCmsService'], true);

		// Models
		$container->alias(DashboardModel::class, 'model.dashboard')
			->share('model.dashboard', [$this, 'getModelDashboardService'], true);

		// Views
		$container->alias(DashboardHtmlView::class, 'view.dashboard.html')
			->share('view.dashboard.html', [$this, 'getViewDashboardHtmlService'], true);
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
		$router->all(
			'/index.php?dashboard=*',
			DashboardController::class
		);
		$router->get(
			'/*',
			LoginController::class
		);

		return $router;
	}

	/**
	 * Get the `controller.login` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  LoginController
	 */
	public function getControllerLoginService(Container $container): LoginController
	{
		return new LoginController(
			$container->get(RendererInterface::class),
			$container->get(Input::class),
			$container->get(AdminApplication::class)
		);
	}

	/**
	 * Get the `controller.dashboard` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DashboardController
	 */
	public function getControllerDashboardService(Container $container): DashboardController
	{
		return new DashboardController(
			$container->get(DashboardHtmlView::class),
			$container->get(Input::class),
			$container->get(AdminApplication::class)
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
			$container->get(AdminApplication::class)
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
	 * Get the `model.dashboard` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DashboardModel
	 */
	public function getModelDashboardService(Container $container): DashboardModel
	{
		return new DashboardModel($container->get(DatabaseInterface::class));
	}

	/**
	 * Get the WebApplication class service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  AdminApplication
	 */
	public function getAdminApplicationClassService(Container $container): AdminApplication
	{
		/** @var \Octoleo\CMS\Application\AdminApplication $application */
		$application = new AdminApplication(
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
		$application->setSession($container->get(SessionInterface::class));
		$application->setUserFactory($container->get(UserFactoryInterface::class));

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
	 * @return  DashboardHtmlView
	 */
	public function getViewDashboardHtmlService(Container $container): DashboardHtmlView
	{
		return new DashboardHtmlView(
			$container->get('model.dashboard'),
			$container->get('renderer')
		);
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
