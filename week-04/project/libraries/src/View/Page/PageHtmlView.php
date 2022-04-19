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

use Octoleo\CMS\Model\MenuInterface;
use Octoleo\CMS\Model\PageInterface;
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
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   PageModel          $model     The page model object.
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
		// set the defaults
		$title = 'Error';
		$body = '';
		$menus = [];
		// get the page data
		if ($this->model instanceof PageInterface)
		{
			// get the page data
			$data = $this->model->getPageItemByPath($this->page);
			if (isset($data->id))
			{
				// set the title
				$title = $data->title;
				// check if we have intro text we add it to full text
				if (!empty($data->introtext))
				{
					// TODO: for now we just merge these
					$data->fulltext = $data->introtext . $data->fulltext;
				}
				// set the title
				$body = $data->fulltext;
			}
			else
			{
				throw new \RuntimeException('Trying to access a page that does not exit (' . $this->page . ')', 404);
			}
		}

		// set the menus if possible
		if ($this->model instanceof MenuInterface)
		{
			$menus = $this->model->getMenus();
		}

		$this->setData(
			[
				'main_menu' => $menus,
				'title' => $title,
				'body' => $body
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
}
