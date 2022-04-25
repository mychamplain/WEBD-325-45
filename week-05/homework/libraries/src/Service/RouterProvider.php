<?php
/**
 * @package    Change Calculator
 *
 * @created    24th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Change\Calculator\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Change\Calculator\Controller\CalculatorController;

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
		 * Change Calculator
		 **/
		$router->all(
			'/',
			CalculatorController::class
		);

		return $router;
	}
}
