<?php
/**
 * @package Ahoy_Dolly
 * @version 1.7.2
 */
/*
Plugin Name: Ahoy Dolly
Plugin URI: http://wordpress.org/plugins/ahoy-dolly/
Description: The "Hello Dolly!" plugin but print insults in a Pirate way! When activated you will randomly see an insult from <cite>Pirate Monkeyness (https://pirate.monkeyness.com/) </cite> in the upper right of your admin screen on every page.
Author: Matteo Gaggiano
Version: 1.7.2
Author URI: https://github.com/marchrius
*/

function ahoy_dolly_get_lyric() {
	// Get the text from external api
	$text = file_get_contents('https://pirate.monkeyness.com/api/insult');

	// And then randomly choose a line.
	return wptexturize( sanitize_text_field( $text ) );
}

// This just echoes the chosen line, we'll position it later.
function ahoy_dolly() {
	$chosen = ahoy_dolly_get_lyric();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="dolly"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Insult from Pirate Monkeyness, by <a href="https://pirate.monkeyness.com/">Tim Moses</a>. Please take this for what it is, a joke:', 'hello-dolly' ),
		$lang,
		$chosen
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'ahoy_dolly' );

// We need some CSS to position the paragraph.
function dolly_css() {
	echo "
	<style type='text/css'>
	#dolly {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #dolly {
		float: left;
	}
	.block-editor-page #dolly {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#dolly,
		.rtl #dolly {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}

add_action( 'admin_head', 'dolly_css' );
