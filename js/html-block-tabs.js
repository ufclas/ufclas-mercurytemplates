/**
 * Companion to css/html-block-tabs.css, which hides the CSS and JavaScript
 * tabs in the Custom HTML block's "Edit HTML" modal for non-superadmins.
 *
 * The hidden tabs are still reachable with the keyboard (Arrow/Home/End
 * select them and leave the modal blank), so stop those keys from reaching
 * the tab list. Only the HTML tab remains, so nothing is lost.
 */
( function () {
	var keys = [ 'ArrowLeft', 'ArrowRight', 'Home', 'End' ];

	document.addEventListener(
		'keydown',
		function ( event ) {
			if ( keys.indexOf( event.key ) === -1 ) {
				return;
			}
			var target = event.target;
			if (
				target &&
				target.closest &&
				target.closest( '.block-library-html__modal [role="tab"]' )
			) {
				event.stopPropagation();
			}
		},
		true
	);
} )();
