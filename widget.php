<?php

/**
 * Core class used to implement a Remote Recent Posts widget.
 *
 * @since 0.1.0
 *
 * @see WP_Widget
 */
class Josh_Remote_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "A different site&#8217;s most recent Posts.", 'josh-remote-recent-posts') );
		parent::__construct('remote-recent-posts', __('Remote Recent Posts', 'josh-remote-recent-posts'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		$query_class = new JP_Remote_Post_Widget_Query( josh_remote_post_widget_cache_key( $instance ) );
		echo $query_class->get_html();
		return;
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}
		$cache_time = ( ! empty( $instance['cache_time'] ) ) ? absint( $instance['cache_time'] ) : 5;

		$cache_key = md5( __CLASS__ . implode( $args ) );
		if( 0 < $cache_time && false != ($cached = get_transient( $cache_key ) ) ){
			echo $cached;
			return;
		}

		ob_start();
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'josh-remote-recent-posts' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ){
			$number = 5;
		}

		$url = trailingslashit( $instance[ 'url' ] ) . 'wp/v2/posts';
		$url = add_query_arg( 'filter[posts_per_page]', $number, $url );
		$r = wp_safe_remote_get( $url );
		if( ! is_wp_error( $r ) ){
			$posts = json_decode( wp_remote_retrieve_body( $r ) );
			if( ! empty( $posts ) ){
				echo $args['before_widget'];
				if ( $title ) {
					echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
				}?>
					<ul>
						<?php foreach( $posts as $post ) : ?>
							<li>
								<a href="<?php echo esc_url( $post->link ) ?>">
									<?php echo esc_html( $post->title->rendered ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php echo $args['after_widget'];

			}
		}
		$output = ob_get_clean();
		if( 0 < $cache_time ){
			set_transient( $cache_key, $output, $cache_time );
		}
		echo $output;

	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['cache_time'] = (int) $new_instance['cache_time'];
		$instance['url'] = esc_url_raw($new_instance['url'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$cache_time    = isset( $instance['cache_time'] ) ? absint( $instance['cache_time'] ) : 60;
		$url     =  esc_url( $instance['url'] );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'Title', 'josh-remote-recent-posts' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">
				<?php _e( 'Number of posts to show', 'josh-remote-recent-posts' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'cache_time' ); ?>"><?php _e( 'Cache Time (in minutes)' ); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'cache_time' ); ?>" name="<?php echo $this->get_field_name( 'cache_time' ); ?>" type="number" step="1" min="1" value="<?php echo $cache_time; ?>" size="3" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">
				<?php _e( 'Remote URL', 'josh-remote-recent-posts' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo $url; ?>" />
			<p class="description">
				<?php esc_html_e( 'Use full URL for REST API route of remote site', 'josh-remote-recent-posts' ); ?>
			</p>
		</p>

		<?php
	}
}
