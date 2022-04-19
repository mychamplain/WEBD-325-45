<?php
/**
 * @package    Octoleo CMS
 *
 * @created    18th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Model;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\ParameterType;
use Joomla\Model\DatabaseModelInterface;
use Joomla\Model\DatabaseModelTrait;
use Octoleo\CMS\Date\Date;

/**
 * Model class for items
 */
class UserModel implements DatabaseModelInterface
{
	use DatabaseModelTrait;

	/**
	 * @var array
	 */
	public $tempItem;

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
	 * @param   string  $username
	 * @param   string  $email
	 * @param   string  $password
	 * @param   int     $block
	 * @param   int     $sendEmail
	 * @param   string  $registerDate
	 * @param   int     $activation
	 *
	 * @return  int
	 * @throws \Exception
	 */
	public function setItem(
		int    $id,
		string $name,
		string $username,
		string $email,
		string $password,
		int    $block,
		int    $sendEmail,
		string $registerDate,
		int    $activation): int
	{
		$db = $this->getDb();

		$data = [
			'name'         => (string) $name,
			'username'     => (string) $username,
			'email'        => (string) $email,
			'block'        => (int) $block,
			'sendEmail'    => (int) $sendEmail,
			'registerDate' => (string) (empty($registerDate)) ? (new Date())->toSql() : (new Date($registerDate))->toSql(),
			'activation'   => (int) $activation
		];

		// only update password if set
		if (!empty($password) && strlen($password) > 6)
		{
			$data['password'] = (string) $password;
		}

		// if we have ID update
		if ($id > 0)
		{
			$data['id'] = (int) $id;
			// we remove registration date when we update the user
			unset($data['registerDate']);
			// change to object
			$data = (object) $data;

			try
			{
				$db->updateObject('#__users', $data, 'id');
			}
			catch (\RuntimeException $exception)
			{
				throw new \RuntimeException($exception->getMessage(), 404);
			}

			return $id;

		}
		else
		{
			// change to object
			$data = (object) $data;

			try
			{
				$db->insertObject('#__users', $data);
			}
			catch (\RuntimeException $exception)
			{
				throw new \RuntimeException($exception->getMessage(), 404);
			}

			return $db->insertid();
		}
	}

	/**
	 * Get an item
	 *
	 * @param   int|null  $id
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function getItem(?int $id): \stdClass
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
		$default->today_date = (new Date())->toSql();
		$default->post_key   = "?task=create";
		$default->block      = 0;
		$default->activation = 1;
		$default->sendEmail  = 1;
		// always remove password
		$default->password = 'xxxxxxxxxx';
		$default->password2 = 'xxxxxxxxxx';

		// we return the default if id not correct
		if (!is_numeric($id))
		{
			return $default;
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__users'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER)
			->setLimit(1);

		try
		{
			$result = $db->setQuery($query)->loadObject();
		}
		catch (\RuntimeException $e)
		{
			// we ignore this and just return an empty object
		}

		if (isset($result) && $result instanceof \stdClass)
		{
			$result->post_key   = "?id=$id&task=edit";
			$result->today_date = $default->today_date;
			// always remove password
			$result->password = $default->password;
			$result->password2 = $default->password2;

			return $result;
		}

		return $default;
	}

	/**
	 * @param   string  $name
	 *
	 * @return string
	 */
	public function setLayout(string $name): string
	{
		return $name . '.twig';
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
			->delete($db->quoteName('#__users'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $id, ParameterType::INTEGER);
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (\RuntimeException $e)
		{
			// delete failed
			return false;
		}

		return true;
	}
}
