<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Page;

use Octoleo\CMS\Model\PageModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;

/**
 * Page HTML view class for the application
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
	 * The active page details
	 *
	 * @var string
	 */
	private $details;

	/**
	 * The page model object.
	 *
	 * @var  PageModel
	 */
	private $pageModel;

	/**
	 * Instantiate the view.
	 *
	 * @param   PageModel          $pageModel     The page model object.
	 * @param   RendererInterface  $renderer         The renderer object.
	 */
	public function __construct(PageModel $pageModel, RendererInterface $renderer)
	{
		parent::__construct($renderer);

		$this->pageModel = $pageModel;
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
				'page' => $this->pageModel->getPage($this->page),
				'details' => $this->pageModel->getDetails($this->details)
			]
		);

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

	/**
	 * Set the active page details
	 *
	 * @param   string  $page  The active page name
	 *
	 * @return  void
	 */
	public function setDetails(string $details): void
	{
		$this->details = $details;
	}
}
