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
use Laminas\Diactoros\Response\HtmlResponse;
use Octoleo\CMS\View\Page\HomepageHtmlView;

/**
 * Controller handling the site's homepage
 *
 * @method         \Octoleo\CMS\Application\SiteApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\SiteApplication  $app              Application object
 */
class HomepageController extends AbstractController
{
	/**
	 * The view object.
	 *
	 * @var  HomepageHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   HomepageHtmlView     $view      The view object.
	 * @param   Input                $input     The input object.
	 * @param   AbstractApplication  $app       The application object.
	 */
	public function __construct(HomepageHtmlView $view, Input $input = null, AbstractApplication $app = null)
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

		// check if there is a home page
		$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));

		return true;
	}
}
