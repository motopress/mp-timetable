<?php

namespace mp_timetable\classes\models;

use mp_timetable\plugin_core\classes\Model as Model;
use mp_timetable\plugin_core\classes\modules\Taxonomy;


/**
 * Model Events
 */
class Events extends Model {
	
	protected static $instance;
	protected $wpdb;
	protected $table_name;
	protected $post_type;
	
	/**
	 * Events constructor.
	 */
	function __construct() {
		parent::__construct();
		global $wpdb;
		$this->wpdb           = $wpdb;
		$this->post_type      = 'mp-event';
		$this->taxonomy_names = array(
			'tag' => 'mp-event_tag',
			'cat' => 'mp-event_category',
		);
		$this->table_name     = $wpdb->prefix . "mp_timetable_data";
	}
	
	/**
	 * @return Events
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Getter
	 *
	 * @param $property
	 *
	 * @return mixed
	 */
	function __get( $property ) {
		return $this->{$property};
	}
	
	/**
	 * Setter
	 *
	 * @param $property
	 * @param $value
	 *
	 * @return mixed
	 */
	function __set( $property, $value ) {
		return $this->{$property} = $value;
	}
	
	/**
	 * Render event metabox
	 *
	 * @param $post
	 * @param $metabox
	 */
	public function render_event_data( $post, $metabox ) {

		$time_format       = get_option( 'time_format' );
		if ( $time_format === 'H:i' ) {
			$time_format_array = array( 'hours' => '0,23', 'am_pm' => false );
		} elseif ( $time_format === 'g:i A' ) {
			$time_format_array = array( 'hours' => '1,12', 'am_pm' => true );
		} else {
			$time_format_array = array( 'hours' => '0,23', 'am_pm' => false );
		}

		$data[ 'columns' ] = $this->get( 'column' )->get_all_column();
		$event_data = $this->get_event_data( array( 'field' => 'event_id', 'id' => $post->ID ), 'event_start', false );
		
		$this->get_view()->render_html( "events/metabox-event-data", array( 'event_data' => $event_data, 'args' => $metabox[ 'args' ],
			'columns' => $data[ 'columns' ], 'date' => array( 'time_format' => $time_format_array ) ), true );
	}
	
	/**
	 *
	 * Get single event data by id
	 *
	 * @param $params
	 * @param string $order_by
	 * @param bool $publish
	 *
	 * @return array|null|object
	 */
	public function get_event_data( $params, $order_by = 'event_start', $publish = true ) {
		$publish_query_part = $publish ? " AND `post_status` = 'publish'" : '';
		$table_posts        = $this->wpdb->prefix . 'posts';
		
		$event_data = $this->wpdb->get_results(
			"SELECT t.*"
			. " FROM $this->table_name t INNER JOIN"
			. " ("
			. "	SELECT * FROM {$table_posts}"
			. " WHERE `post_type` = 'mp-column' AND `post_status` = 'publish'"
			. " ) p ON t.`column_id` = p.`ID`"
			. " INNER JOIN ("
			. "	SELECT * FROM {$table_posts}"
			. " WHERE `post_type` = '{$this->post_type}'{$publish_query_part}"
			. " ) e ON t.`event_id` = e.`ID`"
			. " WHERE t.`{$params["field"]}` = {$params['id']} "
			. " ORDER BY p.`menu_order`, t.`{$order_by}`"
		);

		foreach ( $event_data as $key => $event ) {

			$user = false;
			$wpUser = get_user_by( 'id', $event_data[ $key ]->user_id );

			if ( ! empty( $wpUser ) ) {
				$user = new \stdClass();
				$user->ID = $wpUser->ID;
				$user->display_name = $wpUser->data->display_name;
			}

			$event_data[ $key ]->event_start = date( 'H:i', strtotime( $event_data[ $key ]->event_start ) );
			$event_data[ $key ]->event_end   = date( 'H:i', strtotime( $event_data[ $key ]->event_end ) );
			$event_data[ $key ]->user        = $user;
			$event_data[ $key ]->post        = get_post( $event_data[ $key ]->event_id );
			$event_data[ $key ]->description = stripcslashes( $event_data[ $key ]->description );
		}

		return $event_data;
	}
	
	/**
	 * Render meta data
	 */
	public function render_event_metas() {
		$this->append_time_slots();
	}
	
	/**
	 * Render Timeslots by $post
	 */
	public function append_time_slots() {
		global $post;
		
		$show_public_only = ( ( get_post_status( $post->ID ) == 'draft' ) && is_preview() ) ? false : true;
		
		$data       = $this->get_event_data( array( 'field' => 'event_id', 'id' => $post->ID ), 'event_start', $show_public_only );
		$event_data = ( ! empty( $data ) ) ? $data : array();
		$count      = count( $event_data );
		
		$this->get_view()->get_template( "theme/event-timeslots", array( 'events' => $event_data, 'count' => $count ) );
	}
	
	/**
	 * Render event options
	 *
	 * @param $post
	 */
	public function render_event_options( $post ) {
		$this->get_view()->render_html( "events/metabox-event-options", array( 'post' => $post ), true );
	}
	
	/**
	 * Add column
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function set_event_columns( $columns ) {
		$columns = array_slice( $columns, 0, 2, true ) + array( $this->taxonomy_names[ 'tag' ] => __( 'Tags', 'mp-timetable' ) ) + array_slice( $columns, 2, count( $columns ) - 1, true );
		$columns = array_slice( $columns, 0, 2, true ) + array( $this->taxonomy_names[ 'cat' ] => __( 'Categories', 'mp-timetable' ) ) + array_slice( $columns, 2, count( $columns ) - 1, true );
		
		return $columns;
	}
	
	/**
	 * Create event category admin link
	 *
	 * @param $column
	 */
	public function get_event_taxonomy( $column ) {
		global $post;
		if ( $column === $this->taxonomy_names[ 'cat' ] ) {
			echo Taxonomy::get_instance()->get_the_term_filter_list( $post, $this->taxonomy_names[ 'cat' ] );
		}
		if ( $column === $this->taxonomy_names[ 'tag' ] ) {
			echo Taxonomy::get_instance()->get_the_term_filter_list( $post, $this->taxonomy_names[ 'tag' ] );
		}
	}
	
	/**
	 * Output category post
	 *
	 * @param string $the_list
	 * @param string $separator
	 * @param string $parents
	 *
	 * @return mixed
	 */
	public function the_category( $the_list = '', $separator = '', $parents = '' ) {
		global $post;
		
		if ( $post && $post->post_type === $this->post_type && ! is_admin() ) {
			$categories = wp_get_post_terms( $post->ID, $this->taxonomy_names[ 'cat' ] );
			$the_list .= $this->generate_event_tags( $categories, $separator, $parents );
		}
		
		/**
		 * Filter the category or list of Timetable categories.
		 *
		 * @param array $thelist List of categories for the current post.
		 * @param string $separator Separator used between the categories.
		 * @param string $parents How to display the category parents. Accepts 'multiple',
		 *                          'single', or empty.
		 */
		return apply_filters( 'mptt_the_category', $the_list, $separator, $parents );
	}
	
	/**
	 * Generate HTML of the post tags
	 *
	 * @param $categories
	 * @param $separator
	 * @param $parents
	 *
	 * @return string
	 */
	public function generate_event_tags( $categories, $separator, $parents ) {
		global $wp_rewrite;
		$the_list = '';
		$rel      = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
		
		if ( '' == $separator ) {
			$the_list .= '<ul class="post-categories">';
			foreach ( $categories as $category ) {
				$the_list .= "\n\t<li>";
				switch ( strtolower( $parents ) ) {
					case 'multiple':
						if ( $category->parent ) {
							$the_list .= get_category_parents( $category->parent, true, $separator );
						}
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . $category->name . '</a></li>';
						break;
					case 'single':
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '"  ' . $rel . '>';
						if ( $category->parent ) {
							$the_list .= get_category_parents( $category->parent, false, $separator );
						}
						$the_list .= $category->name . '</a></li>';
						break;
					case '':
					default:
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . $category->name . '</a></li>';
				}
			}
			$the_list .= '</ul>';
		} else {
			$i = 0;
			foreach ( $categories as $category ) {
				if ( 0 < $i ) {
					$the_list .= $separator;
				}
				switch ( strtolower( $parents ) ) {
					case 'multiple':
						if ( $category->parent ) {
							$the_list .= get_category_parents( $category->parent, true, $separator );
						}
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . $category->name . '</a>';
						break;
					case 'single':
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>';
						if ( $category->parent ) {
							$the_list .= get_category_parents( $category->parent, false, $separator );
						}
						$the_list .= "$category->name</a>";
						break;
					case '':
					default:
						$the_list .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . $category->name . '</a>';
				}
				++ $i;
			}
		}
		
		return $the_list;
	}
	
	/**
	 * Returns a formatted tags.
	 *
	 * @param $tags
	 * @param string $before
	 * @param string $sep
	 * @param string $after
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function the_tags( $tags, $before = '', $sep = '', $after = '', $id = 0 ) {
		global $post;
		
		if ( $post && $post->post_type === $this->post_type ) {
			$id          = ( $id === 0 ) ? $post->id : $id;
			$events_tags = get_the_term_list( $id, $this->taxonomy_names[ 'tag' ], $before, $sep, $after );
			$tags        = apply_filters( 'mptt_the_tags', $events_tags, $tags );
		}
		
		return $tags;
	}
	
	/**
	 * Save(insert) event  data
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function save_event_data( array $params ) {

		$rows_affected = array();

		if ( ! empty( $params[ 'event_data' ] ) ) {
			foreach ( $params[ 'event_data' ] as $key => $event ) {
				if ( is_array( $event[ 'event_start' ] ) && ! empty( $event[ 'event_start' ] ) ) {
					
					for ( $i = 0; $i < count( $event[ 'event_start' ] ); $i ++ ) {
						$rows_affected[] = $this->wpdb->insert( $this->table_name, array(
								'column_id'   => $key,
								'event_id'    => $params[ 'post' ]->ID,
								'event_start' => date( 'H:i', strtotime( $event[ 'event_start' ][ $i ] ) ),
								'event_end'   => date( 'H:i', strtotime( $event[ 'event_end' ][ $i ] ) ),
								'user_id'     => $event[ 'user_id' ][ $i ],
								'description' => wp_kses_post( $event[ 'description' ][ $i ] )
							)
						);
					}
				}
			}
		}
		if ( ! empty( $params[ 'event_meta' ] ) ) {
			foreach ( $params[ 'event_meta' ] as $meta_key => $meta ) {
				switch ( $meta_key ) {
					case 'timetable_custom_url':
						update_post_meta( $params[ 'post' ]->ID, $meta_key, esc_url_raw( $meta ) );
					break;
					default:
						update_post_meta( $params[ 'post' ]->ID, $meta_key, wp_kses_post( $meta ) );
				}
			}
		}
		
		return $rows_affected;
	}
	
	/**
	 * Delete event timeslot
	 *
	 * @param $id
	 *
	 * @return false|int
	 */
	public function delete_event( $id ) {
		return $this->wpdb->delete( $this->table_name, array( 'id' => $id ), array( '%d' ) );
	}
	
	/**
	 * Delete event data
	 *
	 * @param $post_id
	 *
	 * @return false|int
	 */
	public function before_delete_event( $post_id ) {
		$meta_keys = array( 'event_id', 'event_start', 'event_end', 'user_id', 'description' );
		
		foreach ( $meta_keys as $meta_key ) {
			delete_post_meta( $post_id, $meta_key );
		}
		
		return $this->wpdb->delete( $this->table_name, array( 'event_id' => $post_id ), array( '%d' ) );
	}
	
	/**
	 * Get widget events
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	public function get_widget_events( $instance ) {
		$events       = array();
		$current_local_time = current_time( 'timestamp' );

		$weekday      = strtolower( date( 'l', $current_local_time ) );
		$current_date = date( 'd/m/Y', $current_local_time );
		
		// 24.09.2019 seconds added
		$curent_time  = date( 'H:i:s', $current_local_time );
		
		if ( ! empty( $instance[ 'mp_categories' ] ) ) {
			$category_columns_ids = $this->get( 'column' )->get_columns_by_event_category( $instance[ 'mp_categories' ] );
		}
		
		$args = array(
			'post_type'   => 'mp-column',
			'post_status' => 'publish',
			'fields'      => 'ids',
			'post__in'    => ! empty( $category_columns_ids ) ? $category_columns_ids : '',
			'orderby'     => 'menu_order',
			'meta_query'  => array(
				'relation' => 'OR',
				array(
					'key'   => 'weekday',
					'value' => $weekday
				),
				array(
					'key'   => 'option_day',
					'value' => $current_date
				)
			)
		);
		
		switch ( $instance[ 'view_settings' ] ) {
			case'today':
			case 'current':
				$column_post_ids = get_posts( $args );
				if ( ! empty( $column_post_ids ) ) {
					$events = $this->get_events_data( array( 'column' => 'column_id', 'list' => $column_post_ids ) );
				}
				$events = $this->filter_events( array( 'events' => $events, 'view_settings' => $instance[ 'view_settings' ], 'time' => $curent_time, 'mp_categories' => $instance[ 'mp_categories' ] ) );
				break;
			case 'all':
				
				if ( ! empty( $instance[ 'next_days' ] ) && $instance[ 'next_days' ] > 0 ) {
					$events_array = array();
					for ( $i = 0; $i <= $instance[ 'next_days' ]; $i ++ ) {

						// set new day week
						$time = strtotime( "+$i days", $current_local_time );
						$day  = strtolower( date( 'l', $time ) );
						$date = date( 'd/m/Y', $time );

						//set week day
						$args[ 'meta_query' ][ 0 ][ 'value' ] = $day;
						//set new date
						$args[ 'meta_query' ][ 1 ][ 'value' ] = $date;
						
						$column_post_ids = get_posts( $args );
						
						if ( ! empty( $column_post_ids ) ) {
							$events_array[ $i ] = $this->get_events_data( array( 'column' => 'column_id', 'list' => $column_post_ids ) );
						} else {
							$events_array[ $i ] = array();
						}
						
						// Filter by time and categories for current day
						if ( $i === 0 && ! empty( $instance[ 'mp_categories' ] ) && ! empty( $events_array[ $i ] ) ) {
							$events_array[ $i ] = $events_array[ $i ] = $this->filter_events( array( 'events' => $events_array[ $i ], 'view_settings' => 'today', 'time' => $curent_time, 'mp_categories' => $instance[ 'mp_categories' ] ) );
						} elseif ( ! empty( $instance[ 'mp_categories' ] ) && ! empty( $events_array[ $i ] ) ) {
							//Filter by  categories for next days
							$events_array[ $i ] = $this->filter_events_by_categories( $events_array[ $i ], $instance[ 'mp_categories' ] );
						}
						
					}
					
					foreach ( $events_array as $day_events ) {
						$events = array_merge( $events, $day_events );
					}
					
				}
				
				break;
			
			default:
				$column_post_ids = get_posts( $args );
				if ( ! empty( $column_post_ids ) ) {
					$events = $this->get_events_data( array( 'column' => 'column_id', 'list' => $column_post_ids ) );
				}
				$events = $this->filter_events( array( 'events' => $events, 'view_settings' => 'today', 'time' => $curent_time, 'mp_categories' => $instance[ 'mp_categories' ] ) );
				
				break;
		}
		if ( $instance[ 'limit' ] > 0 ) {
			$events = array_slice( $events, 0, $instance[ 'limit' ] );
		}
		
		return $events;
	}
	
	/**
	 * Get event data by post
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_events_data( array $params ) {
		$events      = array();
		$sql_request = "SELECT * FROM " . $this->table_name;
		
		if ( ( ! empty( $params[ 'all' ] ) && $params[ 'all' ] ) || empty( $params[ 'list' ] ) ) {
			
		} elseif ( ! is_array( $params[ 'column' ] ) ) {
			
			if ( isset( $params[ 'list' ] ) && is_array( $params[ 'list' ] ) ) {
				$params[ 'list' ] = implode( ',', $params[ 'list' ] );
			}
			
			$sql_request .= " WHERE " . $params[ 'column' ] . " IN (" . $params[ 'list' ] . ")";
			
		} elseif ( is_array( $params[ 'column' ] ) && is_array( $params[ 'list' ] ) ) {
			
			$sql_request .= " WHERE ";
			
			$last_key = key( array_slice( $params[ 'column' ], - 1, 1, true ) );
			
			foreach ( $params[ 'column' ] as $key => $column ) {
				if ( isset( $params[ 'list' ][ $column ] ) && is_array( $params[ 'list' ][ $column ] ) ) {
					$params[ 'list' ][ $column ] = implode( ',', $params[ 'list' ][ $column ] );
				}
				$sql_request .= $column . " IN (" . $params[ 'list' ][ $column ] . ")";
				$sql_request .= ( $last_key != $key ) ? ' AND ' : '';
			}
			
		}
		
		$sql_request .= ' ORDER BY `event_start`';
		
		$events_data = $this->wpdb->get_results( $sql_request );
		
		if ( is_array( $events_data ) ) {
			
			foreach ( $events_data as $event ) {
				$post = get_post( $event->event_id );
				
				if ( $post && ( $post->post_type == $this->post_type ) && ( $post->post_status == 'publish' ) ) {
					$event->post        = $post;
					$event->event_start = date( 'H:i', strtotime( $event->event_start ) );
					$event->event_end   = date( 'H:i', strtotime( $event->event_end ) );
					$events[]           = $event;
				}
			}
		}
		
		return $events;
	}
	
	/**
	 * Filtered events by view settings
	 *
	 * @param $params
	 *
	 * @return array
	 */
	protected function filter_events( $params ) {

		$events = array();
		$events = $this->filter_by_time_period( $params, $events );

		if ( ! empty( $params[ 'mp_categories' ] ) ) {
			$events = $this->filter_events_by_categories( $events, $params[ 'mp_categories' ] );
		}
		
		return $events;
	}
	
	/**
	 * Filter by time period
	 *
	 * @param $params
	 * @param $events
	 *
	 * @return array
	 */
	protected function filter_by_time_period( $params, $events ) {
		if ( ! empty( $params[ 'events' ] ) ) {

			$time = strtotime( $params[ 'time' ] );

			foreach ( $params[ 'events' ] as $key => $event ) {
				
				$event_end = strtotime( $event->event_end );
				$event_start = strtotime( $event->event_start );

				if ( $params[ 'view_settings' ] === 'today' || $params[ 'view_settings' ] === 'all' ) {
					if ( $event_end  <= $time ) {
						continue;
					}
				} elseif ( $params[ 'view_settings' ] === 'current' ) {
					if ( $event_start >= $time || $event_end  <= $time ) {
						continue;
					}
				}
				$events[ $key ] = $event;
			}
		}
		
		return $events;
	}
	
	/**
	 * Filter find events by select categories;
	 *
	 * @param array $events
	 * @param array $categories
	 *
	 * @return array
	 */
	public function filter_events_by_categories( array $events, array $categories ) {
		$temp_events = array();
		$taxonomy    = $this->taxonomy_names[ 'cat' ];
		
		foreach ( $events as $event ) {
			if ( @has_term( $categories, $taxonomy, $event->post->ID ) ) {
				$temp_events[] = $event;
			}
		}
		
		return $temp_events;
	}
	
	/**
	 * Sort by params
	 *
	 * @param $events
	 *
	 * @return mixed
	 */
	
	public function sort_by_param( $events ) {
		
		usort( $events, function ( $a, $b ) {
			if ( strtotime( $a->event_start ) == strtotime( $b->event_start ) ) {
				return 0;
			}
			
			return ( strtotime( $a->event_start ) < strtotime( $b->event_start ) ) ? - 1 : 1;
		} );
		
		
		return $events;
	}
	
	/**
	 * @param array /string $event_category
	 *
	 * @return array|null|object
	 */
	public function get_events_data_by_category( $event_categories ) {
		if ( ! is_array( $event_categories ) ) {
			$terms = explode( ',', $event_categories );
		} else {
			$terms = $event_categories;
		}
		
		$posts_array = get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_type'      => $this->post_type,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'mp-event_category',
						'field'    => 'term_id',
						'terms'    => $terms,
					)
				)
			)
		);
		
		$ids        = implode( ',', $posts_array );
		$event_data = $this->get_events_data( array( 'column' => 'event_id', 'list' => $ids ) );
		
		return $event_data;
	}
	
	/**
	 * Update event data
	 *
	 * @param $data
	 *
	 * @return false|int
	 */
	public function update_event_data( $data ) {

		$id = (int) $data[ 'id' ];

		$result = $this->wpdb->update(
			$this->table_name,
			array(
				'event_start' => date( 'H:i', strtotime( $data[ 'event_start' ] ) ),
				'event_end'   => date( 'H:i', strtotime( $data[ 'event_end' ] ) ),
				'description' => wp_kses_post( $data[ 'description' ] ),
				'column_id'   => $data[ 'weekday_ids' ],
				'user_id'     => $data[ 'user_id' ],
			),
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
			),
			array( '%d' )
		);
		
		return $result;
	}
	
	/**
	 * Get all events
	 * @return array
	 */
	public function get_all_events() {
		$args = array(
			'post_type'      => $this->post_type,
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => 'title',
			'posts_per_page' => - 1
		);
		
		return get_posts( $args );
	}
	
	/**
	 * Choose color widget or event
	 *
	 * @param $params
	 *
	 * @return string
	 */
	
	public function choose_event_color( $params ) {
		if ( ! empty( $params[ 'widget_color' ] ) ) {
			return $params[ 'widget_color' ];
		} elseif ( ! empty( $params[ 'event_color' ] ) ) {
			return $params[ 'event_color' ];
		} else {
			return '';
		}
	}
	
	/**
	 * Filtered events by Event Head
	 *
	 * @param $params
	 *
	 * @return array
	 */
	protected function filter_events_by_field( $params ) {
		$events = array();
		if ( ! empty( $params[ 'events' ] ) ) {
			foreach ( $params[ 'events' ] as $key => $event ) {
				if ( $event->$params[ 'field' ] != $params[ 'value' ] ) {
					continue;
				}
				
				$events[ $key ] = $event;
			}
		}
		
		return $events;
	}
	
	public function post_row_actions( $actions, $post ) {
		
		if ( $post->post_type == $this->post_type && current_user_can('edit_posts') ) {

			$action_url = add_query_arg(
				array(
					'post' => $post->ID,
					'action' => 'mptt_duplicate_event',
					'_wpnonce' => wp_create_nonce( 'mptt_duplicate_event' )
				),
				admin_url( 'post.php' )
			);

			$actions['duplicate'] = '<a href="' . $action_url . '" aria-label="' .
				__('Duplicate', 'mp-timetable') . '" rel="permalink">' . __('Duplicate', 'mp-timetable') . '</a>';
		}

		return $actions;
	}

	public function post_action_mptt_duplicate_event( $post_id ) {

		global $wpdb;

		$post_type = '';

		if ( $post_id ) {
			$post = get_post( $post_id );
		}

		if ( $post ) {
			$post_type = $post->post_type;
		}

		if ( $post_type !== $this->post_type ) {
			wp_die( __( 'A post type mismatch has been detected.', 'mp-timetable' ), __( 'Sorry, you are not allowed to edit this item.', 'mp-timetable' ), 400 );
		}

		$nonce = $_REQUEST['_wpnonce'];

		if ( wp_verify_nonce( $nonce, 'mptt_duplicate_event' ) && current_user_can('edit_posts') ) {

			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;

			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				// translators: New post title of the duplicated post
				'post_title'     => sprintf( __('%s - Copy', 'mp-timetable'), $post->post_title ),
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			if( is_wp_error($new_post_id) ) {
				wp_die( $post_id->get_error_message() );
			}

			/*
			 * get all current post terms and set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type);
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}

			/*
			 * duplicate all post meta
			 */
			$post_meta = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");

			if ( !empty($post_meta) ) {

				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

				foreach ($post_meta as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if ( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}

				$sql_query .= implode( " UNION ALL ", $sql_query_sel );

				$wpdb->query( $sql_query );
			}

			/*
			 * duplicate timeslots in custom BD
			 */
			$timeslots = $wpdb->get_results( "SELECT * FROM {$this->table_name} WHERE event_id = " . $post_id, OBJECT );

			if ( !empty($timeslots) ) {

				foreach ( $timeslots as $timeslot ) {
					$wpdb->insert(
						$this->table_name,
						array(
							'column_id'   => $timeslot->column_id,
							'event_id'    => $new_post_id,
							'event_start' => date( 'H:i', strtotime( $timeslot->event_start ) ),
							'event_end'   => date( 'H:i', strtotime( $timeslot->event_end ) ),
							'user_id'     => $timeslot->user_id,
							'description' => $timeslot->description
						)
					);
				}
			}

			/*
			 * redirect to the edit post screen for the new draft
			 */
			wp_safe_redirect( get_edit_post_link( $new_post_id, '' ) );
			exit();

		} else {
			wp_die( __( 'Sorry, you are not allowed to edit this item.' ) );
		}
	}

	public function get_timeslot_by_id( $id ) {

		global $wpdb;

		$timeslot = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %d", $id )
		);

		return $timeslot;
	}

}
