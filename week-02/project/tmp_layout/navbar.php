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
	<div class="uk-navbar-center">
		<ul class="uk-navbar-nav">
			<li class="uk-active"><a href="?view=home">Home</a></li>
			<li>
				<a href="?view=products">Products</a>
				<div class="uk-navbar-dropdown">
					<ul class="uk-nav uk-navbar-dropdown-nav">
						<li class="uk-active"><a href="?view=products&item=yachts">Yachts</a></li>
						<li><a href="?view=products&item=ski-boats">Ski Boats</a></li>
						<li><a href="?view=products&item=drones">Drones</a></li>
					</ul>
				</div>
			</li>
			<li><a href="?view=blog">Blog</a></li>
			<li><a href="?view=about-us">About Us</a></li>
		</ul>
	</div>
	<div class="uk-navbar-right">
		<ul class="uk-navbar-nav">
			<li><a href="?view=location">Location</a></li>
			<li><a href="?view=contact-us">Contact Us</a></li>
		</ul>
	</div>
</nav>
<!-- Source: https://getuikit.com/docs/navbar -->