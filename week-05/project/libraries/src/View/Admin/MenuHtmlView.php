<?php
/**
 * @package    Octoleo CMS
 *
 * @created    18th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Admin;

use Octoleo\CMS\Model\MenuModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Dashboard HTML view class for the application
 */
class MenuHtmlView extends HtmlView
{
	/**
	 * The id of item/user/menu
	 *
	 * @var int
	 */
	private $id;

	/**
	 * The item model object.
	 *
	 * @var  MenuModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   MenuModel          $model      The page model object.
	 * @param   RendererInterface  $renderer   The renderer object.
	 */
	public function __construct(MenuModel $model, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->model = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 * @throws \Exception
	 */
	public function render(): string
	{
		$this->setData([
			'form' => $this->model->getItem($this->id),
			'items' => $this->model->getItems()
		]);
		return parent::render();
	}

	/**
	 * Set the active view
	 *
	 * @param   string  $name  The active view name
	 *
	 * @return  void
	 */
	public function setActiveView(string $name): void
	{
		$this->setLayout($this->model->setLayout($name));
	}

	/**
	 * Set the active page details
	 *
	 * @param   int  $id  The selected item/user/menu
	 *
	 * @return  void
	 */
	public function setActiveId(int $id): void
	{
		$this->id = $id;
	}
}
