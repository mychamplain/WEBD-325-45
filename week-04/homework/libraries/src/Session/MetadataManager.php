<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\Session;

use RuntimeException;
use Sport\Stars\User\User;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\Exception\ExecutionFailureException;
use Joomla\Database\ParameterType;
use Joomla\Session\SessionInterface;

/**
 * Manager for optional session metadata.
 *
 * @since  3.8.6
 * @internal
 */
final class MetadataManager
{
	/**
	 * Internal variable indicating a session record exists.
	 *
	 * @var    integer
	 * @since  4.0.0
	 * @note   Once PHP 7.1 is the minimum supported version this should become a private constant
	 */
	private static $sessionRecordExists = 1;

	/**
	 * Internal variable indicating a session record does not exist.
	 *
	 * @var    integer
	 * @since  4.0.0
	 * @note   Once PHP 7.1 is the minimum supported version this should become a private constant
	 */
	private static $sessionRecordDoesNotExist = 0;

	/**
	 * Internal variable indicating an unknown session record statue.
	 *
	 * @var    integer
	 * @since  4.0.0
	 * @note   Once PHP 7.1 is the minimum supported version this should become a private constant
	 */
	private static $sessionRecordUnknown = -1;

	/**
	 * Database driver.
	 *
	 * @var    DatabaseInterface
	 * @since  3.8.6
	 */
	private $db;

	/**
	 * MetadataManager constructor.
	 *
	 * @param   DatabaseInterface    $db   Database driver.
	 *
	 * @since   3.8.6
	 */
	public function __construct(DatabaseInterface $db)
	{
		$this->db  = $db;
	}

	/**
	 * Create the metadata record if it does not exist.
	 *
	 * @param   SessionInterface  $session  The session to create the metadata record for.
	 * @param   User              $user     The user to associate with the record.
	 *
	 * @return  void
	 *
	 * @since   3.8.6
	 * @throws  RuntimeException
	 */
	public function createRecordIfNonExisting(SessionInterface $session, User $user)
	{
		$exists = $this->checkSessionRecordExists($session->getId());

		// Only touch the database if the record does not already exist
		if ($exists !== self::$sessionRecordExists)
		{
			return;
		}

		$this->createSessionRecord($session, $user);
	}

	/**
	 * Create the metadata record if it does not exist.
	 *
	 * @param   SessionInterface  $session  The session to create or update the metadata record for.
	 * @param   User              $user     The user to associate with the record.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 * @throws  RuntimeException
	 */
	public function createOrUpdateRecord(SessionInterface $session, User $user)
	{
		$exists = $this->checkSessionRecordExists($session->getId());

		// Do not try to touch the database if we can't determine the record state
		if ($exists === self::$sessionRecordUnknown)
		{
			return;
		}

		if ($exists === self::$sessionRecordDoesNotExist)
		{
			$this->createSessionRecord($session, $user);

			return;
		}

		$this->updateSessionRecord($session, $user);
	}

	/**
	 * Delete records with a timestamp prior to the given time.
	 *
	 * @param   integer  $time  The time records should be deleted if expired before.
	 *
	 * @return  void
	 *
	 * @since   3.8.6
	 */
	public function deletePriorTo($time)
	{
		$query = $this->db->getQuery(true)
			->delete($this->db->quoteName('#__session'))
			->where($this->db->quoteName('time') . ' < :time')
			->bind(':time', $time, ParameterType::INTEGER);

		$this->db->setQuery($query);

		try
		{
			$this->db->execute();
		}
		catch (ExecutionFailureException $exception)
		{
			// Since garbage collection does not result in a fatal error when run in the session API, we don't allow it here either.
		}
	}

	/**
	 * Get session record exists
	 *
	 * @param   string  $sessionId  The session ID to check
	 *
	 * @return mixed  on success value for record presence
	 *
	 * @since   1.0.0
	 */
	public function getSessionRecord(string $sessionId)
	{
		$query = $this->db->getQuery(true)
			->select('*')
			->from($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = :session_id')
			->bind(':session_id', $sessionId)
			->setLimit(1);

		$this->db->setQuery($query);

		return $this->db->loadObject();
	}

	/**
	 * Check if the session record exists
	 *
	 * @param   string  $sessionId  The session ID to check
	 *
	 * @return  integer  Status value for record presence
	 *
	 * @since   4.0.0
	 */
	private function checkSessionRecordExists(string $sessionId): int
	{
		$query = $this->db->getQuery(true)
			->select($this->db->quoteName('session_id'))
			->from($this->db->quoteName('#__session'))
			->where($this->db->quoteName('session_id') . ' = :session_id')
			->bind(':session_id', $sessionId)
			->setLimit(1);

		$this->db->setQuery($query);

		try
		{
			$exists = $this->db->loadResult();
		}
		catch (ExecutionFailureException $e)
		{
			return self::$sessionRecordUnknown;
		}

		if ($exists)
		{
			return self::$sessionRecordExists;
		}

		return self::$sessionRecordDoesNotExist;
	}

	/**
	 * Create the session record
	 *
	 * @param   SessionInterface  $session  The session to create the metadata record for.
	 * @param   User              $user     The user to associate with the record.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	private function createSessionRecord(SessionInterface $session, User $user)
	{
		$query = $this->db->getQuery(true);

		$time = $session->isNew() ? time() : $session->get('session.timer.start');

		$columns = [
			$this->db->quoteName('session_id'),
			$this->db->quoteName('guest'),
			$this->db->quoteName('time'),
			$this->db->quoteName('userid'),
			$this->db->quoteName('username'),
		];

		// Add query placeholders
		$values = [
			':session_id',
			':guest',
			':time',
			':user_id',
			':username',
		];

		// Bind query values
		$sessionId   = $session->getId();
		$userIsGuest = $user->get('guest', 0);
		$userId      = $user->get('id', 0);
		$username    = $user->get('username', '');

		$query->bind(':session_id', $sessionId)
			->bind(':guest', $userIsGuest, ParameterType::INTEGER)
			->bind(':time', $time)
			->bind(':user_id', $userId, ParameterType::INTEGER)
			->bind(':username', $username);

		$query->insert($this->db->quoteName('#__session'))
			->columns($columns)
			->values(implode(', ', $values));

		$this->db->setQuery($query);

		try
		{
			$this->db->execute();
		}
		catch (ExecutionFailureException $e)
		{
			// This failure isn't critical, we can go on without the metadata
		}
	}

	/**
	 * Update the session record
	 *
	 * @param   SessionInterface  $session  The session to update the metadata record for.
	 * @param   User              $user     The user to associate with the record.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	private function updateSessionRecord(SessionInterface $session, User $user)
	{
		$query = $this->db->getQuery(true);

		$time = time();

		$setValues = [
			$this->db->quoteName('guest') . ' = :guest',
			$this->db->quoteName('time') . ' = :time',
			$this->db->quoteName('userid') . ' = :user_id',
			$this->db->quoteName('username') . ' = :username',
		];

		// Bind query values
		$sessionId   = $session->getId();
		$userIsGuest = $user->get('guest', 0);
		$userId      = $user->get('id', 0);
		$username    = $user->get('username', '');

		$query->bind(':session_id', $sessionId)
			->bind(':guest', $userIsGuest, ParameterType::INTEGER)
			->bind(':time', $time)
			->bind(':user_id', $userId, ParameterType::INTEGER)
			->bind(':username', $username);

		$query->update($this->db->quoteName('#__session'))
			->set($setValues)
			->where($this->db->quoteName('session_id') . ' = :session_id');

		$this->db->setQuery($query);

		try
		{
			$this->db->execute();
		}
		catch (ExecutionFailureException $e)
		{
			// This failure isn't critical, we can go on without the metadata
		}
	}
}