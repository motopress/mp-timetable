import edit from './edit';

const {
	registerBlockType,
} = wp.blocks;

const { __ } = wp.i18n;

export default registerBlockType(
    'mp-timetable/time-table',
    {
        title: __('Time Table', 'mp-timetable'),
        category: 'common',
        supports: {
			align: [ 'wide', 'full' ],
		},
        getEditWrapperProps( attributes ) {
            const { align } = attributes;
            if ( [ 'wide', 'full' ].includes( align ) ) {
                return { 'data-align': align };
            }
        },
        edit,
        save: () => {
            return null;
        },
    }
)