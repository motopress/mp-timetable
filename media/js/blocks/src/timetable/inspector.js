import { get } from "lodash";

const {__} = wp.i18n;
const {Component} = wp.element;

const {
    InspectorControls,
} = wp.editor;

const {
    SelectControl,
    CheckboxControl,
    PanelBody,
    TextControl,

} = wp.components;

class Inspector extends Component {
    constructor() {
        super(...arguments);
        
        this.setOptions = this.setOptions.bind( this );
    }
    
    setOptions(data) {
        let options = [];
        if (data) {
            options = data.map((event => {
                return {
                    value: event.id.toString(),
                    label: __( get( event, [ 'title', 'raw' ] ) || get( event, [ 'name' ] ), 'mp-timetable' )                    
                }
            }));
        }
        return options;
    }
    
    render() {
        
        const {
            attributes: {
                col,
                events,
                event_categ,

                increment,
                view,
                label,

                hide_label,
                hide_hrs,
                hide_empty_rows,

                title,
                time,
                sub_title,
                description,
                user,

                disable_event_url,
                text_align,
                id,

                row_height,
                font_size,
                responsive,                            
            },

            selectedEvents,
            selectedColumns,
            selectedEventCategories,

            setAttributes,
        } = this.props;
        
        return (
            <InspectorControls>
				<PanelBody
					title={__('Settings', 'mp-timetable')}
				>
					<SelectControl
						multiple
						size="7"
						label={__('Columns (required)', 'mp-timetable')}
						help={__('In order to display multiple points hold ctrl/cmd button.', 'mp-timetable')}
						value={col}
						onChange={col => setAttributes({col})}
						options={this.setOptions(selectedColumns)}
					/>
					<SelectControl
						multiple
						size="7"
						label={__('Specific events', 'mp-timetable')}
						value={events}
						onChange={events => setAttributes({events})}
						options={this.setOptions(selectedEvents )}
					/>
					<SelectControl
						multiple
						size="7"
						label={__('Event categories', 'mp-timetable')}
						value={event_categ}
						onChange={event_categ => setAttributes({event_categ})}
						options={this.setOptions(selectedEventCategories)}
					/>
					<SelectControl
						label={__('Time frame for event', 'mp-timetable')}
						value={increment}
						onChange={increment => setAttributes({ increment })}
						options={[
							{ value: '1'   , label: __( 'Hour (1h)'           , 'mp-timetable' ) },
							{ value: '0.5' , label: __( 'Half hour (30min)'   , 'mp-timetable' ) },
							{ value: '0.25', label: __( 'Quater hour (15min)' , 'mp-timetable' ) },
						]}
					/>
					<SelectControl
						label={__('Filter events style', 'mp-timetable')}
						value={view}
						onChange={view => setAttributes({ view })}
						options={[
							{ value: 'dropdown_list', label: __( 'Dropdown list', 'mp-timetable' ) },
							{ value: 'tabs'         , label: __( 'Tabs'         , 'mp-timetable' ) },
						]}
					/>
					<TextControl
						label={__('Filter title to display all events', 'mp-timetable')}
						value={label}
						onChange={label => setAttributes({ label })}
					/>
					<SelectControl
						label={__('Hide \'All Events\' option', 'mp-timetable')}
						value={hide_label}
						onChange={hide_label => setAttributes({ hide_label })}
						options={[
							{ value: '0',  label: __( 'No' , 'mp-timetable' ) },
							{ value: '1',  label: __( 'Yes', 'mp-timetable' ) },
						]}
					/>
					<SelectControl
						label={__('Hide column with hours', 'mp-timetable')}
						value={hide_hrs}
						onChange={hide_hrs => setAttributes({ hide_hrs })}
						options={[
							{ value: '0', label: __( 'No' , 'mp-timetable' ) },
							{ value: '1', label: __( 'Yes', 'mp-timetable' ) },
						]}
					/>
					<SelectControl
						label={__('Do not display empty rows', 'mp-timetable')}
						value={hide_empty_rows}
						onChange={hide_empty_rows => setAttributes({ hide_empty_rows })}
						options={[
							{ value: '0', label: __( 'No' , 'mp-timetable' ) },
							{ value: '1', label: __( 'Yes', 'mp-timetable' ) },
						]}
					/>
					<CheckboxControl
						label="Title"
						checked={title == '1' ? true : false}
						onChange={(title) => {
							setAttributes({title: title ? '1' : '0'}) 
						}}
					/>
					<CheckboxControl
						label="Time"
						checked={time == '1' ? true : false}
						onChange={(time) => { setAttributes({time: time ? '1' : '0'}) }}
					/>
					<CheckboxControl
						label="Subtitle"
						checked={sub_title == '1' ? true : false}
						onChange={(sub_title) => { setAttributes({sub_title: sub_title ? '1' : '0'}) }}
					/>
					<CheckboxControl
						label="Description"
						checked={description == '1' ? true : false}
						onChange={(description) => { setAttributes({description: description ? '1' : '0'}) }}
					/>
					<CheckboxControl
						label="Event Head"
						checked={user == '1' ? true : false}
						onChange={(user) => { setAttributes({user: user ? '1' : '0'}) }}
					/>
					<SelectControl
						label={__('Disable event link', 'mp-timetable')}
						value={disable_event_url}
						onChange={disable_event_url => setAttributes({ disable_event_url })}
						options={[
							{ value: '0', label: __( 'No' , 'mp-timetable' ) },
							{ value: '1', label: __( 'Yes', 'mp-timetable' ) },
						]}
					/>
					<SelectControl
						label={__('Horizontal align', 'mp-timetable')}
						value={text_align}
						onChange={text_align => setAttributes({ text_align })}
						options={[
							{ value: 'center', label: __( 'Center', 'mp-timetable' ) },
							{ value: 'left',   label: __( 'Left'  , 'mp-timetable' ) },
							{ value: 'right',  label: __( 'Right' , 'mp-timetable' ) },
						]}
					/>
					<TextControl
						label={__('Block height in pixels', 'mp-timetable')}
						type={'number'}
						value={isNaN(row_height) ? 0 : parseInt(row_height)}
						onChange={row_height => {
							setAttributes({ row_height: row_height.toString() });
						}}
						min={1}
						step={1}
					/>
					<TextControl
						label={__('Base font size', 'mp-timetable')}
						help={__('Base font size for the table. Example 12px, 2em, 80%.', 'mp-timetable')}
						type={'number'}
						value={isNaN(font_size) ? 0 : parseInt(font_size)}
						onChange={font_size => {
							setAttributes({ font_size: font_size.toString() });
						}}
						min={1}
						step={1}
					/>
					<SelectControl
						label={__('Responsive', 'mp-timetable')}
						value={responsive}
						onChange={responsive => setAttributes({responsive})}
						options={[
							{ value: '0', label: __( 'No' , 'mp-timetable' ) },
							{ value: '1', label: __( 'Yes', 'mp-timetable' ) },
						]}
					/>
					<TextControl
						label={__('Unique ID', 'mp-timetable')}
						help={__('If you use more than one table on a page specify the unique ID for a timetable. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mp-timetable')}
						value={id}
						onChange={id => setAttributes({id})}
					/>
				</PanelBody>
            </InspectorControls>
        );
    }
}

export default (Inspector);