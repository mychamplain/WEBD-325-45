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

use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use Sport\Stars\Controller\TableController;
use Sport\Stars\Controller\EditController;
use Sport\Stars\Controller\WrongCmsController;
use Sport\Stars\Model\TableModel;
use Sport\Stars\Model\EditModel;
use Sport\Stars\View\TableHtmlView;
use Sport\Stars\View\EditHtmlView;
use Sport\Stars\Application\SportStarsApplication;

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
		$container->alias(TableController::class, 'controller.table')
			->share('controller.table', [$this, 'getControllerTableService'], true);

		$container->alias(EditController::class, 'controller.edit')
			->share('controller.edit', [$this, 'getControllerEditService'], true);

		$container->alias(WrongCmsController::class, 'controller.wrong.cms')
			->share('controller.wrong.cms', [$this, 'getControllerWrongCmsService'], true);

		// Models
		$container->alias(TableModel::class, 'model.table')
			->share('model.table', [$this, 'getModelTableService'], true);

		$container->alias(EditModel::class, 'model.edit')
			->share('model.edit', [$this, 'getModelEditService'], true);

		// Views
		$container->alias(TableHtmlView::class, 'view.table.html')
			->share('view.table.html', [$this, 'getViewTableHtmlService'], true);

		$container->alias(EditHtmlView::class, 'view.edit.html')
			->share('view.edit.html', [$this, 'getViewEditHtmlService'], true);
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
			$container->get(SportStarsApplication::class)
		);
	}

	/**
	 * Get the `controller.table` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  TableController
	 */
	public function getControllerTableService(Container $container): TableController
	{
		return new TableController(
			$container->get(TableHtmlView::class),
			$container->get(Input::class),
			$container->get(SportStarsApplication::class)
		);
	}

	/**
	 * Get the `controller.edit` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  EditController
	 */
	public function getControllerEditService(Container $container): EditController
	{
		return new EditController(
			$container->get(EditModel::class),
			$container->get(EditHtmlView::class),
			$container->get(Input::class),
			$container->get(SportStarsApplication::class)
		);
	}

	/**
	 * Get the `model.table` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  TableModel
	 */
	public function getModelTableService(Container $container): TableModel
	{
		return new TableModel($container->get(DatabaseInterface::class));
	}

	/**
	 * Get the `model.edit` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  EditModel
	 */
	public function getModelEditService(Container $container): EditModel
	{
		return new EditModel($container->get(DatabaseInterface::class));
	}

	/**
	 * Get the `view.table.html` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  TableHtmlView
	 */
	public function getViewTableHtmlService(Container $container): TableHtmlView
	{
		$view = new TableHtmlView(
			$container->get('model.edit'),
			$container->get('model.table'),
			$container->get('renderer')
		);

		$view->setLayout('table.twig');

		return $view;
	}

	/**
	 * Get the `view.edit.html` service
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  EditHtmlView
	 */
	public function getViewEditHtmlService(Container $container): EditHtmlView
	{
		$view = new EditHtmlView(
			$container->get('model.edit'),
			$container->get('renderer')
		);

		$view->setLayout('edit.twig');

		return $view;
	}
}
