<div class="wrap">
	<h1 class="wp-heading-inline">Help</h1>
	<p class="alignright"><?php
		echo sprintf(__( 'If you like %1$s please leave us a %2$s rating.', 'mp-timetable' ),
			sprintf( '<strong>%s</strong>', esc_html__( 'Timetable and Event Schedule', 'mp-timetable' ) ),
			'<a href="https://wordpress.org/support/plugin/mp-timetable/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
		);
	?></p>
	<hr/>
	<h2>Quick start guide</h2>
	<ol>
		<li>
			<p><strong>Add Columns</strong><br/>
			Once after plugin installation you should add Columns and only then add Events in order to assign them to columns.</p>
		</li>
		<li>
			<p><strong>Add Events</strong><br/>
			The events will be featured in the actual timetable and the details of each event will be displayed on its individual page.</p>
			<ol>
				<li>
					<p><strong>Add Event Category</strong><br/>
					The events can be presented under separate Categories and can be further chosen in shortcode parameters.</p>
				</li>
			</ol>
		</li>
		<li>
			<p><strong>Add Timetable to a page</strong></p>
			<ol>
				<li>In Classic Editor find "Timetable" icon on TinyMCE panel.</li>
				<li>Build Timetable shortcode manually.
				<p>Shortcode <code>[mp-timetable ... ]</code> attributes:</p>
				<ul>
					<li><code>col</code> - comma-separated column IDs.</li>
					<li><code>events</code> - comma-separated event IDs.</li>
					<li><code>event_categ</code> - comma-separated event category IDs.</li>
					<li><code>increment</code> - hour measure; possible values <kbd>1</kbd> - hour (1h), <kbd>0.5</kbd> - half hour (30min), <kbd>0.25</kbd> - quarter hour (15min).</li>
					<li><code>title</code> - display event title; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>time</code> - display event time; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>sub-title</code> - display event subtitle; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>description</code> - display event description; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>user</code> - display event head; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>row_height</code> - event block height in pixels; example <kbd>45</kbd></li>
					<li><code>font_size</code> - base font size for the table; example <kbd>12px</kbd>, <kbd>2em</kbd>, <kbd>80%</kbd>.</li>
					<li><code>view</code> - filter style; possible values <kbd>dropdown_list</kbd> or <kbd>tabs</kbd>.</li>
					<li><code>label</code> - filter label; default is <kbd>All Events</kbd>.</li>
					<li><code>hide_label</code> - display 'All Events' label or not; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>hide_hrs</code> - hide first (hours) column; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>hide_empty_rows</code> - hide empty rows; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>group</code> - merge cells with common events; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>disable_event_url</code> - disable event URL; possible values <kbd>1</kbd> or <kbd>0</kbd>.</li>
					<li><code>text_align</code> - horizontal align; possible values <kbd>left</kbd>, <kbd>center</kbd>, <kbd>right</kbd>.</li>
					<li><code>text_align_vertical</code> - vertical align ; possible values <kbd>default</kbd>, <kbd>top</kbd>, <kbd>middle</kbd>, <kbd>bottom</kbd>.</li>
					<li><code>id</code> - unique ID.</li>
					<li><code>custom_class</code> - CSS class.</li>
					<li><code>responsive</code> - mobile behavior; possible values <kbd>1</kbd> - display as list, <kbd>0</kbd> - display as table.</li>
				</ul>
			</li>
			</ol>
		</li>
	</ol>
</div>
