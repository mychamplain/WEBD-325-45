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
use Octoleo\CMS\Controller\Util\AccessInterface;
use Octoleo\CMS\Controller\Util\AccessTrait;
use Octoleo\CMS\Controller\Util\CheckTokenInterface;
use Octoleo\CMS\Controller\Util\CheckTokenTrait;
use Octoleo\CMS\Factory;
use Octoleo\CMS\User\User;
use Octoleo\CMS\User\UserFactoryInterface;
use Octoleo\CMS\View\Admin\UsersHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the requests
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication $app              Application object
 */
class UsersController extends AbstractController implements AccessInterface, CheckTokenInterface
{
	use AccessTrait, CheckTokenTrait;

	/**
	 * The view object.
	 *
	 * @var  UsersHtmlView
	 */
	private $view;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * Constructor.
	 *
	 * @param   UsersHtmlView        $view   The view object.
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 */
	public function __construct(
		UsersHtmlView       $view,
		Input               $input = null,
		AbstractApplication $app = null,
		User                $user = null)
	{
		parent::__construct($input, $app);

		$this->view = $view;
		$this->user = ($user) ?: Factory::getContainer()->get(UserFactoryInterface::class)->getUser();
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

		$this->view->setActiveView('users');

		// check if user is allowed to access
		if ($this->allow('users') && $this->user->get('access.user.read', false))
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
