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
use Joomla\Filter\InputFilter as InputFilterAlias;
use Joomla\Input\Input;
use Octoleo\CMS\Date\Date;
use Joomla\Authentication\Password\BCryptHandler;
use Octoleo\CMS\Factory;
use Octoleo\CMS\Filter\InputFilter;
use Octoleo\CMS\Model\UserModel;
use Octoleo\CMS\User\UserFactoryInterface;
use Octoleo\CMS\View\Admin\UserHtmlView;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Controller handling the site's dashboard
 *
 * @method         \Octoleo\CMS\Application\AdminApplication  getApplication()  Get the application object.
 * @property-read  \Octoleo\CMS\Application\AdminApplication $app              Application object
 */
class UserController extends AbstractController implements AccessInterface, CheckTokenInterface
{
	use AccessTrait, CheckTokenTrait;

	/**
	 * The view object.
	 *
	 * @var  UserHtmlView
	 */
	private $view;

	/**
	 * The model object.
	 *
	 * @var  UserModel
	 */
	private $model;

	/**
	 * @var InputFilter
	 */
	private $inputFilter;

	/**
	 * @var BCryptHandler
	 */
	private $secure;

	/**
	 * Constructor.
	 *
	 * @param   UserModel                 $model  The model object.
	 * @param   UserHtmlView              $view   The view object.
	 * @param   Input|null                $input  The input object.
	 * @param   AbstractApplication|null  $app    The application object.
	 */
	public function __construct(UserModel $model, UserHtmlView $view, Input $input = null, AbstractApplication $app = null, BCryptHandler $secure = null)
	{
		parent::__construct($input, $app);

		$this->model       = $model;
		$this->view        = $view;
		$this->inputFilter = InputFilter::getInstance(
			[],
			[],
			InputFilterAlias::ONLY_BLOCK_DEFINED_TAGS,
			InputFilterAlias::ONLY_BLOCK_DEFINED_ATTRIBUTES
		);
		$this->secure     = ($secure) ?: new BCryptHandler();
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

		$method = $this->getInput()->getMethod();
		$task   = $this->getInput()->getString('task', '');
		$id     = $this->getInput()->getInt('id', 0);

		// if task is delete
		if ('delete' === $task)
		{
			/** @var \Octoleo\CMS\User\User $user */
			$user = Factory::getContainer()->get(UserFactoryInterface::class)->getUser();
			// check that the user does not delete him/her self
			$user_id = $user->get('id', -1);
			if ($user_id == $id)
			{
				$this->getApplication()->enqueueMessage('You can not delete your own account!', 'warning');
			}
			elseif ($this->model->delete($id))
			{
				$this->getApplication()->enqueueMessage('User was deleted!', 'success');
			}
			else
			{
				$this->getApplication()->enqueueMessage('User could not be deleted!', 'error');
			}
			// go to set page
			$this->_redirect('users');

			return true;
		}

		if ('POST' === $method)
		{
			$id = $this->setItem();
		}

		$this->view->setActiveId($id);
		$this->view->setActiveView('user');

		// check if user is allowed to access
		if ($this->allow('user'))
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

	/**
	 * Set an item
	 *
	 * @return  int
	 * @throws \Exception
	 */
	protected function setItem(): int
	{
		// always check the post token
		$this->checkToken();
		// get the post
		$post = $this->getInput()->getInputForRequestMethod();

		// we get all the needed items
		$tempItem               = [];
		$tempItem['id']         = $post->getInt('user_id', 0);
		$tempItem['name']       = $post->getString('name', '');
		$tempItem['username']   = $post->getUsername('username', '');
		$tempItem['password']   = $post->getString('password', '');
		$tempItem['password2']   = $post->getString('password2', '');
		$tempItem['email']      = $post->getString('email', '');
		$tempItem['block']      = $post->getInt('block', 1);
		$tempItem['sendEmail']  = $post->getInt('sendEmail', 1);
		$tempItem['activation'] = $post->getInt('activation', 0);

		$can_save = true;
		// check that we have a name
		if (empty($tempItem['name']))
		{
			// we show a warning message
			$tempItem['name'] = '';
			$this->getApplication()->enqueueMessage('Name field is required.', 'error');
			$can_save = false;
		}
		// check that we have a username
		if (empty($tempItem['username']))
		{
			// we show a warning message
			$tempItem['username'] = '';
			$this->getApplication()->enqueueMessage('Username field is required.', 'error');
			$can_save = false;
		}
		// check that we have an email TODO: check that we have a valid email
		if (empty($tempItem['email']))
		{
			// we show a warning message
			$tempItem['email'] = '';
			$this->getApplication()->enqueueMessage('Email field is required.', 'error');
			$can_save = false;
		}
		// check passwords
		if (isset($tempItem['password2']) && $tempItem['password'] != $tempItem['password2'])
		{
			// we show a warning message
			$tempItem['password'] = 'xxxxxxxxxx';
			$tempItem['password2'] = 'xxxxxxxxxx';
			$this->getApplication()->enqueueMessage('Passwords do not match.', 'error');
			$can_save = false;
		}
		unset ($tempItem['password2']);
		// do not set password that has not changed
		if ($tempItem['password'] === 'xxxxxxxxxx')
		{
			if ($tempItem['id'] == 0)
			{
				// we show a warning message
				$tempItem['password'] = 'xxxxxxxxxx';
				$tempItem['password2'] = 'xxxxxxxxxx';
				$this->getApplication()->enqueueMessage('Passwords not set.', 'error');
				$can_save = false;
			}
			else
			{
				$tempItem['password'] = '';
			}
		}
		elseif (strlen($tempItem['password']) < 7)
		{
			// we show a warning message
			$tempItem['password'] = 'xxxxxxxxxx';
			$tempItem['password2'] = 'xxxxxxxxxx';
			$this->getApplication()->enqueueMessage('Passwords must be longer than 6 characters.', 'error');
			$can_save = false;
		}
		else
		{
			// hash the password
			$tempItem['password'] = $this->secure->hashPassword($tempItem['password']);
		}

		// can we save the item
		if ($can_save)
		{
			/** @var \Octoleo\CMS\User\User $user */
			$user = Factory::getContainer()->get(UserFactoryInterface::class)->getUser();
			$today = (new Date())->toSql();

			// check that the user does not block him/her self
			$user_id = $user->get('id', -1);
			if ($user_id == $tempItem['id'] && $tempItem['block'] == 1)
			{
				// don't allow user to block self
				$this->getApplication()->enqueueMessage('You can not block yourself!', 'warning');
				$tempItem['block'] = 0;
			}

			return $this->model->setItem(
				$tempItem['id'],
				$tempItem['name'],
				$tempItem['username'],
				$tempItem['email'],
				$tempItem['password'],
				$tempItem['block'],
				$tempItem['sendEmail'],
				$today,
				$tempItem['activation']);
		}

		// add to model the post values
		$this->model->tempItem = $tempItem;

		return $tempItem['id'];
	}
}
