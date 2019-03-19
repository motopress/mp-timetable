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

    componentDidUpdate(prevProps, prevState) {
        if (!isEqual(this.props.attributes, prevProps.attributes)) {
            setTimeout(() => {
                window.mptt.tableInit();
            }, 1000);
        }
    }

    componentDidMount() {
        setTimeout(() => {
            window.mptt.tableInit();
        }, 1000);
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
                        block="mp-timetable/time-table"
                        attributes={this.props.attributes}
                    />
                </Disabled>

                {
                    (events && event_categ) ? (!events.length || !event_categ.length) && <div>No event selected</div> : null
                }

            </Fragment>
        );
    }
}

export default compose([
    withSelect(( select, props ) => {
        const { getEntityRecords, getCategories } = select( "core" );

        let events  = getEntityRecords( "postType", "mp-event"  );
        let columns = getEntityRecords( "postType", "mp-column" );
        
        let eventCategories = getEntityRecords( "taxonomy", "mp-event_category" );

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
