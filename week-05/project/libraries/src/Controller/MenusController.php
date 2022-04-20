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
use Octoleo\CMS\View\Admin\MenusHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's dashboard
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication  $app              Application object
 */
class MenusController extends AbstractController implements AccessInterface, CheckTokenInterface
{
	use AccessTrait, CheckTokenTrait;

	/**
	 * The view object.
	 *
	 * @var  MenusHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   MenusHtmlView        $view   The view object.
	 * @param   Input                $user   The user object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(MenusHtmlView $view, Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->view = $view;
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean
	 * @throws \Exception
	 */
	public function execute(): bool
	{
		// Do not Enable browser caching
		$this->getApplication()->allowCache(false);

		$this->view->setActiveView('menus');

		// check if user is allowed to access
		if ($this->allow('menus'))
		{
			$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));
		}
		else
		{
			// go to set page
			$this->_redirect();
		}

		return true;
	}
}
