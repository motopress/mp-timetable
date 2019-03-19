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
                    label: __( get( event, [ 'title', 'raw' ] ) || get( event, [ 'name' ] ), 'getwid' )                    
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
                <SelectControl
                    multiple
					label={__('Columns', 'getwid')}
					value={col}
					onChange={col => setAttributes({col})}
					options={this.setOptions(selectedColumns)}
				/>
                <SelectControl
                    multiple
					label={__('Events', 'getwid')}
					value={events}
					onChange={events => setAttributes({events})}
					options={this.setOptions(selectedEvents )}
				/>
                <SelectControl
                    multiple
					label={__('Event categories', 'getwid')}
					value={event_categ}
					onChange={event_categ => setAttributes({event_categ})}
					options={this.setOptions(selectedEventCategories)}
				/>
                <SelectControl
                    label={__('Hour measure', 'getwid')}
                    value={increment}
                    onChange={increment => setAttributes({ increment })}
                    options={[
                        { value: '1'   , label: __( 'Hour (1h)'           , 'getwid' ) },
                        { value: '0.5' , label: __( 'Half hour (30min)'   , 'getwid' ) },
                        { value: '0.25', label: __( 'Quater hour (15min)' , 'getwid' ) },
                    ]}
                />
                <SelectControl
                    label={__('Filter style', 'getwid')}
                    value={view}
                    onChange={view => setAttributes({ view })}
                    options={[
                        { value: 'dropdown_list', label: __( 'Dropdown list', 'getwid' ) },
                        { value: 'tabs'         , label: __( 'Tabs'         , 'getwid' ) },
                    ]}
                />
                <TextControl
                    label={__('Filter label', 'getwid')}
                    type={'string'}
                    value={label}
                    onChange={label => setAttributes({ label })}
                />
                <SelectControl
                    label={__('Hide \'All events\' view', 'getwid')}
                    value={hide_label}
                    onChange={hide_label => setAttributes({ hide_label })}
                    options={[
                        { value: '0',  label: __( 'No' , 'getwid' ) },
                        { value: '1',  label: __( 'Yes', 'getwid' ) },
                    ]}
                />
                <SelectControl
                    label={__('Hide first (hours) column', 'getwid')}
                    value={hide_hrs}
                    onChange={hide_hrs => setAttributes({ hide_hrs })}
                    options={[
                        { value: '0', label: __( 'No' , 'getwid' ) },
                        { value: '1', label: __( 'Yes', 'getwid' ) },
                    ]}
                />
                <SelectControl
                    label={__('Hide empty rows', 'getwid')}
                    value={hide_empty_rows}
                    onChange={hide_empty_rows => setAttributes({ hide_empty_rows })}
                    options={[
                        { value: '0', label: __( 'No' , 'getwid' ) },
                        { value: '1', label: __( 'Yes', 'getwid' ) },
                    ]}
                />

                <PanelBody title={ __( 'Fields to display', 'getwid' ) } initialOpen={false}>
                    <CheckboxControl
                        label="Title"
                        checked={title == '0' ? false : true}
                        onChange={(title) => {
                            setAttributes({title: title ? '1' : '0'}) 
                        }}
                    />
                    <CheckboxControl
                        label="Time"
                        checked={time == '0' ? false : true}
                        onChange={(time) => { setAttributes({time: time ? '1' : '0'}) }}
                    />
                    <CheckboxControl
                        label="Subtitle"
                        checked={sub_title == '0' ? false : true}
                        onChange={(sub_title) => { setAttributes({sub_title: sub_title ? '1' : '0'}) }}
                    />
                    <CheckboxControl
                        label="Description"
                        checked={description == '0' ? false : true}
                        onChange={(description) => { setAttributes({description: description ? '1' : '0'}) }}
                    />
                    <CheckboxControl
                        label="User"
                        checked={user == '0' ? false : true}
                        onChange={(user) => { setAttributes({user: user ? '1' : '0'}) }}
                    />
                </PanelBody>

                <SelectControl
                    label={__('Disable event URL', 'getwid')}
                    value={disable_event_url}
                    onChange={disable_event_url => setAttributes({ disable_event_url })}
                    options={[
                        { value: '0', label: __( 'No' , 'getwid' ) },
                        { value: '1', label: __( 'Yes', 'getwid' ) },
                    ]}
                />
                <SelectControl
                    label={__('Text align', 'getwid')}
                    value={text_align}
                    onChange={text_align => setAttributes({ text_align })}
                    options={[
                        { value: 'center', label: __( 'Center', 'getwid' ) },
                        { value: 'left',   label: __( 'Left'  , 'getwid' ) },
                        { value: 'right',  label: __( 'Right' , 'getwid' ) },
                    ]}
                />
                <TextControl
                    label={__('Id', 'getwid')}
                    type={'string'}
                    value={id}
                    onChange={id => setAttributes({id})}
                />
                <TextControl
                    label={__('Row height (in px)', 'getwid')}
                    type={'number'}
                    value={isNaN(row_height) ? 0 : parseInt(row_height)}
                    onChange={row_height => {
                        setAttributes({ row_height: row_height.toString() });
                    }}
                    min={1}
                    step={1}
                />
                <TextControl
                    label={__('Base Font Size (px, em, %.)', 'getwid')}
                    type={'number'}
                    value={isNaN(font_size) ? 0 : parseInt(font_size)}
                    onChange={font_size => {
                        setAttributes({ font_size: font_size.toString() });
                    }}
                    min={1}
                    step={1}
                />
                <SelectControl
                    label={__('Responsive', 'getwid')}
                    value={responsive}
                    onChange={responsive => setAttributes({responsive})}
                    options={[
                        { value: '0', label: __( 'No' , 'getwid' ) },
                        { value: '1', label: __( 'Yes', 'getwid' ) },
                    ]}
                />                
            </InspectorControls>
        );
    }
}

export default (Inspector);