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

use Octoleo\CMS\Model\UserModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Dashboard HTML view class for the application
 */
class UserHtmlView extends HtmlView
{
	/**
	 * The id of user
	 *
	 * @var int
	 */
	private $id;

	/**
	 * The User model object.
	 *
	 * @var  UserModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   UserModel          $model  The page model object.
	 * @param   RendererInterface  $renderer   The renderer object.
	 */
	public function __construct(UserModel $model, RendererInterface $renderer)
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
		$this->setData(['form' => $this->model->getItem($this->id)]);
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
	 * @param   int  $id  The selected user
	 *
	 * @return  void
	 */
	public function setActiveId(int $id): void
	{
		$this->id = $id;
	}
}
