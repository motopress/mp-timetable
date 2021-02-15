import edit from './edit';
import { __ } from 'wp.i18n';

const {
	registerBlockType,
} = wp.blocks;

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