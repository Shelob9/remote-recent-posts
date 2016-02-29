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
class JP_Remote_Post_Widget_Inner_HTML {

	private $html;

	public function __construct( $key ){
		if( false == $this->get_cached( $key ) ){
			$this->make_html();
		}
	}
	public function get_html(){
		return $this->html;
	}


	protected function  get_cache( $key ){
		if(  false != ( $cached = get_transient( $key ) ) ){
			$this->html = $cached;
		}else{
			return false;
		}
	}
}
