<?php
/**
 * @package    Octoleo CMS
 *
 * @created    9th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_LEXEC') or die;

// Option to override defines from root folder
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/includes/app.php#L15
if (file_exists(dirname(__DIR__) . '/defines.php'))
{
	include_once dirname(__DIR__) . '/defines.php';
}

// Load the default defines
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/includes/app.php#L20
if (!defined('_LDEFINES'))
{
	define('LPATH_BASE', dirname(__DIR__));
	require_once LPATH_BASE . '/includes/defines.php';
}

// I have not yet had time to finish this part of the application (CMS)
echo file_get_contents(LPATH_ROOT . '/templates/system/install_notice.html');

exit;
