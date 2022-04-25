<?php
/**
 * @package    Octoleo CMS
 *
 * @created    21th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Octoleo\CMS\Model\Util;

/**
 * Class for getting the home page
 *
 * @since  1.0.0
 */
interface  HomeMenuInterface
{
	/**
	 * @return \stdClass
	 */
	public function getHomePage(): \stdClass;
}
