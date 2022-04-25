<?php
/**
 * @package    Octoleo CMS
 *
 * @created    20th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Admin;

use Octoleo\CMS\Model\UsergroupModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * HTML view class for the application
 */
class UsergroupHtmlView extends HtmlView
{
	/**
	 * The id
	 *
	 * @var int
	 */
	private $id;

	/**
	 * The model object.
	 *
	 * @var  UsergroupModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   UsergroupModel     $model      The model object.
	 * @param   RendererInterface  $renderer   The renderer object.
	 */
	public function __construct(UsergroupModel $model, RendererInterface $renderer)
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
		$this->setData([
			'form' => $this->model->getItem($this->id)
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
	 * Set the active id
	 *
	 * @param   int  $id  The active id
	 *
	 * @return  void
	 */
	public function setActiveId(int $id): void
	{
		$this->id = $id;
	}
}
