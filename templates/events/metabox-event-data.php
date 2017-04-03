<input type="hidden" name="<?php echo Mp_Time_Table::get_plugin_name() . '_noncename' ?>" id="eventmeta_noncename" value="<?php echo wp_create_nonce(Mp_Time_Table::get_plugin_path()) ?>"/>
<input type="hidden" name="events[place]" id="eventmeta_place" value=""/>
<input type="hidden" name="events[leading_event]" id="eventmeta_leading_event" value="'.<?php wp_create_nonce(plugin_basename(__FILE__)) ?>.'"/>
<input type="hidden" id="time_format" value="<?php echo $date["time_format"]["am_pm"] === true ? '1' : '0' ?>"/>

<?php \mp_timetable\plugin_core\classes\View::get_instance()->render_html('events/event-data', array('event_data' => $event_data), true) ?>

<table id="add_event_table" class="widefat">
	<tr>
		<td><label for="weekday_id"><?php _e('Column:', 'mp-timetable') ?></label></td>
		<td>
			<?php if (count($columns)) { ?>
				<select id="weekday_id" name="events[weekday_id]">
					<?php foreach ($columns as $column) { ?>
						<option value="<?php echo $column->ID ?>"><?php echo $column->post_title ?></option>
					<?php } ?>
				</select>
				<span class="description"><?php printf(__('Select column or <a target="_blank" href="%s">Add New</a>.', 'mp-timetable'), admin_url('post-new.php?post_type=mp-column') ); ?></span>
			<?php } else {
				printf(__('No columns found. <a href="%s">Create at least one column first.</a>', 'mp-timetable'), admin_url('post-new.php?post_type=mp-column'));
			}
			?>
		</td>
	</tr>
	<tr>
		<td><label for="event_start"><?php _e('Start Time:', 'mp-timetable') ?></label></td>
		<td>
			<input id="event_start" type="text" value="" name="events[start_hour]" maxlength="5" size="5">
			<span class="description"><?php _e('hh:mm', 'mp-timetable') ?></span>
		</td>
	</tr>
	<tr>
		<td><label for="event_end"><?php _e('End Time:', 'mp-timetable') ?></label></td>
		<td>
			<input id="event_end" type="text" value="" name="events[end_hour]" maxlength="5" size="5">
			<span class="description"><?php _e('hh:mm', 'mp-timetable') ?></span>
		</td>
	</tr>
	<tr>
		<td><label for="description"><?php _e('Description:', 'mp-timetable') ?></label></td>
		<td><textarea id="description" class="widefat" name="events[description]" rows="2"></textarea></td>
	</tr>
	<tr>
		<td><label for="user_id"><?php _e('Event Head:', 'mp-timetable') ?></label></td>
		<td>
			<?php
			
			global $wp_version;
			$wp_dropdown_users_show = (version_compare($wp_version, '4.5', '<')) ? 'user_login' : 'display_name_with_login';
			
			wp_dropdown_users(array(
				'show_option_none' => __('none', 'mp-timetable'),
				'show_option_all' => null,
				'hide_if_only_one_author' => null,
				'orderby' => 'display_name',
				'order' => 'ASC',
				'include' => null,
				'exclude' => null,
				'multi' => false,
				'show' => $wp_dropdown_users_show,
				'echo' => true,
				'selected' => false,
				'include_selected' => false,
				'name' => 'user_id',
				'id' => null,
				'class' => null,
				'blog_id' => $GLOBALS['blog_id'],
				'who' => null
			)); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input id="add_mp_event" type="button" class="button button-primary" value="<?php _e('Add New', 'mp-timetable'); ?>">
			<span class="spinner left"></span>
		</td>
	</tr>
</table>
