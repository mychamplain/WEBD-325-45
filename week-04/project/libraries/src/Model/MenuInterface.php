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

/**
 * Class for getting menu items
 *
 * @since  1.0.0
 */
interface  MenuInterface
{
	/**
	 * Get all menu items
	 *
	 * @param   string|null  $active
	 *
	 * @return array
	 */
	public function getMenus(string $active = null): array;
}
