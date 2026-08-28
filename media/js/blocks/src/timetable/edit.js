import Inspector from './inspector';

const { serverSideRender: ServerSideRender } = wp;
const { Component, Fragment } = wp.element;
const { compose } = wp.compose;

const {
    Disabled,
    Spinner,
    Placeholder
} = wp.components;

const {
	withSelect
} = wp.data;

class Edit extends Component {
    constructor() {
        super(...arguments);
    }

    componentDidMount() {
        this.mutationObserver = new MutationObserver( () => {
            window.mptt.tableInit( this.preview );
        } );

        this.mutationObserver.observe( this.preview, {
            childList: true,
            subtree: true
        } );
    }

    componentWillUnmount() {
        this.mutationObserver.disconnect();
    }

    placeholder() {
        return (
            <Placeholder>
                <Spinner />
            </Placeholder>
        );
    }

    render() {
        return (
            <Fragment>
                <Inspector { ...this.props }/>
                <div ref={ ( preview ) => { this.preview = preview; } }>
                    <Disabled>
                        <ServerSideRender
                            block="mp-timetable/timetable"
                            attributes={ this.props.attributes }
                            LoadingResponsePlaceholder={ this.placeholder }
                        />
                    </Disabled>
                </div>
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
                const { id, title } = event;
                return { id, title }
            }) : null,

            selectedColumns: columns ? columns.map((column) => {
                const { id, title } = column;
                return { id, title }
            }) : null,

            selectedEventCategories: eventCategories ? eventCategories.map((categorie) => {
                const { id, name } = categorie;
                return { id, name }
            }) : null
        };
    }),
])(Edit);
