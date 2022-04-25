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

use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Change\Calculator\Controller\CalculatorController;
use Change\Calculator\Controller\WrongCmsController;
use Change\Calculator\Model\CalculatorModel;
use Change\Calculator\View\CalculatorHtmlView;
use Change\Calculator\Application\ChangeCalculatorApplication;

use Joomla\Input\Input;

/**
 * Model View Controller service provider
 */
class MVCProvider implements ServiceProviderInterface
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
		$container->alias(ContainerControllerResolver::class, ControllerResolverInterface::class)
			->share(ControllerResolverInterface::class, [$this, 'getControllerResolverService']);

		// Controllers
		$container->alias(CalculatorController::class, 'controller.calculator')
			->share('controller.calculator', [$this, 'getControllerCalculatorService'], true);

		$container->alias(WrongCmsController::class, 'controller.wrong.cms')
			->share('controller.wrong.cms', [$this, 'getControllerWrongCmsService'], true);

		// Models
		$container->alias(CalculatorModel::class, 'model.calculator')
			->share('model.calculator', [$this, 'getModelCalculatorService'], true);

		// Views
		$container->alias(CalculatorHtmlView::class, 'view.calculator.html')
			->share('view.calculator.html', [$this, 'getViewCalculatorHtmlService'], true);
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
			$container->get(ChangeCalculatorApplication::class)
		);
	}

	/**
	 * Get the `controller.calculator` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  CalculatorController
	 */
	public function getControllerCalculatorService(Container $container): CalculatorController
	{
		return new CalculatorController(
			$container->get(CalculatorHtmlView::class),
			$container->get(Input::class),
			$container->get(ChangeCalculatorApplication::class)
		);
	}

	/**
	 * Get the `model.calculator` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  CalculatorModel
	 */
	public function getModelCalculatorService(Container $container): CalculatorModel
	{
		return new CalculatorModel();
	}

	/**
	 * Get the `view.calculator.html` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  CalculatorHtmlView
	 */
	public function getViewCalculatorHtmlService(Container $container): CalculatorHtmlView
	{
		$view = new CalculatorHtmlView(
			$container->get('model.calculator'),
			$container->get('renderer')
		);

		$view->setLayout('calculator.twig');

		return $view;
	}
}
