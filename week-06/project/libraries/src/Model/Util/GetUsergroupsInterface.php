<?php
/**
 * @package    Octoleo CMS
 *
 * @created    23th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Model\Util;

/**
 * Class for all user groups
 *
 * @since  1.0.0
 */
interface  GetUsergroupsInterface
{
	/**
	 * Get all user groups
	 *
	 * @param   int|null  $id
	 *
	 * @return array
	 */
	public function getUsergroups(?int $id = null): array;

	/**
	 * Get the group default full access values
	 *
	 * @param   string  $access
	 *
	 * @return array
	 */
	public function getGroupDefaultsAccess(string $access = 'CRUD'): array;
}
