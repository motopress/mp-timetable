<div class="<?php echo $widget_object->widget_options['classname'] ?>">
	<p>
		<label for="<?php echo $widget_object->get_field_id('title') ?>"><?php _e('Title', "mp-timetable") ?></label>
		<input class="widefat" id="<?php echo $widget_object->get_field_id('title') ?>" name="<?php echo $widget_object->get_field_name('title') ?>" type="text" value="<?php echo $instance['title'] ?>">
	</p>
	<p>
		<label for="<?php echo $widget_object->get_field_id('user_id') ?>"><?php _e('User ID', "mp-timetable") ?></label>
		<?php wp_dropdown_users(array(
				'show_option_none' => null,
				'show_option_all' => null,
				'hide_if_only_one_author' => null,
				'orderby' => 'display_name',
				'order' => 'ASC',
				'include' => null,
				'exclude' => null,
				'multi' => false,
				'show' => 'display_name',
				'echo' => true,
				'selected' => $instance['user_id'],
				'include_selected' => false,
				'name' => $widget_object->get_field_name('user_id'),
				'id' => $widget_object->get_field_id('user_id'), // integer
				'class' => 'widefat',
		)); ?>
	</p>

	<p>
		<label for="<?php echo $widget_object->get_field_id('limit') ?>"><?php _e('Number of events to display', "mp-timetable") ?></label>
		<input class="widefat" id="<?php echo $widget_object->get_field_id('limit') ?>" name="<?php echo $widget_object->get_field_name('limit') ?>" type="text" value="<?php echo $instance['limit'] ?>">
	</p>

	<p>
		<label for="<?php echo $widget_object->get_field_id('view_settings') ?>"><?php _e('Display settings', "mp-timetable") ?></label>
		<select class="view_settings widefat" id="<?php echo $widget_object->get_field_id('view_settings') ?>" name="<?php echo $widget_object->get_field_name('view_settings') ?>">
			<option value="today" <?php echo $instance['view_settings'] === 'today' ? 'selected="selected"' : '' ?> ><?php _e('today upcoming', "mp-timetable") ?></option>
			<option value="all" <?php echo $instance['view_settings'] === 'all' ? 'selected="selected"' : '' ?> ><?php _e('all upcoming', "mp-timetable") ?></option>
			<option value="current"<?php echo $instance['view_settings'] === 'current' ? 'selected="selected"' : '' ?>><?php _e('current events', "mp-timetable") ?></option>
		</select>
	</p>

</div>