<div class="<?php echo $widget_object->widget_options['classname'] ?>">
	<p>
		<label for="<?php echo $widget_object->get_field_id('title') ?>"><?php _e('Title', "mp-timetable") ?></label>
		<input class="widefat" id="<?php echo $widget_object->get_field_id('title') ?>"
		       name="<?php echo $widget_object->get_field_name('title') ?>" type="text"
		       value="<?php echo $instance['title'] ?>">
	</p>

	<p>
		<label
			for="<?php echo $widget_object->get_field_id('limit') ?>"><?php _e('Number of events to display', "mp-timetable") ?></label>
		<input class="widefat" id="<?php echo $widget_object->get_field_id('limit') ?>"
		       name="<?php echo $widget_object->get_field_name('limit') ?>" type="text"
		       value="<?php echo $instance['limit'] ?>">
	</p>

	<p>
		<label
			for="<?php echo $widget_object->get_field_id('view_settings') ?>"><?php _e('Display settings', "mp-timetable") ?></label>
		<select class="view_settings widefat" id="<?php echo $widget_object->get_field_id('view_settings') ?>"
		        name="<?php echo $widget_object->get_field_name('view_settings') ?>">
			<option
				value="today" <?php echo $instance['view_settings'] === 'today' ? 'selected="selected"' : '' ?> ><?php _e('today upcoming', "mp-timetable") ?></option>
			<option
				value="all" <?php echo $instance['view_settings'] === 'all' ? 'selected="selected"' : '' ?> ><?php _e('all upcoming', "mp-timetable") ?></option>
			<option
				value="current"<?php echo $instance['view_settings'] === 'current' ? 'selected="selected"' : '' ?>><?php _e('current events', "mp-timetable") ?></option>
		</select>
	</p>

	<p class="next-days" style="display: <?php echo $instance['view_settings'] === 'all' ? 'block' : 'none' ?> ;">
		<label for="<?php echo $widget_object->get_field_id('next_days') ?>">
			<?php _e('Enter number of days', "mp-timetable") ?>
		</label>
		<input class="regular-text" id="<?php echo $widget_object->get_field_id('next_days') ?>"
		       name="<?php echo $widget_object->get_field_name('next_days') ?>" type="text"
		       value="<?php echo $instance['next_days'] ?>">
		<?php _n('day', 'days', $instance['next_days'], "mp-timetable") ?>
	</p>

	<p>
		<label class="widget-categories"
		       for="<?php echo $widget_object->get_field_id('mp_categories') ?>"><?php _e('Categories', "mp-timetable") ?></label>
		<select class="widefat" multiple="multiple" id="<?php echo $widget_object->get_field_id('mp_categories') ?>"
		        name="<?php echo $widget_object->get_field_name('mp_categories') ?>[]">
			<?php
			if (!empty($data['categories'])):
				foreach ($data['categories'] as $category) :
					if (empty($instance['mp_categories'])):
						$instance['mp_categories'] = array();
					endif ?>
					<option
						value="<?php echo $category->term_id ?>"<?php echo in_array($category->term_id, $instance['mp_categories']) ? 'selected="selected"' : '' ?> ><?php echo $category->name ?></option>
				<?php endforeach;
			endif; ?>
		</select>
	</p>
	<p>
		<label
			for="<?php echo $widget_object->get_field_id('custom_url') ?>"><?php _e('Custom event url', "mp-timetable") ?></label>
		<input class="widefat" id="<?php echo $widget_object->get_field_id('custom_url') ?>"
		       name="<?php echo $widget_object->get_field_name('custom_url') ?>" type="text"
		       value="<?php echo $instance['custom_url'] ?>">
	</p>

	<p>
		<label
			for="<?php echo $widget_object->get_field_id('disable_url') ?>"><?php _e('Disable event URL', "mp-timetable") ?></label>
		<select class="widefat" id="<?php echo $widget_object->get_field_id('disable_url') ?>"
		        name="<?php echo $widget_object->get_field_name('disable_url') ?>">
			<option
				value="0" <?php echo $instance['disable_url'] === '0' ? 'selected="selected"' : '' ?>> <?php _e('No', "mp-timetable") ?> </option>
			<option
				value="1" <?php echo $instance['disable_url'] === '1' ? 'selected="selected"' : '' ?>> <?php _e('Yes', "mp-timetable") ?> </option>
		</select>
	</p>

	<?php if (\mp_timetable\classes\models\Settings::get_instance()->is_plugin_template_mode()): ?>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('background_color'); ?>"><?php _e('Background color', "mp-timetable"); ?></label>
		</p>
		<p class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['background_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('background_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('background_color'); ?>" type="text"
			       value="<?php echo $instance['background_color']; ?>"/>
		</p>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('hover_background_color'); ?>"><?php _e('Hover background color', "mp-timetable"); ?></label>
		</p>
		<p class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['hover_background_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('hover_background_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('hover_background_color'); ?>" type="text"
			       value="<?php echo $instance['hover_background_color']; ?>"/>
		</p>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('text_color'); ?>"><?php _e('Text color', "mp-timetable"); ?></label>
		</p>
		<p class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['text_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('text_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('text_color'); ?>" type="text"
			       value="<?php echo $instance['text_color']; ?>"/>
		</p>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('hover_text_color'); ?>"><?php _e('Hover text color', "mp-timetable"); ?></label>
		</p>
		<p class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['hover_text_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('hover_text_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('hover_text_color'); ?>" type="text"
			       value="<?php echo $instance['hover_text_color']; ?>"/>
		</p>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('item_border_color'); ?>"><?php _e('Item border color', "mp-timetable"); ?></label>
		</p>
		<P class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['item_border_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('item_border_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('item_border_color'); ?>" type="text"
			       value="<?php echo $instance['item_border_color']; ?>"/>
		</P>

		<p style="margin-bottom:0px;">
			<label
				for="<?php echo $widget_object->get_field_id('hover_item_border_color'); ?>"><?php _e('Hover item border color', "mp-timetable"); ?></label>
		</p>
		<p class="select-color" style="margin-top:0px;">
			<input type="hidden" class="clr-picker" value="<?php echo $instance['hover_item_border_color']; ?>">
			<input class="regular-text" id="<?php echo $widget_object->get_field_id('hover_item_border_color'); ?>"
			       name="<?php echo $widget_object->get_field_name('hover_item_border_color'); ?>" type="text"
			       value="<?php echo $instance['hover_item_border_color']; ?>"/>
		</p>

	<?php else: ?>

		<input type="hidden" name="<?php echo $widget_object->get_field_name('background_color'); ?>" value=""/>
		<input type="hidden" name="<?php echo $widget_object->get_field_name('hover_background_color'); ?>" value=""/>
		<input type="hidden" name="<?php echo $widget_object->get_field_name('text_color'); ?>" value=""/>
		<input type="hidden" name="<?php echo $widget_object->get_field_name('hover_text_color'); ?>" value=""/>
		<input type="hidden" name="<?php echo $widget_object->get_field_name('item_border_color'); ?>" value=""/>
		<input type="hidden" name="<?php echo $widget_object->get_field_name('hover_item_border_color'); ?>" value=""/>

	<?php endif; ?>

</div>
<script type="application/javascript">
	(function ($) {
		"use strict";
		$(document).ready(function () {
			Registry._get("Event").initColorPicker('#widgets-right .mptt-container');
			Registry._get("Event").displaySettings("<?php echo $widget_object->get_field_id('view_settings') ?>");
			Registry._get("Event").timeMode("<?php echo $widget_object->get_field_id('time_settings');?>");
		});
	})(jQuery);
</script>