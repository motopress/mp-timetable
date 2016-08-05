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
				<?php $theme_mode = !empty($settings['theme_mode']) ? $settings['theme_mode'] : 'theme'; ?>

				<select id="theme_mode" name="theme_mode" <?php echo $theme_supports ? ' disabled' : ''; ?>>
					<option value="theme" <?php selected($theme_mode, 'theme'); ?>><?php _e('Theme Mode', 'mp-timetable'); ?></option>
					<option value="plugin" <?php selected($theme_mode, 'plugin'); ?>><?php _e('Developer Mode', 'mp-timetable'); ?></option>
				</select>

				<p class="description"><?php _e("Choose Theme Mode to display the content with the styles of your theme. Choose Developer Mode to control appearance of the content with custom page templates, actions and filters.", 'mp-timetable'); ?><br/><?php _e("This option can't be changed if your theme is initially integrated with plugin.", 'mp-timetable'); ?></p>
			</td>
		</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save', 'mp-timetable') ?>"/>
		<input type="hidden" name="mp-timetable-save-settings" value="<?php echo wp_create_nonce('mp_timetable_nonce_settings') ?>">
	</p>
</form>
