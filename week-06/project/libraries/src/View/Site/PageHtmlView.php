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
use Octoleo\CMS\Model\Util\MenuInterface;
use Octoleo\CMS\Model\Util\PageInterface;
use Octoleo\CMS\Model\Util\HomeMenuInterface;

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
		// set the defaults
		$title = 'Error';
		$body = '';
		$menus = [];
		// menu ID
		$menu_id = 0;
		$menu_home = false;
		// set home menu title (not ideal)
		$home_menu_title = 'Home';
		// we check if we have a home page
		if ($this->model instanceof HomeMenuInterface)
		{
			$home_page = $this->model->getHomePage();
			if (isset($home_page->title))
			{
				$home_menu_title = $home_page->title;
			}
		}
		// get the page data
		if ($this->model instanceof PageInterface)
		{
			// get the page data
			if (empty($this->page) && isset($home_page->item_id) && $home_page->item_id > 0)
			{
				// this is the home menu
				$data = $this->model->getPageItemById($home_page->item_id);
				$menu_home = true;
			}
			else
			{
				$data = $this->model->getPageItemByPath($this->page);
			}
			// check if we found any data
			if (isset($data->id))
			{
				// set the menu ID
				$menu_id = $data->menu_id;
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
			$menus = $this->model->getMenus($menu_id);
		}

		$this->setData(
			[
				'menus' => $menus,
				'home' => $menu_home,
				'menu_active' => $menu_id,
				'title' => $title,
				'home_menu_title' => $home_menu_title,
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
