<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Controller\Util;

/**
 * Class for checking the form had a token
 *
 * @since  1.0.0
 */
interface  CheckTokenInterface
{
	/**
	 * Check the token of the form
	 *
	 * @return bool
	 */
	public function checkToken(): bool;
}
