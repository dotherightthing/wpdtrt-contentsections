<?php
/**
 * Plugin sub class.
 *
 * @package WPDTRT_Contentsections
 * @since   0.7.16 DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Extend the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since   1.0.0
 */
class WPDTRT_Contentsections_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_4_39\Plugin {

	/**
	 * Supplement plugin initialisation.
	 *
	 * @param     array $options Plugin options.
	 * @since     1.0.0
	 * @version   1.1.0
	 */
	function __construct( $options ) {

		// edit here.

		parent::__construct( $options );
	}

	/**
	 * ====== WordPress Integration ======
	 */

	/**
	 * Supplement plugin's WordPress setup.
	 * Note: Default priority is 10. A higher priority runs later.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference Action order
	 */
	protected function wp_setup() {

		// edit here.

		parent::wp_setup();

		// add actions and filters here
		add_filter( 'the_content', array( $this, 'filter_content_sections' ), 10 );
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * ===== Renderers =====
	 */

	/**
	 * ===== Filters =====
	 */

	/**
	 * Wrap content in section elements with id and class attributes
	 * for compatibility with stickyNavbar.js
	 * This repurposes the anchors injected by the better-anchor-links plugin
	 *
	 * @param string $content Content
	 * @uses https://wordpress.org/plugins/better-anchor-links/
	 */
	function filter_content_sections( $content ) {
		$regex = '/<a class=[\"\']mwm-aal-item[\"\'] name=\"(?P<anchor_name>[0-9a-z-]+)\"><\/a>/im';
		// $regex = "/<a class=[\"\']mwm-aal-item[\"\'] name=\"(?P<anchor_name>[0-9a-z-]+)\"><\/a>+(?P<section_content>[\s\S]+)(?P<next_anchor>class=[\"\']mwm-aal-item[\"\'])/im";

		// DOMDocument doesn't support HTML5 elements
		// https://github.com/ivopetkov/html5-dom-document-php
		/*
		$dom = new IvoPetkov\HTML5DOMDocument();
		$dom->loadHTML($content);

		foreach( $dom->childNodes as $element ) {
		wpdtrt_log( $element->getAttribute('name') );
		}

		// if heading/anchor link add to array
		// if anything else make nested array of current heading/anchor link
		*/

		$anchor_after_all   = preg_split( $regex, $content ); // excludes anchors
		$anchor_after_first = array_shift( $anchor_after_all ); // the first item is held (removed) here, the remainder are in $anchor_after_all
		preg_match_all( $regex, $content, $matches ); // $matches['anchor_name'] is just the anchors

		$content_sectioned  = '';
		$content_sectioned .= $anchor_after_first . "\n";
		$content_sectioned .= '<div class="clear"></div>' . "\n";

		$array_index = 0;

		foreach ( $matches['anchor_name'] as $anchor_name ) {
			// tabindex="-1" works better in JAWS 17
			$content_sectioned .= '<section id="' . $anchor_name . '" class="scrollto" tabindex="-1">' . "\n";
			$content_sectioned .= $anchor_after_all[ $array_index ] . "\n";
			$content_sectioned .= '</section>' . "\n";

			$array_index++;
		}

		return $content_sectioned;
	}

	/**
	 * ===== Helpers =====
	 */
}
