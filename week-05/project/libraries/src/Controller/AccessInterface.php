<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Controller;

/**
 * Class for checking the user access
 *
 * @since  1.0.0
 */
interface  AccessInterface
{
	/**
	 * @param   string  $task
	 * @param   string  $default
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function allow(string $task, string $default = ''): bool;
}
