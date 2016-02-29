<?php

/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */
class JP_Remote_Post_Widget_Query {

	private $html;

	private $cache_key;

	public function __construct( $cache_key ){
		$this->cache_key = $cache_key;
		$this->make_hmtl();

	}

	public function get_html(){
		return $this->html;
	}

	protected function make_hmtl(){
		$this->html = sprintf( '<div class="jp-remote-post-widget" data-key="%s"></div>', $this->cache_key );
	}
}

