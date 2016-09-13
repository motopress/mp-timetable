<div id="tab-main" class="wrap">
	<form id="timetable-settings" method="post">
		<div id="timetable-configuration-tabs">

			<div id="tab-main">
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="weekdays"><?php _e('Column', 'mp-timetable') ?></label>
						</th>
						<td>
							<select multiple="multiple" id="weekday" name="weekday" class="widefat">
								<?php foreach ($data['column'] as $column): ?>
									<option value="<?php echo $column->ID; ?>"><?php echo $column->post_title; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Select the columns that are to be displayed in timetable. Hold the CTRL key to select multiple items.', 'mp-timetable') ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="event"><?php _e('Events', 'mp-timetable') ?></label>
						</th>
						<td>
							<select multiple="multiple" id="event" name="event" class="widefat">
								<?php foreach ($data['events'] as $events): ?>
									<option value="<?php echo $events->ID; ?>"><?php echo $events->post_title; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Select the events that are to be displayed in timetable. Hold the CTRL key to select multiple items.', 'mp-timetable') ?> </span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="event"><?php _e('Event categories', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select multiple="multiple" id="event_category" name="event_category" class="widefat">
								<?php foreach ($data['category'] as $category): ?>
									<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Select the events categories that are to be displayed in timetable. Hold the CTRL key to select multiple items.', 'mp-timetable'); ?> </span>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="measure"><?php _e('Hour measure', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="measure" name="measure">
								<option value="1"><?php _e('Hour (1h)', 'mp-timetable'); ?></option>
								<option value="0.5"><?php _e('Half hour (30min)', 'mp-timetable'); ?></option>
								<option value="0.25"><?php _e('Quarter hour (15min)', 'mp-timetable'); ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Choose hour measure for event hours.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="filter_style"><?php _e('Filter style', 'mp-timetable'); ?>     </label>
						</th>
						<td>
							<select id="filter_style" name="filter_style">
								<option value="dropdown_list"><?php _e('Dropdown list', 'mp-timetable'); ?></option>
								<option value="tabs"><?php _e('Tabs', 'mp-timetable'); ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Choose between dropdown menu and tabs for event filtering.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="filter_label"><?php _e('Filter label', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="filter_label" id="filter_label" value="All Events" class="regular-text">
						</td>
						<td>
							<span class="description"><?php _e('Specify text label for all events.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="hide_all_events_view"><?php _e('Hide \'All Events\' view', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="hide_all_events_view" name="hide_all_events_view">
								<option value="0"><?php _e('No', 'mp-timetable') ?></option>
								<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Set to Yes to hide All Events view.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="hide_hours_column"><?php _e('Hide first (hours) column', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="hide_hours_column" name="hide_hours_column">
								<option value="0"><?php _e('No', 'mp-timetable') ?></option>
								<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Set to Yes to hide timetable column with hours.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="hide_empty"><?php _e('Hide empty rows', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="hide_empty" name="hide_empty">
								<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
								<option value="0"><?php _e('No', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Set to Yes to hide timetable rows without events.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="display_block"><?php _e('Display:', 'mp-timetable'); ?></label>
						</th>
						<td>
							<label for="title" class="label_width"><input type="checkbox" name="title" checked value="1"/><?php _e('Title', 'mp-timetable'); ?></label><br/>
							<label for="time" class="label_width"><input type="checkbox" name="time" checked value="1"/><?php _e('Time', 'mp-timetable'); ?></label><br/>
							<label for="sub-title" class="label_width"><input type="checkbox" name="sub-title" checked value="1"/><?php _e('Subtitle', 'mp-timetable'); ?></label><br/>
							<label for="description" class="label_width"><input type="checkbox" name="description" value="1"/><?php _e('Description', 'mp-timetable'); ?></label><br/>
							<label for="user" class="label_width"><input type="checkbox" name="user" value="1"/><?php _e('User', 'mp-timetable'); ?></label>
						</td>
						<td>
							<span class="description"><?php _e('Check the event parameter(s) to be displayed for a certain event in the timetable.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="disable_event_url"><?php _e('Disable event URL', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="disable_event_url" name="disable_event_url">
								<option value="0"><?php _e('No', 'mp-timetable') ?></option>
								<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Set to Yes for nonclickable event blocks.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="text_align"><?php _e('Text align', 'mp-timetable') ?> </label>
						</th>
						<td>
							<select id="text_align" name="text_align">
								<option value="center"><?php _e('center', 'mp-timetable') ?></option>
								<option value="left"><?php _e('left', 'mp-timetable') ?></option>
								<option value="right"><?php _e('right', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Specify text align in timetable event block.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="id"><?php _e('Id', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="id" id="id" value="" class="regular-text">
						</td>
						<td>
							<span class="description"><?php _e('Assign a unique identifier to a timetable if you use more than one table on a single page. Otherwise, leave this field blank.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="row_height"><?php _e('Row height (in px)', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="row_height" id="row_height" value="45" class="regular-text">
						</td>
						<td>
							<span class="description"><?php _e('Specify timetable row height in pixels.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="font_size"><?php _e('Base Font Size', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="font_size" id="font_size" value="" class="regular-text">
						</td>
						<td>
							<span class="description"><?php _e('Base font size for the table. Example 12px, 2em, 80%.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="responsive"><?php _e('Responsive', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select id="responsive" name="responsive">
								<option value="1"><?php _e('Yes', 'mp-timetable') ?></option>
								<option value="0"><?php _e('No', 'mp-timetable') ?></option>
							</select>
						</td>
						<td>
							<span class="description"><?php _e('Set Yes to remove a horizontal scroll bar on the desktop and display a table in a list view on mobile devices. <br> Set No to display a table with horizontal scroll bar on both desktop and mobile devices.', 'mp-timetable'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"></th>
						<td>
							<input type="button" value="<?php _e('Add Timetable', 'mp-timetable'); ?>" id="insert-into" class="button button-primary button-large" name="save">
						</td>
						<td></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-colors" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-hidden="true">
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="box_bg_color"><?php _e('Timetable box background color', 'mp-timetable'); ?> </label>
						</th>
						<td>
							<span style="background-color: #00A27C" class="color_preview"></span>
							<input type="text" data-default-color="00A27C" value="00A27C" name="box_bg_color" id="box_bg_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="box_hover_bg_color"><?php _e('Timetable box hover background color', 'mp-timetable'); ?></label>
						</th>
						<td>
							<span style="background-color: #1F736A" class="color_preview"></span>
							<input type="text" data-default-color="1F736A" value="1F736A" name="box_hover_bg_color" id="box_hover_bg_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="box_txt_color"><?php _e('Timetable box text color', 'mp-timetable'); ?>  </label>
						</th>
						<td>
							<span style="background-color: #FFFFFF" class="color_preview"></span>
							<input type="text" data-default-color="FFFFFF" value="FFFFFF" name="box_txt_color" id="box_txt_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="box_hover_txt_color"><?php _e('Timetable box hover text color', 'mp-timetable'); ?> </label>
						</th>
						<td>
							<span style="background-color: #FFFFFF" class="color_preview"></span>
							<input type="text" data-default-color="FFFFFF" value="FFFFFF" name="box_hover_txt_color" id="box_hover_txt_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="box_hours_txt_color"><?php _e('Timetable box hours text color', 'mp-timetable'); ?> </label>
						</th>
						<td>
							<span style="background-color: #FFFFFF" class="color_preview"></span>
							<input type="text" data-default-color="FFFFFF" value="FFFFFF" name="box_hours_txt_color" id="box_hours_txt_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="box_hours_hover_txt_color"><?php _e('Timetable box hours hover text color', 'mp-timetable'); ?>  </label>
						</th>
						<td>
							<span style="background-color: #FFFFFF" class="color_preview"></span>
							<input type="text" data-default-color="FFFFFF" value="FFFFFF" name="box_hours_hover_txt_color" id="box_hours_hover_txt_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="filter_color"><?php _e('Filter control background color', 'mp-timetable'); ?>  </label>
						</th>
						<td>
							<span style="background-color: #00A27C" class="color_preview"></span>
							<input type="text" data-default-color="00A27C" value="00A27C" name="filter_color" id="filter_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="row1_color"><?php _e('Row 1 style background color', 'mp-timetable'); ?> </label>
						</th>
						<td>
							<span style="background-color: #F0F0F0" class="color_preview"></span>
							<input type="text" data-default-color="F0F0F0" value="F0F0F0" name="row1_color" id="row1_color" class="regular-text color">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="row2_color"><?php _e('Row 2 style background color', 'mp-timetable'); ?>  </label>
						</th>
						<td>
							<span style="background-color: transparent" class="color_preview"></span>
							<input type="text" data-default-color="transparent" value="" name="row2_color" id="row2_color" class="regular-text color">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-fonts" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-hidden="true">
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="timetable_font_custom"><?php _e('Enter font name', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="timetable_font_custom" id="timetable_font_custom" value="" class="regular-text">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="timetable_font"><?php _e('or choose Google font', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select class="google_font_chooser" id="timetable_font" name="timetable_font">
								<option value=""><?php _e('Default', 'mp-timetable'); ?></option>
							</select>
							<span class="spinner"></span>
						</td>
					</tr>
					<tr class="fontSubsetRow">
						<th scope="row">
							<label for="timetable_font_subset"><?php _e('Google font subset', 'mp-timetable'); ?></label>
						</th>
						<td>
							<select multiple="multiple" class="fontSubset" id="timetable_font_subset" name="timetable_font_subset[]"></select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="timetable_font_size"><?php _e('Font size (in px)', 'mp-timetable'); ?></label>
						</th>
						<td>
							<input type="text" name="timetable_font_size" id="timetable_font_size" value="" class="regular-text">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div id="tab-custom-css" aria-labelledby="ui-id-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" style="display: none;" aria-hidden="true">
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row">
							<label for="timetable_custom_css"><?php _e('Custom CSS', 'mp-timetable'); ?></label>
						</th>
						<td>
							<textarea style="width: 540px; height: 200px;" name="timetable_custom_css" id="timetable_custom_css"></textarea>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>