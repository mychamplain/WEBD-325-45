<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Site;

use Octoleo\CMS\Model\PageModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * HTML view class for the application
 */
class PageHtmlView extends HtmlView
{
	/**
	 * The active page
	 *
	 * @var  string
	 */
	private $page = '';

	/**
	 * The model object.
	 *
	 * @var  PageModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   PageModel          $model     The model object.
	 * @param   RendererInterface  $renderer  The renderer object.
	 */
	public function __construct(PageModel $model, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->model = $model;
	}

	/**
	 * Method to render the view
	 *
	 * @return  string  The rendered view
	 */
	public function render()
	{
        // get and set the data needed in the view
		$this->setData($this->model->getData($this->page));

		return parent::render();
	}

	/**
	 * Set the active page
	 *
	 * @param   string  $page  The active page name
	 *
	 * @return  void
	 */
	public function setPage(string $page): void
	{
		$this->page = $page;
	}
}
