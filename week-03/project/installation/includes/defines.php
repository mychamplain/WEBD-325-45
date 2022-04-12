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

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, LPATH_BASE);
array_pop($parts);

// Defines.
define('LPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('LPATH_SITE',          LPATH_ROOT);
define('LPATH_CONFIGURATION', LPATH_ROOT);
define('LPATH_ADMINISTRATOR', LPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('LPATH_LIBRARIES',     LPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('LPATH_INSTALLATION',  LPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
