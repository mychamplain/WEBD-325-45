<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\User;

use Joomla\Authentication\AuthenticationStrategyInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\Authentication\Password\BCryptHandler;
use Joomla\Filter\InputFilter as InputFilterAlias;
use Joomla\String\StringHelper;
use Octoleo\CMS\Application\AdminApplication;
use Octoleo\CMS\Date\Date;
use Octoleo\CMS\Factory;
use Octoleo\CMS\Filter\InputFilter;
use Octoleo\CMS\Session\MetadataManager;

/**
 * Default factory for creating User objects
 *
 * @since  1.0.0
 * source: https://github.com/joomla/joomla-cms/blob/4.2-dev/libraries/src/User/UserFactory.php
 */
class UserFactory implements UserFactoryInterface
{
	/**
	 * The database.
	 *
	 * @var  DatabaseInterface
	 */
	private $db;

	/**
	 * @var AuthenticationStrategyInterface
	 */
	private $authentication;

	/**
	 * @var InputFilter
	 */
	private $inputFilter;

	/**
	 * @var string[]
	 */
	private $userFilter = [
		'name' => 'STRING',
	    'username' => 'USERNAME',
		'email' => 'STRING',
		'password' => 'RAW',
		'password2' => 'RAW'
	];
	/**
	 * @var BCryptHandler
	 */
	private $secure;

	/**
	 * UserFactory constructor.
	 *
	 * @param   DatabaseInterface|null                $db  The database
	 * @param   AuthenticationStrategyInterface|null  $authentication
	 *
	 * @throws \Exception
	 */
	public function __construct(
		DatabaseInterface $db = null,
		AuthenticationStrategyInterface $authentication = null,
		BCryptHandler $secure = null)
	{
		$this->db             = ($db) ?: Factory::getApplication()->get(DatabaseInterface::class);
		$this->authentication = ($authentication) ?: Factory::getApplication()->get(AuthenticationStrategyInterface::class);
		$this->secure         = ($secure) ?: new BCryptHandler();
	}

	/**
	 * Method to get an instance of a user for the given id.
	 *
	 * @param   int  $id  The id
	 *
	 * @return  User
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function loadUserById(int $id): User
	{
		return new User($id);
	}

	/**
	 * Method to get an instance of a user for the given username.
	 *
	 * @param   string  $username  The username
	 *
	 * @return  User
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function loadUserByUsername(string $username): User
	{
		// Initialise some variables
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('id'))
			->from($this->db->quoteName('#__users'))
			->where($this->db->quoteName('username') . ' = :username')
			->bind(':username', $username)
			->setLimit(1);
		$this->db->setQuery($query);

		return $this->loadUserById((int) $this->db->loadResult());
	}

	/**
	 * Check if user is active
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function active(): bool
	{
		/** @var \Octoleo\CMS\Application\AdminApplication $application */
		$application = Factory::getApplication();

		$session = $application->getSession();

		// Grab the current session ID
		$sessionId = $session->getId();

		/** @var \Octoleo\CMS\Session\MetadataManager $manager */
		$manager = Factory::getContainer()->get(MetadataManager::class);

		$active = $manager->getSessionRecord($sessionId);

		// if no active session is found...
		if (!$active)
		{
			return false;
		}

		// if user above 0 the active
		if ($active->guest == 0 && $active->userid > 0)
		{
			return true;
		}

		// Purge the session
		$query = $this->db->getQuery(true)
			->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = :sessionid')
			->bind(':sessionid', $sessionId);
		try
		{
			$this->db->setQuery($query)->execute();
		}
		catch (\RuntimeException $e)
		{
			// The old session is already invalidated, don't let this block logging in
		}

		// destroy session
		$session->destroy();

		// very basic for now....
		return false;
	}

	/**
	 * Check if a user exist based on give key value pair
	 *
	 * @param   string  $value
	 * @param   string  $key
	 *
	 * @return false|mixed  on success return user ID
	 */
	public function exist(string $value, string $key = 'username')
	{
		try
		{
			$id = $this->db->setQuery(
				$this->db->getQuery(true)
					->select($this->db->quoteName('id'))
					->from($this->db->quoteName('#__users'))
					->where($this->db->quoteName($key) . ' = ?')
					->bind(1, $value)
			)->loadResult();
		}
		catch (\RuntimeException $exception)
		{
			return false;
		}

		if ($id > 0)
		{
			return $id;
		}
		return false;
	}

	/**
	 * Attempt to authenticate the username and password pair.
	 *
	 * @return  string|boolean  A string containing a username if authentication is successful, false otherwise.
	 *
	 * @since   1.1.0
	 */
	public function authenticate()
	{
		return $this->authentication->authenticate();
	}

	/**
	 * Attempt to login user
	 *
	 * @return  boolean  true on success
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function login(): bool
	{
		/** @var \Octoleo\CMS\Application\AdminApplication $application */
		$application = Factory::getApplication();

		if (($username = $this->authenticate()) !== false)
		{
			$user = $this->loadUserByUsername($username);

			// If loadUserByUsername returned an error, then pass it back.
			if ($user instanceof \Exception)
			{
				$application->enqueueMessage('Login failure', 'Error');

				return false;
			}

			// If loadUserByUsername returned an error, then pass it back.
			if ($user->get('block', true))
			{
				$application->enqueueMessage('Login failure, user is blocked.', 'Warning');

				return false;
			}

			return $this->setUserSession($application, $user->toArray());
		}
		// set authentication failure message
		$application->enqueueMessage('Login failure, please try again.', 'Warning');

		return false;
	}

	/**
	 * Logout user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function logout(): bool
	{
		/** @var \Octoleo\CMS\Application\AdminApplication $application */
		$application = Factory::getApplication();

		$session = $application->getSession();

		// Grab the current session ID
		$sessionId = $session->getId();

		// Purge the session
		$query = $this->db->getQuery(true)
			->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = :sessionid')
			->bind(':sessionid', $sessionId);
		try
		{
			$this->db->setQuery($query)->execute();
		}
		catch (\RuntimeException $e)
		{
			// The old session is already invalidated, don't let this block logging in
		}

		// destroy session
		$session->destroy();

		// very basic for now....
		return true;
	}

	/**
	 * Attempt to great user
	 *
	 * @param   string|null  $name
	 * @param   string|null  $username
	 * @param   string|null  $email
	 * @param   string|null  $password
	 * @param   string|null  $password2
	 *
	 * @return  boolean  true on success
	 *
	 * @throws \Exception
	 * @since   1.0.0
	 */
	public function create(
		string $name = null,
		string $username = null,
		string $email = null,
		string $password = null,
		string $password2 = null): bool
	{
		/** @var \Octoleo\CMS\Application\AdminApplication $application */
		$application = Factory::getApplication();

		/** @var \Joomla\Input\Input $input */
		$input = $application->getInput();

		$user = [];
		$user['name']      = ($name) ?: $input->getString('name', '');
		$user['username']  = ($username) ?: $input->getString('username', '');
		$user['email']     = ($email) ?: $input->getString('email', '');
		$user['password']  = ($password) ?: $input->getString('password', '');
		$user['password2'] = ($password2) ?: $input->getString('password2', '');

		// check if username exist
		if (!empty($user['username']) && $this->exist($user['username']))
		{
			$application->enqueueMessage('Username already exist, try another username.', 'Warning');

			return false;
		}
		// check if email exist
		if (!empty($user['email']) && $this->exist($user['email'], 'email'))
		{
			$application->enqueueMessage('Email already exist, try another email.', 'Warning');

			return false;
		}

		// load our filter
		$this->inputFilter = InputFilter::getInstance(
			[],
			[],
			InputFilterAlias::ONLY_BLOCK_DEFINED_TAGS,
			InputFilterAlias::ONLY_BLOCK_DEFINED_ATTRIBUTES
		);

		// check that we have all the values set
		$valid = true;
		foreach ($user as $key => $detail)
		{
			// check if its empty
			if (empty($detail))
			{
				$valid = false;
				$application->enqueueMessage($key . ' is required', 'error');
			}
			// check if its valid
			if (!$this->valid($key, $detail))
			{
				$valid = false;
				$application->enqueueMessage($key . ' is not valid', 'error');
			}
		}

		// check passwords TODO: check that we have a valid email
		if (isset($user['password2']) && $user['password'] != $user['password2'])
		{
			$valid = false;
			$application->enqueueMessage('Passwords do not match', 'error');
		}
		unset ($user['password2']);

		// continue only if valid
		if ($valid)
		{
			// hash the password
			$user['password'] = $this->secure->hashPassword($user['password']);

			// set the registration date
			$user['registerDate'] = (new Date())->toSql();

			// set other defaults for now
			$user['sendEmail'] = 1;
			$user['block'] = 0;
			$user['params'] = '';

			$insert = (object) $user;

			try
			{
				// Insert the user
				$result = $this->db->insertObject('#__users', $insert, 'id');
			}
			catch (\RuntimeException $exception)
			{
				throw new \RuntimeException($exception->getMessage(), 404);

				return false;
			}

			// only set session if success
			if ($result)
			{
				$user['id'] = $this->db->insertid();
				return $this->setUserSession($application, $user);
			}
		}
		return false;
	}

	/**
	 * Attempt validate user input (BASIC)
	 *
	 * @param   string  $key
	 * @param   string  $detail
	 *
	 * @return bool
	 */
	private function valid(string $key, string $detail): bool
	{
		if (isset($this->userFilter[$key]))
		{
			$valid = $this->inputFilter->clean($detail, $this->userFilter[$key]);

			if (StringHelper::strcmp($valid, $detail) == 0)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * @param   AdminApplication  $application
	 * @param   array             $user
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private function setUserSession(AdminApplication $application, array $user): bool
	{
		$session = $application->getSession();

		// Grab the current session ID
		$oldSessionId = $session->getId();

		// Fork the session
		$session->fork();

		// Register the needed session variables
		$session->set('user', $user);

		// Purge the old session
		$query = $this->db->getQuery(true)
			->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = :sessionid')
			->bind(':sessionid', $oldSessionId);
		try
		{
			$this->db->setQuery($query)->execute();
		}
		catch (\RuntimeException $e)
		{
			// The old session is already invalidated, don't let this block logging in
		}

		/** @var \Octoleo\CMS\Session\MetadataManager $manager */
		$manager = Factory::getContainer()->get(MetadataManager::class);

		$manager->createOrUpdateRecord($session, $this->loadUserById($user['id']));

		return true;
	}
}
