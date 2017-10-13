<?php
/**
 * Header elements.
 *
 * @package GeneratePress
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'generate_construct_header' ) ) {
	add_action( 'generate_header', 'generate_construct_header' );
	/**
	 * Build the header.
	 *
	 * @since 1.3.42
	 */
	function generate_construct_header() {
		?>
		<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" id="masthead" <?php generate_header_class(); ?>>
			<div <?php generate_inside_header_class(); ?>>
				<?php do_action( 'generate_before_header_content' ); ?>
				<?php generate_header_items(); ?>
				<?php do_action( 'generate_after_header_content' ); ?>
			</div><!-- .inside-header -->
		</header><!-- #masthead -->
		<?php
	}
}

if ( ! function_exists( 'generate_header_items' ) ) {
	/**
	 * Build the header contents.
	 * Wrapping this into a function allows us to customize the order.
	 *
	 * @since 1.2.9.7
	 */
	function generate_header_items() {
		generate_construct_header_widget();
		generate_construct_site_title();
		generate_construct_logo();
	}
}

if ( ! function_exists( 'generate_construct_logo' ) ) {
	/**
	 * Build the logo
	 *
	 * @since 1.3.28
	 */
	function generate_construct_logo() {
		$logo_url = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
		$logo_url = ( $logo_url ) ? $logo_url[0] : generate_get_setting( 'logo' );

		$logo_url = esc_url( apply_filters( 'generate_logo', $logo_url ) );
		$retina_logo_url = esc_url( apply_filters( 'generate_retina_logo', generate_get_setting( 'retina_logo' ) ) );

		// If we don't have a logo, bail
		if ( empty( $logo_url ) ) {
			return;
		}

		do_action( 'generate_before_logo' );

		$attr = apply_filters( 'generate_logo_attributes', array(
			'class' => 'header-image',
			'alt'	=> esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			'src'	=> $logo_url,
			'title'	=> esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ),
		) );

		if ( '' !== $retina_logo_url ) {
			$attr[ 'srcset' ] = $logo_url . ' 1x, ' . $retina_logo_url . ' 2x';
		}

		$attr = array_map( 'esc_attr', $attr );

		$html_attr = '';
		foreach ( $attr as $name => $value ) {
			$html_attr .= " $name=" . '"' . $value . '"';
		}

		// Print our HTML
		echo apply_filters( 'generate_logo_output', sprintf(
			'<div class="site-logo">
				<a href="%1$s" title="%2$s" rel="home">
					<img %3$s />
				</a>
			</div>',
			esc_url( apply_filters( 'generate_logo_href' , home_url( '/' ) ) ),
			esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			$html_attr
		), $logo_url, $html_attr );

		do_action( 'generate_after_logo' );
	}
}

if ( ! function_exists( 'generate_construct_site_title' ) ) {
	/**
	 * Build the site title and tagline.
	 *
	 * @since 1.3.28
	 */
	function generate_construct_site_title() {
		$generate_settings = wp_parse_args(
			get_option( 'generate_settings', array() ),
			generate_get_defaults()
		);

		// Get the title and tagline
		$title = get_bloginfo( 'title' );
		$tagline = get_bloginfo( 'description' );

		// If the disable title checkbox is checked, or the title field is empty, return true
		$disable_title = ( '1' == $generate_settings[ 'hide_title' ] || '' == $title ) ? true : false;

		// If the disable tagline checkbox is checked, or the tagline field is empty, return true
		$disable_tagline = ( '1' == $generate_settings[ 'hide_tagline' ] || '' == $tagline ) ? true : false;

		// Build our site title
		$site_title = apply_filters( 'generate_site_title_output', sprintf(
			'<%1$s class="main-title" itemprop="headline">
				<a href="%2$s" rel="home">
					%3$s
				</a>
			</%1$s>',
			( is_front_page() && is_home() ) ? 'h1' : 'p',
			esc_url( apply_filters( 'generate_site_title_href', home_url( '/' ) ) ),
			get_bloginfo( 'name' )
		));

		// Build our tagline
		$site_tagline = apply_filters( 'generate_site_description_output', sprintf(
			'<p class="site-description">
				%1$s
			</p>',
			html_entity_decode( get_bloginfo( 'description', 'display' ) )
		));

		// Site title and tagline
		if ( false == $disable_title || false == $disable_tagline ) {
			echo apply_filters( 'generate_site_branding_output', sprintf(
				'<div class="site-branding">
					%1$s
					%2$s
				</div>',
				( ! $disable_title ) ? $site_title : '',
				( ! $disable_tagline ) ? $site_tagline : ''
			) );
		}
	}
}

if ( ! function_exists( 'generate_construct_header_widget' ) ) {
	/**
	 * Build the header widget.
	 *
	 * @since 1.3.28
	 */
	function generate_construct_header_widget() {
		if ( is_active_sidebar('header') ) : ?>
			<div class="header-widget">
				<?php dynamic_sidebar( 'header' ); ?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'generate_top_bar' ) ) {
	add_action( 'generate_before_header', 'generate_top_bar', 5 );
	/**
	 * Build our top bar.
	 *
	 * @since 1.3.45
	 */
	function generate_top_bar() {

		if ( ! is_active_sidebar( 'top-bar' ) ) {
			return;
		}

		?>
		<div <?php generate_top_bar_class(); ?>>
			<div class="inside-top-bar<?php if ( 'contained' == generate_get_setting( 'top_bar_inner_width' ) ) echo ' grid-container grid-parent'; ?>">
				<?php dynamic_sidebar( 'top-bar' ); ?>
			</div>
		</div>
		<?php

	}
}

if ( ! function_exists( 'generate_pingback_header' ) ) {
	add_action( 'wp_head', 'generate_pingback_header' );
	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 *
	 * @since 1.3.42
	 */
	function generate_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

if ( ! function_exists( 'generate_add_viewport' ) ) {
	add_action( 'wp_head', 'generate_add_viewport' );
	/**
	 * Add viewport to wp_head.
	 *
	 * @since 1.1.0
	 */
	function generate_add_viewport() {
		echo apply_filters( 'generate_meta_viewport', '<meta name="viewport" content="width=device-width, initial-scale=1">' );
	}
}
