<thead>
<tr class="mptt-shortcode-row">
	<?php foreach ($header_items as $key => $column):
		if (!$column[ 'output' ]) {
			continue;
		} ?>
		<th data-index="<?php echo esc_attr( $key ); ?>" data-column-id="<?php echo esc_attr( $column[ 'id' ] ); ?>"><?php echo esc_html( $column[ 'title' ] ); ?></th>
	<?php endforeach; ?>
</tr>
</thead>