<?php
/**
 * @package    Generic CMS
 *
 * @created    3rd April 2022
 * @author     Llewellyn van der Merwe <https://git.vdm.dev/Llewellyn>
 * @git        WEBD-325-45 <https://git.vdm.dev/Llewellyn/WEBD-325-45>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_WEBD') or die('Restricted access');

?>

<nav class="uk-navbar-container" uk-navbar>
	<div class="uk-navbar-left">
		<ul class="uk-navbar-nav">
			<li><a href="?view=dashboard">Dashboard</a></li>
			<li><a href="?view=users">Users</a></li>
			<li><a href="?view=menus">Menus</a></li>
			<li><a href="?view=items">Items</a></li>
		</ul>
	</div>
	<div class="uk-navbar-right">
		<ul class="uk-navbar-nav">
			<li class="uk-active"><a href="?view=login">Logout</a></li>
		</ul>
	</div>
</nav>
<!-- Source: https://getuikit.com/docs/navbar -->