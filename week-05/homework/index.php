<?php
/**
 * @package    Change Calculator
 *
 * @created    24th April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// NOTE: This file should remain compatible with PHP 5.2 to allow us to run our PHP minimum check and show a friendly error message
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/index.php#L9

// Define the application's minimum supported PHP version as a constant, so it can be referenced within the application.
define('OCTOLEO_MINIMUM_PHP', '7.2.5');

if (version_compare(PHP_VERSION, OCTOLEO_MINIMUM_PHP, '<'))
{
	die(
		str_replace(
			'{{phpversion}}',
			OCTOLEO_MINIMUM_PHP,
			file_get_contents(dirname(__FILE__) . '/templates/system/incompatible.html')
		)
	);
}

/**
 * Constant that is checked in included files to prevent direct access.
 */
define('_LEXEC', 1);

// We must setup some house rules, since we can't have all
// this code just doing what it wants can we?... <<eWɘ>>yn growling
require_once dirname(__FILE__) . '/includes/app.php';
