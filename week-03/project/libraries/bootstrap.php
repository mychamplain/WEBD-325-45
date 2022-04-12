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

// Set the platform root path as a constant if necessary.
// source: https://github.com/joomla/joomla-cms/blob/4.1-dev/libraries/bootstrap.php#L12
defined('LPATH_PLATFORM') or define('LPATH_PLATFORM', __DIR__);

// Detect the native operating system type.
$os = strtoupper(substr(PHP_OS, 0, 3));

defined('IS_WIN') or define('IS_WIN', ($os === 'WIN'));
defined('IS_UNIX') or define('IS_UNIX', (($os !== 'MAC') && ($os !== 'WIN')));

// Import the library loader if necessary.
if (!class_exists('LLoader'))
{
	require_once LPATH_PLATFORM . '/loader.php';

	// If JLoader still does not exist panic.
	if (!class_exists('LLoader'))
	{
		throw new RuntimeException('Octoleo Platform not loaded.');
	}
}

// Setup the autoloaders.
LLoader::setup();

// Create the Composer autoloader
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require LPATH_LIBRARIES . '/vendor/autoload.php';

// We need to pull our decorated class loader into memory before unregistering Composer's loader
class_exists('\\Octoleo\\CMS\\Autoload\\ClassLoader');

$loader->unregister();

// Decorate Composer autoloader
spl_autoload_register([new \Octoleo\CMS\Autoload\ClassLoader($loader), 'loadClass'], true, true);
