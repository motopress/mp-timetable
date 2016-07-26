<h3><?php _e('Genreal Settings', 'mp-timetable'); ?></h3>

<?php settings_errors('mpTimetableSettings', false); ?>

<form method="POST">
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="template_source"><?php _e('Template Mode', 'mp-timetable'); ?></label>
			</th>
			<td>
				<?php $selected = !empty($settings['source_id']) ? $settings['source_id'] : 'plugin'; ?>
				<select id="source_id" name="source_id">
					<option value="theme"<?php echo ($selected == 'theme')? ' selected' : ''; ?>><?php _e('Theme', 'mp-timetable'); ?></option>
					<option value="plugin"<?php echo ($selected == 'plugin')? ' selected' : ''; ?>><?php _e('Plugin', 'mp-timetable'); ?></option>
				</select>
				<p class="description"><?php _e('Choose a page template to control the appearance of your single event and column page.', 'mp-timetable'); ?></p>
			</td>
		</tr>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save', 'mp-timetable') ?>"/>
		<input type="hidden" name="mp-timetable-save-settings"
		       value="<?php echo wp_create_nonce('mp_timetable_nonce_settings') ?>">
	</p>
</form>
