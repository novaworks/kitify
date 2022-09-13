import { Dialog } from '@elementor/app-ui';
import { Context as TemplatesContext } from '../context/templates';

export default function DialogDelete( props ) {
	const { deleteTemplate, findTemplateItemInState } = React.useContext( TemplatesContext ),
		template = findTemplateItemInState( props.id );

	const closeDialog = ( shouldUpdate ) => {
		props.setId( null );

		if ( shouldUpdate ) {
			deleteTemplate( props.id );
		}
	};

	if ( ! props.id ) {
		return '';
	}

	return (
		<Dialog
			title={ __( 'Move Item To Trash', 'kitify' ) }
			text={ __( 'Are you sure you want to move this item to trash:', 'kitify' ) + ` "${ template.title }"` }
			onSubmit={ () => closeDialog( true ) }
			approveButtonText={ __( 'Move to Trash', 'kitify' ) }
			approveButtonOnClick={ () => closeDialog( true ) }
			approveButtonColor="danger"
			dismissButtonText={ __( 'Cancel', 'kitify' ) }
			dismissButtonOnClick={ () => closeDialog() }
			onClose={ () => closeDialog() }
		/>
	);
}

DialogDelete.propTypes = {
	id: PropTypes.number,
	setId: PropTypes.func.isRequired,
};
