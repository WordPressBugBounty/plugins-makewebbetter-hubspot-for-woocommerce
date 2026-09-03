/**
 * Abandoned cart capture for WooCommerce block (Gutenberg) checkout.
 *
 * Listens for an email entry on the block checkout form and sends it
 * to the server so the cart can be tracked even if the user never
 * completes the order.
 *
 * Localized via hubwooBlockCheckout: { ajaxUrl, nonce, locale }
 */
( function () {
	'use strict';

	if ( typeof hubwooBlockCheckout === 'undefined' ) {
		return;
	}

	var ajaxUrl = hubwooBlockCheckout.ajaxUrl;
	var nonce   = hubwooBlockCheckout.nonce;
	var locale  = hubwooBlockCheckout.locale;

	var lastCapturedEmail = '';
	var debounceTimer;

	function captureCart( email ) {
		if ( ! email || email.indexOf( '@' ) === -1 || email === lastCapturedEmail ) {
			return;
		}
		lastCapturedEmail = email;

		var formData = new FormData();
		formData.append( 'action', 'hubwoo_block_checkout_save_cart' );
		formData.append( 'email',  email );
		formData.append( 'nonce',  nonce );
		formData.append( 'locale', locale );

		fetch( ajaxUrl, { method: 'POST', body: formData } );
	}

	function attachToEmailField() {
		// WooCommerce block checkout renders email inside the contact information block.
		// Try several selectors to be resilient across WC versions and themes.
		var selectors = [
			'.wc-block-checkout__contact-fields input[type="email"]',
			'.wp-block-woocommerce-checkout-contact-information-block input[type="email"]',
			'.wc-block-components-form input[type="email"]',
			'input[autocomplete="email"][id*="email"]',
		];

		var input = null;
		for ( var i = 0; i < selectors.length; i++ ) {
			input = document.querySelector( selectors[ i ] );
			if ( input ) {
				break;
			}
		}

		if ( ! input ) {
			return false;
		}

		// Use blur so we only fire once the user has finished typing.
		input.addEventListener( 'blur', function () {
			clearTimeout( debounceTimer );
			debounceTimer = setTimeout( function () {
				captureCart( input.value.trim() );
			}, 300 );
		} );

		return true;
	}

	// Block checkout is rendered by React — the inputs may not exist yet when
	// this script runs.  Poll briefly until the form appears.
	var pollAttempts = 0;
	function pollForCheckout() {
		if ( attachToEmailField() ) {
			return;
		}
		if ( ++pollAttempts < 30 ) {
			setTimeout( pollForCheckout, 500 );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', pollForCheckout );
	} else {
		pollForCheckout();
	}
} )();
