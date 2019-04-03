import edit from './edit';

const {
	registerBlockType,
} = wp.blocks;

const { __ } = wp.i18n;

export default registerBlockType(
    'mp-timetable/timetable',
    {
        title: __('Timetable', 'mp-timetable'),
        category: 'common',
		icon: 'calendar',
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