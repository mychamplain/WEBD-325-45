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
use Joomla\Model\DatabaseModelInterface;
use Joomla\Model\DatabaseModelTrait;
use Octoleo\CMS\Model\Util\GetUsergroupsInterface;
use Octoleo\CMS\Model\Util\GetUsergroupsTrait;

/**
 * Model class
 */
class UsersModel implements DatabaseModelInterface, GetUsergroupsInterface
{
	use DatabaseModelTrait, GetUsergroupsTrait;

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
	 * Get all items
	 *
	 * @return  array
	 */
	public function getItems(): array
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__users'));

		$users = $db->setQuery($query)->loadObjectList('id');

		// add groups
		if ($users)
		{
			foreach ($users as $id => &$user)
			{
				$user->groups = $this->getUsergroups($id);
			}
		}

		return $users;
	}

	public function setLayout(string $name): string
	{
		return $name . '.twig';
	}
}
