<?php ?>
<table id="add_event_options_table" class="form-table">
	<tr>
		<td><label for="sub_title"><?php _e('Event Subtitle:', 'mp-timetable') ?></label></td>
		<td><input id="sub_title" class="widefat" type="text" value="<?php echo $post->sub_title ?>" name="event_meta[sub_title]"></td>
	</tr>
	<tr class="select-color">
		<td><label for="color"><?php _e('Background Color:', 'mp-timetable'); ?></label></td>
		<td>
			<input type="hidden" class="clr-picker" value="<?php echo $post->color ?>">
			<input type="text" id="color" name="event_meta[color]" value="<?php echo $post->color ?>" data-default-color="transparent">
		</td>
	</tr>
	<tr class="select-color">
		<td><label for="hover_color"><?php _e('Background Hover Color:', 'mp-timetable'); ?></label></td>
		<td>
			<input type="hidden" class="clr-picker" value="<?php echo $post->hover_color ?>">
			<input type="text" id="hover_color" name="event_meta[hover_color]" value="<?php echo $post->hover_color ?>" data-default-color="transparent">
		</td>
	</tr>
	<tr class="select-color">
		<td><label for="text_color"><?php _e('Text Color:', 'mp-timetable'); ?></label></td>
		<td>
			<input type="hidden" class="clr-picker" value="<?php echo $post->text_color ?>">
			<input type="text" id="text_color" name="event_meta[text_color]" value="<?php echo $post->text_color ?>" data-default-color="transparent">
		</td>
	</tr>
	<tr class="select-color">
		<td><label for="hover_text_color"><?php _e('Text Hover Color:', 'mp-timetable'); ?></label></td>
		<td>
			<input type="hidden" class="clr-picker" value="<?php echo $post->hover_text_color ?>">
			<input type="text" id="hover_text_color" name="event_meta[hover_text_color]" value="<?php echo $post->hover_text_color ?>" data-default-color="transparent">
		</td>
	</tr>
	<tr>
		<td><label for="timetable_custom_url"><?php _e('Custom Event URL:', 'mp-timetable'); ?></label></td>
		<td>
			<input type="text" id="timetable_custom_url" class="widefat" placeholder="http://mywebsite.com" name="event_meta[timetable_custom_url]" value="<?php echo $post->timetable_custom_url ?>">
		</td>
	</tr>
	<tr>
		<td><label for="select-disable-url"><?php _e('Disable link to this event:', 'mp-timetable'); ?></label></td>
		<td>
			<select id="select-disable-url" name="event_meta[timetable_disable_url]">
				<option value="0" <?php echo !($post->timetable_disable_url) ? 'selected="selected"' : '' ?> ><?php _e('No', 'mp-timetable') ?></option>
				<option value="1" <?php echo ($post->timetable_disable_url) ? 'selected="selected"' : '' ?>><?php _e('Yes', 'mp-timetable') ?></option>
			</select>
		</td>
	</tr>
</table>