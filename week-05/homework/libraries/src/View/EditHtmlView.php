<?php
/**
 * @package    Sport Stars
 *
 * @created    19th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\View;

use Exception;
use Sport\Stars\Model\EditModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Page HTML view class for the application
 */
class EditHtmlView extends HtmlView
{
	/**
	 * The active item
	 *
	 * @var  int
	 */
	private $id = 0;

	/**
	 * The model object.
	 *
	 * @var  EditModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   EditModel          $model     The page model object.
	 * @param   RendererInterface  $renderer  The renderer object.
	 */
	public function __construct(EditModel $model, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->model = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 * @throws Exception
	 */
	public function render()
	{
		$this->setData(
			[
				'form' => $this->model->getItem($this->id),
				'messages_queue' => $this->model->getMessageQueue()
			]
		);

		return parent::render();
	}

	/**
	 * Set the active item
	 *
	 * @param   string  $id  The active item
	 *
	 * @return  void
	 */
	public function setActiveItem(int $id): void
	{
		$this->id = $id;
	}
}
