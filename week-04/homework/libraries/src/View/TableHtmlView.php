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

use Sport\Stars\Model\EditModel;
use Sport\Stars\Model\TableModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Page HTML view class for the application
 */
class TableHtmlView extends HtmlView
{
	/**
	 * The edit model object.
	 *
	 * @var  EditModel
	 */
	private $model;

	/**
	 * The table model object.
	 *
	 * @var  TableModel
	 */
	private $tableModel;

	/**
	 * Instantiate the view.
	 *
	 * @param   EditModel          $model       The edit model object.
	 * @param   TableModel         $tableModel  The table model object.
	 * @param   RendererInterface  $renderer    The renderer object.
	 */
	public function __construct(EditModel $model, TableModel $tableModel, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->tableModel = $tableModel;
		$this->model      = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 */
	public function render()
	{
		$this->setData(
			[
				'list'           => $this->tableModel->getTable(),
				'messages_queue' => $this->model->getMessageQueue()
			]
		);

		return parent::render();
	}
}
