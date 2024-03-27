<?php
/**
 * @link       https://webyx.it/wfe-guide
 * @since      1.0.0
 * @package    webyx-fep
 * @subpackage webyx-fep/templates
 */
	if ( ! defined( 'WPINC' ) ) {
		die;
	} ?>  
	<!doctype html>
	<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php wp_head(); ?>
		</head>
		<body <?php body_class( 'webyx-menu' ); ?>>
		<?php wp_body_open(); ?>