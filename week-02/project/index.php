<?php
/**
 * @package    Generic CMS
 *
 * @created    3rd April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Constant that is checked in included files to prevent direct access.
 */
define('_WEBD', 1);

// I realize you said no PHP (but can you blame me?)
set_include_path(PATH_SEPARATOR . __DIR__ . '/tmp_views');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/tmp_layout');

// legal views
$views = array(
	'home' => 'home',
	'products' => 'products',
	'blog' => 'blog',
	'about-us' => 'about_us',
	'location' => 'location',
	'contact-us' => 'contact_us'
);

// dynamic view
$view = (isset($_GET['view']) && isset($views[$_GET['view']])) ? $views[$_GET['view']] : 'home';
$title = ucfirst($view);

// add the header
include 'header.php';
// add the header
include 'navbar.php';
// load our view
include "$view.php";
// load the footer
require 'footer.php';
