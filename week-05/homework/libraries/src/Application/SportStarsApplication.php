<?php
/**
 * @package    Sport Stars
 *
 * @created    19th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\Application;

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\SessionAwareWebApplicationInterface;
use Joomla\Application\SessionAwareWebApplicationTrait;
use Joomla\Application\Web\WebClient;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use function call_user_func;

/**
 * Site application class
 * source: https://github.com/joomla/framework.joomla.org/blob/master/src/WebApplication.php
 */
class SportStarsApplication extends AbstractWebApplication implements SessionAwareWebApplicationInterface
{
	use SessionAwareWebApplicationTrait;

	/**
	 * The application's controller resolver.
	 *
	 * @var  ControllerResolverInterface
	 */
	protected $controllerResolver;

	/**
	 * The application's router.
	 *
	 * @var  RouterInterface
	 */
	protected $router;

	/**
	 * Class constructor.
	 *
	 * @param   ControllerResolverInterface  $controllerResolver  The application's controller resolver
	 * @param   RouterInterface              $router              The application's router
	 * @param   Input                        $input               An optional argument to provide dependency injection for the application's
	 *                                                            input object.
	 * @param   Registry                     $config              An optional argument to provide dependency injection for the application's
	 *                                                            config object.
	 * @param   WebClient                    $client              An optional argument to provide dependency injection for the application's
	 *                                                            client object.
	 * @param   ResponseInterface            $response            An optional argument to provide dependency injection for the application's
	 *                                                            response object.
	 */
	public function __construct(
		ControllerResolverInterface $controllerResolver,
		RouterInterface $router,
		Input $input = null,
		Registry $config = null,
		WebClient $client = null,
		ResponseInterface $response = null
	)
	{
		$this->controllerResolver = $controllerResolver;
		$this->router             = $router;

		// Call the constructor as late as possible (it runs `initialise`).
		parent::__construct($input, $config, $client, $response);
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 */
	protected function doExecute(): void
	{
		$route = $this->router->parseRoute($this->get('uri.route'), $this->input->getMethod());

		// Add variables to the input if not already set
		foreach ($route->getRouteVariables() as $key => $value)
		{
			$this->input->def($key, $value);
		}

		call_user_func($this->controllerResolver->resolve($route));
	}
}
