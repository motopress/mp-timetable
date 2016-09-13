<input type="hidden" name="<?php echo Mp_Time_Table::get_plugin_name() . '_noncename' ?>" value="<?php echo wp_create_nonce(Mp_Time_Table::get_plugin_path()) ?>"/>
<input type="hidden" id="date-format" value="<?php echo get_option('date_format') ?>">

<table id="column-options" class="column-options form-table">

	<tr>
		<td class="column-option">
			<input class="option-input" value="simple" type="radio" name="column[column_option]" id="simple_column" <?php echo ($post->column_option === 'simple' || empty($post->column_option)) ? 'checked="checked"' : '' ?>">
			<label for="simple_column" class="option-label"><?php _e('Simple Column', 'mp-timetable') ?></label>
		</td>
	</tr>
	<tr>
		<td class="column-option">
			<input class="option-input" value="weekday" type="radio" name="column[column_option]" id="mp_weekday" <?php echo ($post->column_option === 'weekday') ? 'checked="checked"' : '' ?>>
			<label for="mp_weekday" class="option-label"><?php _e('Day', 'mp-timetable') ?></label>
			<br>
			<select class="option-select mp-weekday" name="column[weekday]" <?php echo ($post->column_option != 'weekday') ? 'disabled' : '' ?>>
				<option value=""><?php _e('- Select -', 'mp-timetable') ?></option>
				<option value="sunday" <?php echo $post->weekday === 'sunday' ? 'selected="selected"' : '' ?> ><?php _e('Sunday', 'mp-timetable') ?></option>
				<option value="monday" <?php echo $post->weekday === 'monday' ? 'selected="selected"' : '' ?>><?php _e('Monday', 'mp-timetable') ?></option>
				<option value="tuesday" <?php echo $post->weekday === 'tuesday' ? 'selected="selected"' : '' ?>><?php _e('Tuesday', 'mp-timetable') ?></option>
				<option value="wednesday" <?php echo $post->weekday === 'wednesday' ? 'selected="selected"' : '' ?>><?php _e('Wednesday', 'mp-timetable') ?></option>
				<option value="thursday" <?php echo $post->weekday === 'thursday' ? 'selected="selected"' : '' ?>><?php _e('Thursday', 'mp-timetable') ?></option>
				<option value="friday" <?php echo $post->weekday === 'friday' ? 'selected="selected"' : '' ?>><?php _e('Friday', 'mp-timetable') ?></option>
				<option value="saturday" <?php echo $post->weekday === 'saturday' ? 'selected="selected"' : '' ?>><?php _e('Saturday', 'mp-timetable') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="column-option">
			<input class="option-input" value="date" type="radio" name="column[column_option]" id="mp_date" <?php echo ($post->column_option === 'date') ? 'checked="checked"' : '' ?>>
			<label for="mp_date" class="option-label"><?php _e('Date', 'mp-timetable') ?></label>
			<div class="column-datepick mp-date">
				<input id="datepicker" class="option-input" value="<?php echo (!empty($post->option_day)) ? date('d/m/Y', strtotime(str_replace('/', '-', $post->option_day))) : '' ?>" type="text" name="column[option_day]" <?php echo ($post->column_option != 'date') ? 'disabled' : '' ?> placeholder="<?php echo date('d/m/Y', time()) ?>">
			</div>
		</td>
	</tr>

</table>