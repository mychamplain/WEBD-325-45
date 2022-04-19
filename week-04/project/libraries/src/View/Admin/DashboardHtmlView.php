<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Admin;

use Octoleo\CMS\Model\DashboardModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Dashboard HTML view class for the application
 */
class DashboardHtmlView extends HtmlView
{
	/**
	 * The id of item/user/menu
	 *
	 * @var int
	 */
	private $id;

	/**
	 * The page model object.
	 *
	 * @var  DashboardModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   DashboardModel     $model       The page model object.
	 * @param   RendererInterface  $renderer    The renderer object.
	 */
	public function __construct(DashboardModel $model, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->model = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 */
	public function render(): string
	{
		$this->setData(['page' => $this->id]);
		return parent::render();
	}

	/**
	 * Set the active dashboard
	 *
	 * @param   string  $name  The active page name
	 *
	 * @return  void
	 */
	public function setActiveDashboard(string $name): void
	{
		$this->setLayout($this->model->getDashboard($name));
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
