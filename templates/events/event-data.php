<table class="widefat fixed">
	<thead>
		<tr>
			<th><?php _e('Column', 'mp-timetable') ?></th>
			<th><?php _e('Start', 'mp-timetable') ?></th>
			<th><?php _e('End', 'mp-timetable') ?></th>
			<th><?php _e('Description', 'mp-timetable') ?></th>
			<th><?php _e('Head', 'mp-timetable') ?></th>
			<th><?php _e('Actions', 'mp-timetable') ?></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<div class="events-list-wrapper">
	<table id="events-list" class="widefat fixed striped">
		<tbody>
		<?php if (!empty($event_data)): ?>
			<?php foreach ($event_data as $data): ?>
				<tr data-id="<?php echo $data->id ?>">
					<td class="event-column"><?php echo get_the_title($data->column_id); ?></td>
					<td class="event-start"><?php echo date(get_option('time_format'), strtotime($data->event_start)); ?></td>
					<td class="event-end"><?php echo date(get_option('time_format'), strtotime($data->event_end)); ?></td>
					<td class="event-description"><?php echo $data->description ?></td>
					<td class="event-user-id"><?php
						$user = ($data->user_id != '-1') ? get_userdata($data->user_id) : false;
						if ($user) {
							echo $user->display_name;
						} ?>
					</td>
					<td>
						<a class="button icon dashicons-edit edit-event-button" data-id="<?php echo $data->id ?>" title="<?php _e('Edit event in the form below', 'mp-timetable') ?>"></a>
						<a class="button icon dashicons-trash delete-event-button" data-id="<?php echo $data->id ?>" title="<?php _e('Delete', 'mp-timetable') ?>"></a>
						<span class="spinner left"></span>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
</div>