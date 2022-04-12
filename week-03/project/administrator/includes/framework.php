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

// System includes
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/includes/framework.php#L14
require_once LPATH_LIBRARIES . '/bootstrap.php';

// Installation check, and check on removal of the installation directory.
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/includes/framework.php#L17
if (!file_exists(LPATH_CONFIGURATION . '/octoconfig.php')
	|| (filesize(LPATH_CONFIGURATION . '/octoconfig.php') < 10)
	|| (file_exists(LPATH_INSTALLATION . '/index.php')))
{
	if (file_exists(LPATH_INSTALLATION . '/index.php'))
	{
		header('Location: ../installation/index.php');

		exit;
	}
	else
	{
		echo 'No configuration file found and no installation code available. Exiting...';

		exit;
	}
}
