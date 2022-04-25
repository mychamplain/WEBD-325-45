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
 * Class for getting page data
 *
 * @since  1.0.0
 */
interface  PageInterface
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
	public function getPageItemByPath(string $path): \stdClass;

	/**
	 * Get page data
	 *
	 * @param   int  $item  The item id
	 *
	 * @return  \stdClass
	 *
	 * @throws  \RuntimeException
	 */
	public function getPageItemById(int $item): \stdClass;
}
