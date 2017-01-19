<form id="mptt-settings" method="post">
	<div class="mptt-shortcode-settings-wrapper">
		<table class="form-table striped">
			<tr>
				<td><label for="weekday"><?php _e('<b>Columns</b> (required)', 'mp-timetable') ?></label></td>
				<td>
					<select multiple="multiple" id="weekday" name="weekday" class="widefat">
						<?php foreach ($data[ 'column' ] as $column): ?>
							<option value="<?php echo $column->ID; ?>"><?php echo $column->post_title; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php _e('In order to display multiple points hold ctrl/cmd button.', 'mp-timetable') ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="event"><?php _e('Specific <b>events</b>', 'mp-timetable') ?></label></td>
				<td>
					<select multiple="multiple" id="event" name="event" class="widefat">
						<?php foreach ($data[ 'events' ] as $events): ?>
							<option value="<?php echo $events->ID; ?>"><?php echo $events->post_title; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php _e('In order to display multiple points hold ctrl/cmd button.', 'mp-timetable') ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="event_category"><?php _e('Event <b>categories</b>', 'mp-timetable'); ?></label></td>
				<td>
					<select multiple="multiple" id="event_category" name="event_category" class="widefat">
						<?php foreach ($data[ 'category' ] as $category): ?>
							<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php _e('In order to display multiple points hold ctrl/cmd button.', 'mp-timetable'); ?></p>
				</td>
			</tr>
			<tr>
				<td><label><?php _e('Fields to display:', 'mp-timetable'); ?></label></td>
				<td>
					<label for="title" class="label_width"><input type="checkbox" name="title" checked value="1"/><?php _e('Title', 'mp-timetable'); ?></label><br/>
					<label for="time" class="label_width"><input type="checkbox" name="time" checked value="1"/><?php _e('Time', 'mp-timetable'); ?></label><br/>
					<label for="sub-title" class="label_width"><input type="checkbox" name="sub-title" checked value="1"/><?php _e('Subtitle', 'mp-timetable'); ?></label><br/>
					<label for="description" class="label_width"><input type="checkbox" name="description" value="1"/><?php _e('Description', 'mp-timetable'); ?></label><br/>
					<label for="user" class="label_width"><input type="checkbox" name="user" value="1"/><?php _e('Event Head', 'mp-timetable'); ?></label>
					<p class="description"><?php _e('Check the event parameter(s) to be displayed in the timetable.', 'mp-timetable'); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="row_height"><?php _e('Block height in pixels', 'mp-timetable'); ?></label></td>
				<td>
					<input type="text" name="row_height" id="row_height" value="45" class="regular-text">
					<p class="description"><?php _e('Set height of the block', 'mp-timetable'); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="font_size"><?php _e('Base font size', 'mp-timetable'); ?></label></td>
				<td>
					<input type="text" name="font_size" id="font_size" value="" class="regular-text">
					<p class="description"><?php _e('Base font size for the table. Example 12px, 2em, 80%.', 'mp-timetable'); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="measure"><?php _e('Time frame for event', 'mp-timetable'); ?></label></td>
				<td>
					<select id="measure" name="measure">
						<option value="1"><?php _e('Hour (1h)', 'mp-timetable'); ?></option>
						<option value="0.5"><?php _e('Half hour (30min)', 'mp-timetable'); ?></option>
						<option value="0.25"><?php _e('Quarter hour (15min)', 'mp-timetable'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="filter_style"><?php _e('Filter events style', 'mp-timetable'); ?>     </label></td>
				<td>
					<select id="filter_style" name="filter_style">
						<option value="dropdown_list"><?php _e('Dropdown', 'mp-timetable'); ?></option>
						<option value="tabs"><?php _e('Tabs', 'mp-timetable'); ?></option>
						<option value="none"><?php _e('None', 'mp-timetable'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="filter_label"><?php _e('Filter title to display all events', 'mp-timetable'); ?></label></td>
				<td>
					<input type="text" name="filter_label" id="filter_label" value="All Events" class="regular-text">
				</td>
			</tr>
			<tr>
				<td><label for="hide_all_events_view"><?php _e('Hide \'All Events\' option', 'mp-timetable'); ?></label>
				</td>
				<td>
					<select id="hide_all_events_view" name="hide_all_events_view">
						<option value="0"><?php _e('No', 'mp-timetable') ?></option>
						<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="hide_hours_column"><?php _e('Hide column with hours', 'mp-timetable'); ?></label></td>
				<td>
					<select id="hide_hours_column" name="hide_hours_column">
						<option value="0"><?php _e('No', 'mp-timetable') ?></option>
						<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="hide_empty"><?php _e('Do not display empty rows', 'mp-timetable'); ?></label></td>
				<td>
					<select id="hide_empty" name="hide_empty">
						<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
						<option value="0"><?php _e('No', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="group_events"><?php _e('Merge cells with common events', 'mp-timetable'); ?></label></td>
				<td>
					<select id="group_events" name="group_events">
						<option value="0"><?php _e('No', 'mp-timetable') ?></option>
						<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="disable_event_url"><?php _e('Disable event link', 'mp-timetable'); ?></label></td>
				<td>
					<select id="disable_event_url" name="disable_event_url">
						<option value="0"><?php _e('No', 'mp-timetable') ?></option>
						<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="text_align"><?php _e('Horizontal align', 'mp-timetable') ?> </label></td>
				<td><select id="text_align" name="text_align">
						<option value="center"><?php _e('center', 'mp-timetable') ?></option>
						<option value="left"><?php _e('left', 'mp-timetable') ?></option>
						<option value="right"><?php _e('right', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="text_align_vertical"><?php _e('Vertical align', 'mp-timetable') ?> </label></td>
				<td><select id="text_align_vertical" name="text_align_vertical">
						<option value="default"><?php _e('default', 'mp-timetable') ?></option>
						<option value="top"><?php _e('top', 'mp-timetable') ?></option>
						<option value="middle"><?php _e('middle', 'mp-timetable') ?></option>
						<option value="bottom"><?php _e('bottom', 'mp-timetable') ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="id"><?php _e('Unique ID', 'mp-timetable'); ?></label></td>
				<td>
					<input type="text" name="id" id="id" value="" class="regular-text">
					<p class="description"><?php _e('If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mp-timetable'); ?></p>
				</td>
			</tr>
			<tr>
				<td><label for="custom_class"><?php _e('CSS class', 'mp-timetable'); ?></label></td>
				<td>
					<input type="text" name="custom_class" id="custom_class" value="" class="regular-text">
				</td>
			</tr>
			<tr>
				<td><label for="responsive"><?php _e('Mobile behavior', 'mp-timetable'); ?></label></td>
				<td>
					<select id="responsive" name="responsive">
						<option value="1"><?php _e('List', 'mp-timetable') ?></option>
						<option value="0"><?php _e('Table', 'mp-timetable') ?></option>
					</select>
					<p class="description"><?php _e('Tick "List" to display events in a list view on mobile devices. Tick "Table" to display events in a table.', 'mp-timetable'); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<div class="mptt-shortcode-submit-wrapper">
		<input type="button" value="<?php _e('Add Timetable', 'mp-timetable'); ?>" id="insert-into" class="button button-primary button-large" name="save">
	</div>
</form>