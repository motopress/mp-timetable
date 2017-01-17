<thead>
<tr class="mptt-shortcode-row">
	<?php foreach ($header_items as $column):
		if (!$column[ 'output' ]) {
			continue;
		} ?>
		<th data-column-id="<?php echo $column[ 'id' ] ?>"><?php echo $column[ 'title' ] ?></th>
	<?php endforeach; ?>
</tr>
</thead>