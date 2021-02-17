<input type="hidden" name="<?php echo Mp_Time_Table::get_plugin_name() . '_noncename' ?>" value="<?php echo wp_create_nonce(Mp_Time_Table::get_plugin_path()) ?>"/>
<input type="hidden" id="date-format" value="<?php echo get_option('date_format') ?>">

<table id="column-options" class="column-options form-table">

	<tr>
		<td class="column-option">
			<p>
				<input class="option-input" value="simple" type="radio" name="column[column_option]" id="simple_column" 
					<?php echo ($post->column_option === 'simple' || empty($post->column_option)) ? 'checked="checked"' : '' ?>">
				<label for="simple_column" class="option-label"><?php _e('Simple Column', 'mp-timetable') ?></label>
			</p>
		</td>
	</tr>
	<tr>
		<td class="column-option">
			<p>
				<input class="option-input" value="weekday" type="radio" name="column[column_option]" id="mp_weekday" 
					<?php echo ($post->column_option === 'weekday') ? 'checked="checked"' : '' ?>>
				<label for="mp_weekday" class="option-label"><?php _e('Day of the week', 'mp-timetable') ?></label>
			</p>
			<select class="option-select mp-weekday" name="column[weekday]" <?php echo ($post->column_option != 'weekday') ? 'disabled="disabled"' : '' ?>>
				<option value=""><?php _e('- Select -', 'mp-timetable') ?></option>
				<option value="sunday" <?php selected( $post->weekday, 'sunday' ); ?>><?php _e('Sunday', 'mp-timetable') ?></option>
				<option value="monday" <?php selected( $post->weekday, 'monday' ); ?>><?php _e('Monday', 'mp-timetable') ?></option>
				<option value="tuesday" <?php selected( $post->weekday, 'tuesday' ); ?>><?php _e('Tuesday', 'mp-timetable') ?></option>
				<option value="wednesday" <?php selected( $post->weekday, 'wednesday' ); ?>><?php _e('Wednesday', 'mp-timetable') ?></option>
				<option value="thursday" <?php selected( $post->weekday, 'thursday' ); ?>><?php _e('Thursday', 'mp-timetable') ?></option>
				<option value="friday" <?php selected( $post->weekday, 'friday' ); ?>><?php _e('Friday', 'mp-timetable') ?></option>
				<option value="saturday" <?php selected( $post->weekday, 'saturday' ); ?>><?php _e('Saturday', 'mp-timetable') ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="column-option">
			<p>
				<input class="option-input" value="date" type="radio" name="column[column_option]" id="mp_date" 
					<?php echo ($post->column_option === 'date') ? 'checked="checked"' : '' ?>>
				<label for="mp_date" class="option-label"><?php _e('Date', 'mp-timetable') ?></label>
			</p>
			<div class="column-datepick mp-date">
				<?php
					$datepicker_value = '';
					if ( !empty($post->option_day) ) {
						$datepicker_value = date('d/m/Y', strtotime(str_replace('/', '-', $post->option_day)));
					}
				?>
				<input id="datepicker" class="option-input" type="text" name="column[option_day]"
					value="<?php echo $datepicker_value ?>" 
					<?php echo ($post->column_option != 'date') ? 'disabled="disabled"' : '' ?> 
					placeholder="<?php echo date('d/m/Y', current_time( 'timestamp' ) ) ?>">
			</div>
		</td>
	</tr>

</table>