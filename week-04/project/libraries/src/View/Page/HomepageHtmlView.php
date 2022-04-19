<?php
/**
 * @package    Octoleo CMS
 *
 * @created    18th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\View\Page;

use Octoleo\CMS\Model\MenuInterface;
use Octoleo\CMS\Model\HomepageModel;
use Joomla\Renderer\RendererInterface;
use Joomla\View\HtmlView;
use Octoleo\CMS\Model\PageInterface;

/**
 * Page HTML view class for the application
 */
class HomepageHtmlView extends HtmlView
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
	 * @var  HomepageModel
	 */
	private $model;

	/**
	 * Instantiate the view.
	 *
	 * @param   HomepageModel      $model     The page model object.
	 * @param   RendererInterface  $renderer  The renderer object.
	 */
	public function __construct(HomepageModel $model, RendererInterface $renderer)
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
		$title = 'Home';
		$body = '';
		$menus = [];
		// set the home page default template
		$this->setLayout('homepage.twig');
		// we check if we have a home page
		$home_page = $this->model->getHomePage();
		if ($this->model instanceof PageInterface && isset($home_page->item_id) && $home_page->item_id > 0)
		{
			// get the page data
			$data = $this->model->getPageItemById($home_page->item_id);
			if ($data->id)
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
				// set the page template
				$this->setLayout('page.twig');
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
