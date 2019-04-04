import Inspector from './inspector';

import { pick, isEqual } from "lodash";

const {__} = wp.i18n;
const { Component, Fragment } = wp.element;
const { compose } = wp.compose;

const {
    Disabled,
    ServerSideRender
} = wp.components;

const {
	withSelect
} = wp.data;

class Edit extends Component {
    constructor() {
        super(...arguments);
    }

    initTable(){
        //Set timer and check when table is load fully, and then initialize table data, and after stop timer
        const waitLoadTable = setInterval( () => {
            if ($('.mptt-shortcode-wrapper').length && !$('.mptt-shortcode-wrapper').hasClass('table-init')){
                clearInterval(waitLoadTable);
                window.mptt.tableInit();
            }
        }, 1);
    }

    componentDidUpdate(prevProps, prevState) {
        if (!isEqual(this.props.attributes, prevProps.attributes)) {
            this.initTable();
        }
    }

    componentDidMount() {
        this.initTable();
    }

    render() {

        const {
            attributes: {
                events,
                event_categ
            }
        } = this.props;    

        return (
            <Fragment>
                <Inspector {...this.props }/>
                <Disabled>
                    <ServerSideRender
                        block="mp-timetable/timetable"
                        attributes={this.props.attributes}
                    />
                </Disabled>
            </Fragment>
        );
    }
}

export default compose([
    withSelect(( select, props ) => {
        const { getEntityRecords, getCategories } = select( "core" );

        let events  		= getEntityRecords( "postType", "mp-event", {per_page: -1, orderby: 'title', order: 'asc'} );
        let columns 		= getEntityRecords( "postType", "mp-column", {per_page: -1} );
        let eventCategories = getEntityRecords( "taxonomy", "mp-event_category", {per_page: -1} );

        return {
            selectedEvents:  events  ? events .map((event)  => {
                return pick( event,  [ 'id', 'title' ])
            }) : null,

            selectedColumns: columns ? columns.map((column) => {
                return pick( column, [ 'id', 'title' ])
            }) : null,

            selectedEventCategories: eventCategories ? eventCategories.map((categorie) => {
                return pick( categorie, [ 'id', 'name' ])
            }) : null
        };
    }),
])(Edit);
