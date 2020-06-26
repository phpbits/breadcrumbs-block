/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Fragment, Component } from '@wordpress/element';
import { withSpokenMessages } from '@wordpress/components';

/**
 * Block edit function
 */
class Edit extends Component {
	render() {
		const { postTitle } = this.props;

		return (
			<Fragment>
				{ postTitle }
				{ /* {isSelected && <Inspector {...this.props} /> } */ }
			</Fragment>
		);
	}
}

export default compose( [
	withSelect( ( select, props ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );

		return {
			postTitle: getEditedPostAttribute( 'title' ),
		};
	} ),
	withSpokenMessages,
] )( Edit );
