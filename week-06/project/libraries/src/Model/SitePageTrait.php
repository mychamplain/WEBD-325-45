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

use Joomla\Database\ParameterType;

/**
 * Trait for getting page data
 *
 * @since  1.0.0
 */
trait SitePageTrait
{
	/**
	 * Get page data
	 *
	 * @param   string  $path  The page path
	 *
	 * @return  \stdClass
	 *
	 * @throws  \RuntimeException
	 */
	public function getPageItemByPath(string $path): \stdClass
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select('t.*')
			->from($db->quoteName('#__menu', 'a'))
			->join('INNER', $db->quoteName('#__item', 't'), 'a.item_id = t.id')
			->where($db->quoteName('path') . ' = :path')
			->bind(':path', $path)
			->setLimit(1);

		try
		{
			$page = $db->setQuery($query)->loadObject();
		}
		catch (\RuntimeException $e)
		{
			return new \stdClass();
		}

		if ($page)
		{
			return $page;
		}

		return new \stdClass();
	}

	/**
	 * Get page data
	 *
	 * @param   int  $item  The item id
	 *
	 * @return  \stdClass
	 *
	 * @throws  \RuntimeException
	 */
	public function getPageItemById(int $item): \stdClass
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->quoteName('#__item', 'a'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $item, ParameterType::INTEGER)
			->setLimit(1);

		try
		{
			$page = $db->setQuery($query)->loadObject();
		}
		catch (\RuntimeException $e)
		{
			return new \stdClass();
		}

		if ($page)
		{
			return $page;
		}

		return new \stdClass();
	}
}
