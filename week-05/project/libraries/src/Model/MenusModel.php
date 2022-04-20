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

/**
 * Model class for menus
 */
class MenusModel implements DatabaseModelInterface
{
	use DatabaseModelTrait;

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
			->select('a.*')
			->select($db->quoteName(array('t.title'), array('item_title')))
			->from($db->quoteName('#__menu', 'a'))
			->join('INNER', $db->quoteName('#__item', 't'), 'a.item_id = t.id');

		return $db->setQuery($query)->loadObjectList('id');
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
}
