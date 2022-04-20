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

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\Web\WebClient;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;

use Sport\Stars\Application\SportStarsApplication;

use Joomla\Input\Input;
use Joomla\Router\RouterInterface;
use Psr\Log\LoggerInterface;

/**
 * Application service provider
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/Service/ApplicationProvider.php
 */
class ApplicationProvider implements ServiceProviderInterface
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
		$container->alias(SportStarsApplication::class, AbstractWebApplication::class)
			->share(AbstractWebApplication::class, [$this, 'getSportStarsApplicationClassService']);

		/*
		 * Application Helpers and Dependencies
		 */
		$container->share(WebClient::class, [$this, 'getWebClientService'], true);
	}

	/**
	 * Get the WebApplication class service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  SportStarsApplication
	 */
	public function getSportStarsApplicationClassService(Container $container): SportStarsApplication
	{
		/** @var SportStarsApplication $application */
		$application = new SportStarsApplication(
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
