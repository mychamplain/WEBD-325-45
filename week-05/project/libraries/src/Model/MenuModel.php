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
use Joomla\String\StringHelper;
use Octoleo\CMS\Date\Date;

/**
 * Model class for menu item
 */
class MenuModel implements DatabaseModelInterface
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
	 * @param   string  $title
	 * @param   string  $alias
	 * @param   int     $itemId
	 * @param   string  $path
	 * @param   int     $published
	 * @param   string  $publishUp
	 * @param   string  $publishDown
	 * @param   string  $position
	 * @param   int     $home
	 *
	 * @return  int
	 * @throws \Exception
	 */
	public function setItem(
		int    $id,
		string $title,
		string $alias,
		int    $itemId,
		string $path,
		int    $published,
		string $publishUp,
		string $publishDown,
		string $position,
		int    $home): int
	{
		$db = $this->getDb();

		// set the path if not set
		$this->setPathAlias($id, $title, $path, $alias);

		$data = [
			'title'        => (string) $title,
			'alias'        => (string) $alias,
			'path'         => (string) $path,
			'item_id'      => (int) $itemId,
			'published'    => (int) $published,
			'publish_up'   => (string) (empty($publishUp)) ? '0000-00-00 00:00:00' : (new Date($publishUp))->toSql(),
			'publish_down' => (string) (empty($publishDown)) ? '0000-00-00 00:00:00' : (new Date($publishDown))->toSql(),
			'home'         => (int) $home,
			'parent_id'    => 0 // only root items for now
		];

		// we set position in params
		$data['params'] = json_encode(['position' => $position]);

		// if we have ID update
		if ($id > 0)
		{
			$data['id'] = (int) $id;
			// change to object
			$data = (object) $data;

			try
			{
				$db->updateObject('#__menu', $data, 'id');
			}
			catch (\RuntimeException $exception)
			{
				throw new \RuntimeException($exception->getMessage(), 404);
			}
		}
		else
		{
			// change to object
			$data = (object) $data;

			try
			{
				$db->insertObject('#__menu', $data);
			}
			catch (\RuntimeException $exception)
			{
				throw new \RuntimeException($exception->getMessage(), 404);
			}

			$id = $db->insertid();
		}

		// check if we have another home set
		if ($data->home == 1)
		{
			$this->setHome($id);
		}

		return $id;
	}

	/**
	 * Get all published items
	 *
	 * @return  array
	 */
	public function getItems(): array
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select($db->quoteName(array('id', 'title')))
			->from($db->quoteName('#__item'))
			->where('state = 1');

		return $db->setQuery($query)->loadObjectList('id');
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
		$default->published  = 1;
		$default->home       = 0;

		// we return the default if id not correct
		if (!is_numeric($id))
		{
			return $default;
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__menu'))
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
			// set the position
			$result->params = json_decode($result->params);

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
			->delete($db->quoteName('#__menu'))
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

	/**
	 * Make sure that we have only one home
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	private function setHome($id): bool
	{
		$db = $this->getDb();
		// Purge the session
		$query = $db->getQuery(true)
			->update($db->quoteName('#__menu'))
			->set($db->quoteName('home') . ' = 0')
			->where($db->quoteName('id') . ' != :id')
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

	/**
	 * @param   int     $id
	 * @param   string  $title
	 * @param   string  $path
	 * @param   string  $alias
	 */
	private function setPathAlias(int $id, string $title, string &$path, string &$alias)
	{
		$alias = (empty($alias)) ? $title : $alias;
		$alias = preg_replace('/\s+/', '-', strtolower(preg_replace("/[^A-Za-z0-9\- ]/", '', $alias)));
		// TODO: we will only have root menus for now, no sub-menus
		$seeker  = $alias;
		$pointer = 2;
		while ($this->exist($id, $seeker))
		{
			$seeker = $alias . '-' . $pointer;
			$pointer++;
		}
		// update the path
		$alias = $seeker;
		$path = $seeker;
	}

	/**
	 * Check if an alias exist
	 *
	 * @param   int     $id
	 * @param   string  $alias
	 *
	 * @return bool
	 */
	private function exist(int $id, string $alias): bool
	{
		$db = $this->getDb();
		$query = $db->getQuery(true)
			->select('id')
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('alias') . ' = :alias')
			->bind(':alias', $alias)
			->setLimit(1);

		// only add the id item exist
		if ($id > 0)
		{
			$query
				->where($db->quoteName('id') . ' != :id')
				->bind(':id', $id, ParameterType::INTEGER);
		}

		try
		{
			$id = $db->setQuery($query)->loadResult();
		}
		catch (\RuntimeException $e)
		{
			// we ignore this and just return an empty object
		}

		if (isset($id) && $id > 0)
		{
			return true;
		}
		return false;
	}
}
