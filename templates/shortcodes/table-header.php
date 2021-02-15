<thead>
<tr class="mptt-shortcode-row">
	<?php foreach ($header_items as $key => $column):
		if (!$column[ 'output' ]) {
			continue;
		} ?>
		<th data-index="<?php echo $key ?>" data-column-id="<?php echo $column[ 'id' ] ?>"><?php echo $column[ 'title' ] ?></th>
	<?php endforeach; ?>
</tr>
</thead>