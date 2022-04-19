<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Controller;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Joomla\Uri\Uri;
use Octoleo\CMS\View\Page\PageHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Controller handling the site's simple text pages
 *
 * @method         \Octoleo\CMS\Application\SiteApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\SiteApplication  $app              Application object
 */
class PageController extends AbstractController
{
	/**
	 * The view object.
	 *
	 * @var  PageHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   PageHtmlView         $view      The view object.
	 * @param   Input                $input     The input object.
	 * @param   AbstractApplication  $app       The application object.
	 */
	public function __construct(PageHtmlView $view, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->view = $view;
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 */
	public function execute(): bool
	{
		// Disable all cache for now
		$this->getApplication()->allowCache(false);

		$page = $this->getInput()->getString('view', '');
		$details = $this->getInput()->getString('details', '');

		// if for some reason the view value is administrator
		if ('administrator' === $page)
		{
			// get uri request to get host
			$uri = new Uri($this->getApplication()->get('uri.request'));

			// Redirect to the administrator area
			$this->getApplication()->setResponse(new RedirectResponse($uri->getScheme() . '://' . $uri->getHost() . '/administrator/', 301));
		}
		else
		{
			$this->view->setPage($page);

			$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));
		}

		return true;
	}
}
