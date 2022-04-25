<?php
/**
 * @package    Octoleo CMS
 *
 * @created    14th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Octoleo\CMS\Controller\DashboardController;
use Octoleo\CMS\Controller\LoginController;
use Octoleo\CMS\Controller\ItemsController;
use Octoleo\CMS\Controller\ItemController;
use Octoleo\CMS\Controller\MenuController;
use Octoleo\CMS\Controller\MenusController;
use Octoleo\CMS\Controller\UserController;
use Octoleo\CMS\Controller\UsersController;

use Joomla\Router\Router;
use Joomla\Router\RouterInterface;

/**
 * Application service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/ApplicationProvider.php
 */
class AdminRouterProvider implements ServiceProviderInterface
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
		// This service cannot be protected as it is decorated when the debug bar is available
		$container->alias(RouterInterface::class, 'application.router')
			->alias(Router::class, 'application.router')
			->share('application.router', [$this, 'getApplicationRouterService']);
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

		/**
		 * CMS Admin Panels
		 **/
		$router->all(
			'/index.php/dashboard',
			DashboardController::class
		);
		$router->get(
			'/index.php/users',
			UsersController::class
		);
		$router->all(
			'/index.php/user',
			UserController::class
		);
		$router->get(
			'/index.php/menus',
			MenusController::class
		);
		$router->all(
			'/index.php/menu',
			MenuController::class
		);
		$router->get(
			'/index.php/items',
			ItemsController::class
		);
		$router->all(
			'/index.php/item',
			ItemController::class
		);
		$router->get(
			'/*',
			LoginController::class
		);

		return $router;
	}
}
