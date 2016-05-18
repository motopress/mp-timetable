<table id="events-list" class="widefat fixed striped">
	<thead>
	<tr>
		<th><?php _e('Column', 'mp-timetable') ?></th>
		<th><?php _e('Start', 'mp-timetable') ?></th>
		<th><?php _e('End', 'mp-timetable') ?></th>
		<th><?php _e('Description', 'mp-timetable') ?></th>
		<th><?php _e('Event Head', 'mp-timetable') ?></th>
		<th><?php _e('Actions', 'mp-timetable') ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($event_data)): ?>
		<?php foreach ($event_data as $data): ?>
			<tr data-id="<?php echo $data->id ?>">
				<td class="event-column"><?php echo get_the_title($data->column_id); ?></td>
				<td class="event-start"><?php echo $data->event_start; ?></td>
				<td class="event-end"><?php echo $data->event_end; ?></td>
				<td class="event-description"><?php echo $data->description ?></td>
				<td class="event-user-id"><?php
					$user = ($data->user_id != '-1')? get_userdata($data->user_id) : false;
					if($user){
						echo $user->display_name;
					}?></td>
				<td>
					<a id="edit-event-button" class="button" data-id="<?php echo $data->id ?>"><?php _e('Edit', 'mp-timetable') ?></a>
					<a id="delete-event-button" class="button" data-id="<?php echo $data->id ?>"><?php _e('Delete', 'mp-timetable') ?></a>
					<span class="spinner left"></span>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>
