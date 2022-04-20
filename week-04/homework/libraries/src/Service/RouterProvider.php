<?php
/**
 * @package    Sport Stars
 *
 * @created    19th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Sport\Stars\Controller\TableController;
use Sport\Stars\Controller\EditController;

use Joomla\Router\Router;
use Joomla\Router\RouterInterface;

/**
 * Application service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/ApplicationProvider.php
 */
class RouterProvider implements ServiceProviderInterface
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
		 * Sports Stars
		 **/
		$router->get(
			'/',
			TableController::class
		);
		$router->all(
			'/edit',
			EditController::class
		);

		return $router;
	}
}
