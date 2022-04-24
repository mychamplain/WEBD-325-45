<?php
/**
 * @package    Sport Stars
 *
 * @created    19th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Sport\Stars\Model;

use Exception;
use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;
use Joomla\Model\DatabaseModelInterface;
use Joomla\Model\DatabaseModelTrait;
use RuntimeException;
use stdClass;

/**
 * Model class for items
 */
class EditModel implements DatabaseModelInterface
{
	use DatabaseModelTrait;

	const MSG_INFO = 'info';

	/**
	 * @var array
	 */
	public $tempItem;

	/**
	 * @var array
	 */
	private $messages = [];

	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $db  The database adapter.
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->setDb($db);
	}

	/**
	 * Add an item
	 *
	 * @param   int     $id
	 * @param   string  $name
	 * @param   int     $age
	 * @param   string  $sport
	 *
	 * @return  int
	 * @throws Exception
	 */
	public function setItem(
		int    $id,
		string $name,
		int    $age,
		string $sport): int
	{
		$db = $this->getDb();

		$data = [
			'name'  => (string) $name,
			'age'   => (int) $age,
			'sport' => (string) $sport
		];

		// if we have ID update
		if ($id > 0)
		{
			$data['id'] = (int) $id;
			// change to object
			$data = (object) $data;

			try
			{
				$db->updateObject('#__sportstars', $data, 'id');
			}
			catch (RuntimeException $exception)
			{
				throw new RuntimeException($exception->getMessage(), 404);
			}

			return $id;

		}
		else
		{
			// change to object
			$data = (object) $data;

			try
			{
				$db->insertObject('#__sportstars', $data);
			}
			catch (RuntimeException $exception)
			{
				throw new RuntimeException($exception->getMessage(), 404);
			}

			return $db->insertid();
		}
	}

	/**
	 * Get an item
	 *
	 * @param   int|null  $id
	 *
	 * @return stdClass
	 * @throws Exception
	 */
	public function getItem(?int $id): stdClass
	{
		$db = $this->getDb();
		// default object (use posted values if set)
		if (is_array($this->tempItem))
		{
			$default = (object) $this->tempItem;
		}
		else
		{
			$default = new \stdClass();
		}
		// to be sure ;)
		$default->post_key   = "?task=create";

		// we return the default if id not correct
		if (!is_numeric($id))
		{
			return $default;
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__sportstars'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER)
			->setLimit(1);

		try
		{
			$result = $db->setQuery($query)->loadObject();
		}
		catch (RuntimeException $e)
		{
			// we ignore this and just return an empty object
		}

		if (isset($result) && $result instanceof stdClass)
		{
			$result->post_key   = "?id=$id&task=edit";
			return $result;
		}

		return $default;
	}

	/**
	 * @param   int  $id
	 *
	 * @return bool
	 */
	public function delete(int $id): bool
	{
		$db = $this->getDb();
		// Purge the session
		$query = $db->getQuery(true)
			->delete($db->quoteName('#__sportstars'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (RuntimeException $e)
		{
			// delete failed
			return false;
		}

		return true;
	}

	/**
	 * Enqueue a system message.
	 *
	 * @param   string  $message  The message to enqueue.
	 * @param   string  $type     The message type. Default is message.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function enqueueMessage(string $message, string $type = self::MSG_INFO)
	{
		// Don't add empty messages.
		if (empty($message) || trim($message) === '')
		{
			return;
		}

		if (!\in_array($message, $this->messages))
		{
			// Enqueue the message.
			$this->messages[] = ['type' => $type, 'message' => $message];
		}
	}

	/**
	 * Get the message queue.
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   1.0.0
	 */
	public function getMessageQueue(): array
	{
		// Get messages
		return $this->messages;
	}
}
