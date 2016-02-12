<?php
/**
 * Plugin Name: Remote Recent Posts
 * Plugin URI:  http://JoshPress.net
 * Description: Like the default recent post widget, but fetches post from remote site running the WordPress REST API
 * Version: 0.1.0
 * Author:      Josh Pollock
 * Author URI:  http://JoshPress.net
 * License:     GPLv2+
 * Text Domain: josh-remote-recent-posts
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 Josh Pollock for CalderaWP LLC (email : Josh@JoshPress.net)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

include_once( dirname( __FILE__ ) . '/widget.php' );
function josh_remote_post_widget_register() {
	register_widget( 'Josh_Remote_Recent_Posts' );
}

add_action( 'widgets_init', 'josh_remote_post_widget_register' );
