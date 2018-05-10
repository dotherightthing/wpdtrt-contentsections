<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_contentsections
 * @version 	0.0.1
 * @since       0.7.0
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @version 	0.0.1
 * @since       0.7.0
 */
class WPDTRT_Contentsections_Plugin extends DoTheRightThing\WPPlugin\r_1_4_14\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_Weather_Plugin
     *
     * @param     array $settings Plugin options
     *
	 * @version 	0.0.1
     * @since       0.7.0
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-contentsections here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    //// START WORDPRESS INTEGRATION \\\\

    /**
     * Initialise plugin options ONCE.
     *
     * @param array $default_options
     *
     * @version     0.0.1
     * @since       0.7.0
     */
    protected function wp_setup() {

    	parent::wp_setup();

		// add actions and filters here
        add_filter( 'the_content', [$this, 'filter_content_sections'], 10 );
    }

    //// END WORDPRESS INTEGRATION \\\\

    //// START SETTERS AND GETTERS \\\\
    //// END SETTERS AND GETTERS \\\\

    //// START RENDERERS \\\\
    //// END RENDERERS \\\\

    //// START FILTERS \\\\

    /**
     * Wrap content in section elements with id and class attributes
     * for compatibility with stickyNavbar.js
     * This repurposes the anchors injected by the better-anchor-links plugin
     *
     * @uses https://wordpress.org/plugins/better-anchor-links/
     */
    function filter_content_sections($content) {
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

        $anchor_after_all = preg_split($regex, $content); // excludes anchors
        $anchor_after_first = array_shift( $anchor_after_all ); // the first item is held (removed) here, the remainder are in $anchor_after_all
        preg_match_all($regex, $content, $matches); // $matches['anchor_name'] is just the anchors

        $content_sectioned = '';
        $content_sectioned .= $anchor_after_first . "\n";
        $content_sectioned .= '<div class="clear"></div>' . "\n";

        $array_index = 0;

        foreach( $matches['anchor_name'] as $anchor_name ) {
            // tabindex="-1" works better in JAWS 17
            $content_sectioned .= '<section id="' . $anchor_name . '" class="scrollto" tabindex="-1">' . "\n";
            $content_sectioned .= $anchor_after_all[$array_index] . "\n";
            $content_sectioned .= '</section>' . "\n";

            $array_index++;
        }

        return $content_sectioned;
    }
    
    //// END FILTERS \\\\

    //// START HELPERS \\\\
    //// END HELPERS \\\\
}

?>