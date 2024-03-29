import { Select2 } from '@elementor/app-ui';

/**
 * Main component.
 *
 * @param {any} props
 * @return {any} -
 * @class
 */
export default function ConditionSubId( props ) {
	if ( ! props.sub || ! Object.keys( props.subIdAutocomplete ).length ) {
		return '';
	}

	const settings = React.useMemo( () => getSettings( props.subIdAutocomplete ), [ props.subIdAutocomplete ] );

	const onChange = ( e ) => props.updateConditions( props.id, { subId: e.target.value } );

	return (
		<div className="e-site-editor-conditions__input-wrapper">
			<Select2
				onChange={ onChange }
				value={ props.subId }
				settings={ settings }
				options={ props.subIdOptions }
			/>
		</div>
	);
}

/**
 * Get settings for the select2 base on the autocomplete settings,
 * that passes as a prop
 *
 * @param {any} autocomplete
 * @return {Object} -
 */
function getSettings( autocomplete ) {
	return {
		allowClear: false,
		placeholder: __( 'All', 'kitify' ),
		dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
		ajax: {
			transport( params, success, failure ) {
				return elementorCommon.ajax.addRequest( 'kitify_query_control_filter_autocomplete', {
					data: {
						q: params.data.q,
						autocomplete,
					},
					success,
					error: failure,
				} );
			},
			data( params ) {
				return {
					q: params.term,
					page: params.page,
				};
			},
			cache: true,
		},
		escapeMarkup( markup ) {
			return markup;
		},
		minimumInputLength: 1,
	};
}

ConditionSubId.propTypes = {
	subIdAutocomplete: PropTypes.object,
	id: PropTypes.string.isRequired,
	sub: PropTypes.string,
	subId: PropTypes.string,
	updateConditions: PropTypes.func,
	subIdOptions: PropTypes.array,
};

ConditionSubId.defaultProps = {
	subId: '',
	subIdOptions: [],
};
