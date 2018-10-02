<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('General Settings', 'mp-timetable'); ?></h1>

	<?php settings_errors('mpTimetableSettings', false); ?>

	<form method="POST">
		<table class="form-table">
			<tr>
				<td><label for="template_source"><?php _e('Template Mode', 'mp-timetable'); ?></label></td>
				<td>
					<?php $theme_mode = !empty($settings['theme_mode']) ? $settings['theme_mode'] : 'theme'; ?>
					<select id="theme_mode" name="theme_mode" <?php echo $theme_supports ? ' disabled' : ''; ?>>
						<option value="theme" <?php selected($theme_mode, 'theme'); ?>><?php _e('Theme Mode', 'mp-timetable'); ?></option>
						<option value="plugin" <?php selected($theme_mode, 'plugin'); ?>><?php _e('Developer Mode', 'mp-timetable'); ?></option>
					</select>
					<p class="description"><?php _e("Choose Theme Mode to display the content with the styles of your theme. Choose Developer Mode to control appearance of the content with custom page templates, actions and filters.", 'mp-timetable'); ?><br/><?php _e("This option can't be changed if your theme is initially integrated with the plugin.", 'mp-timetable'); ?></p>
				</td>
			</tr>
			<?php
			 if ( apply_filters('mptt_permalinks_enabled', true) ) {
			?>
			<tr>
				<td><?php _e('Permalink Settings'); ?></td>
				<td><?php echo sprintf( __('Configure permalink settings in <a href="%s">Settings > Permalinks</a>', 'mp-timetable'), admin_url('options-permalink.php') ); ?></td>
			</tr>
			 <?php } ?>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save', 'mp-timetable') ?>"/>
			<input type="hidden" name="mp-timetable-save-settings" value="<?php echo wp_create_nonce('mp_timetable_nonce_settings') ?>">
		</p>
	</form>
</div>