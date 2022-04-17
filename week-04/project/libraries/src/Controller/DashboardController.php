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
use Laminas\Diactoros\Response\RedirectResponse;
use Octoleo\CMS\View\Admin\DashboardHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's homepage
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication  $app              Application object
 */
class DashboardController extends AbstractController
{
	/**
	 * The view object.
	 *
	 * @var  DashboardHtmlView
	 */
	private $view;

	/**
	 * Constructor.
	 *
	 * @param   DashboardHtmlView    $view      The view object.
	 * @param   Input                $user      The user object.
	 * @param   Input                $input     The input object.
	 * @param   AbstractApplication  $app       The application object.
	 */
	public function __construct(DashboardHtmlView $view, Input $input = null, AbstractApplication $app = null)
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
		// our little access controller TODO: we can do better
		$has_access = false;

		// Enable browser caching
		$this->getApplication()->allowCache(true);

		$dashboard = $this->getInput()->getString('dashboard', '');
		$id = $this->getInput()->getInt('id', 0);

		$this->view->setActiveDashboard($dashboard);
		$this->view->setActiveId($id);

		/** @var \Octoleo\CMS\User\UserFactory $userFactory */
		$userFactory = $this->getApplication()->getUserFactory();

		// user actions [access, signup]
		if ('access' === $dashboard || 'signup' === $dashboard || 'logout' === $dashboard)
		{
			if ('access' === $dashboard && $userFactory->login())
			{
				$has_access = true;
			}
			elseif ('signup' === $dashboard && $userFactory->create())
			{
				$has_access = true;
			}
			elseif ('logout' === $dashboard && $userFactory->logout())
			{
				$has_access = false;
			}

			// we by default always load the dashboard
			$this->view->setActiveDashboard('dashboard');
		}
		elseif ($userFactory->active())
		{
			$has_access = true;
		}

		if ($has_access)
		{
			$this->getApplication()->setResponse(new HtmlResponse($this->view->render()));
		}
		else
		{
			// get uri request to get host
			$uri = new Uri($this->getApplication()->get('uri.request'));

			// Redirect to the administrator area
			$this->getApplication()->redirect($uri->getScheme() . '://' . $uri->getHost() . '/administrator/');
		}

		return true;
	}
}
