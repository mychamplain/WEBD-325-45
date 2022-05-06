<?php
/**
 * @package    Octoleo CMS
 *
 * @created    18th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Model\Util;

/**
 * Trait for getting menu items
 *
 * @since  1.0.0
 */
trait SiteMenuTrait
{
	/**
	 * Get all menu items that are root and published and not home page
	 *
	 * @param   int $active
	 *
	 * @return array
	 */
	public function getMenus(int $active = 0): array
	{
		$db = $this->getDb();

		$query = $db->getQuery(true)
			->select('a.*')
			->from($db->quoteName('#__menu', 'a'))
			->where($db->quoteName('a.published') . ' = 1')
			->where($db->quoteName('a.home') . ' = 0');

		try
		{
			$menus = $db->setQuery($query)->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			return [];
		}

		if ($menus)
		{
			$bucket = [];
			foreach ($menus as $menu)
			{
				$row = [];
				// set the details
				$row['id'] = $menu->id;
				$row['title'] = $menu->title;
				$row['path'] = $menu->path;
				$row['parent'] = $menu->parent_id;
				// set position
				$params = (isset($menu->params) && strpos($menu->params, 'position') !== false) ? json_decode($menu->params) : null;
				// default is center
				$row['position'] = (is_object($params) && isset($params->position)) ? $params->position : 'center';

				// add to our bucket
				$bucket[] = $row;
			}
			return $bucket;
		}

		return [];
	}
}
