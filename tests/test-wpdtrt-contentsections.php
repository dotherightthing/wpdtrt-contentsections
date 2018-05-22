<?php
/**
 * Unit tests, using PHPUnit, wp-cli, WP_UnitTestCase
 *
 * The plugin is 'active' within a WP test environment
 * 	so the plugin class has already been instantiated
 * 	with the options set in wpdtrt-gallery.php
 *
 * Only function names prepended with test_ are run.
 * $debug logs are output with the test output in Terminal
 * A failed assertion may obscure other failed assertions in the same test.
 *
 * @package     WPDTRT_Contentsections
 * @version     0.0.1
 * @since       0.7.0 DTRT WordPress Plugin Boilerplate Generator
 *
 * @see http://kb.dotherightthing.dan/php/wordpress/php-unit-testing-revisited/ - Links
 * @see http://richardsweeney.com/testing-integrations/
 * @see https://gist.github.com/benlk/d1ac0240ec7c44abd393 - Collection of notes on WP_UnitTestCase
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory.php
 * @see https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes//factory/
 * @see https://stackoverflow.com/questions/35442512/how-to-use-wp-unittestcase-go-to-to-simulate-current-pageclass-wp-unittest-factory-for-term.php
 * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
 */

/**
 * WP_UnitTestCase unit tests for wpdtrt_contentsections
 */
class wpdtrt_contentsectionsTest extends WP_UnitTestCase {

    /**
     * Compare two HTML fragments.
     *
     * @param string $expected Expected value
     * @param string $actual Actual value
     * @param string $error_message Message to show when strings don't match
     *
     * @uses https://stackoverflow.com/a/26727310/6850747
     */
    protected function assertEqualHtml($expected, $actual, $error_message) {
        $from = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s'];
        $to   = ['>',            '<',            '\\1',      '><'];
        $this->assertEquals(
            preg_replace($from, $to, $expected),
            preg_replace($from, $to, $actual),
            $error_message
        );
    }

    /**
     * SetUp
     * Automatically called by PHPUnit before each test method is run
     */
    public function setUp() {
  		// Make the factory objects available.
        parent::setUp();

	    $this->post_id_1 = $this->create_post( array(
	    	'post_title' => 'DTRT Content Sections test',
	    	'post_content' => '<h2>Heading One</h2><p>This is the first heading.</p><p>There would usually be a gallery here too.</p><h2>Heading Two</h2><p>This is the second heading.</p><p>There would usually be a gallery here too.</p>'
	    ) );
    }

    /**
     * TearDown
     * Automatically called by PHPUnit after each test method is run
     *
     * @see https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories
     */
    public function tearDown() {

    	parent::tearDown();

    	wp_delete_post( $this->post_id_1, true );
    }

    /**
     * Create post
     *
     * @param array $options Post options (post_title, post_date, post_content)
     * @return number $post_id
     *
     * @see https://developer.wordpress.org/reference/functions/wp_insert_post/
     * @see https://wordpress.stackexchange.com/questions/37163/proper-formatting-of-post-date-for-wp-insert-post
     * @see https://codex.wordpress.org/Function_Reference/wp_update_post
     */
    public function create_post( $options ) {

    	$post_title = null;
    	$post_date = null;
        $post_content = null;

    	extract( $options, EXTR_IF_EXISTS );

 		$post_id = $this->factory->post->create([
           'post_title' => $post_title,
           'post_date' => $post_date,
           'post_content' => $post_content,
           'post_type' => 'post',
           'post_status' => 'publish'
        ]);

        return $post_id;
    }

    // ########## TEST ########## //

    /**
     * Test better-anchor-links dependency injection
     * @uses ludek/better-anchor-links
     */
    public function __test_dependency_injection() {

        $this->go_to(
            get_post_permalink( $this->post_id_1 )
        );

        $content = get_post_field('post_content', $this->post_id_1);

        $this->assertContains(
            "<div class='mwm-aal-title'>",
            do_shortcode( $content ),
            'Anchor links list not injected'
        );
    }

	/**
	 * Test section injection
     * @uses ludek/better-anchor-links
	 */
	public function __test_section_injection() {

        $this->go_to(
            get_post_permalink( $this->post_id_1 )
        );

        $content = get_post_field('post_content', $this->post_id_1);

        $this->assertContains(
            '<section id="heading-one" class="scrollto" tabindex="-1">',
            do_shortcode( $content ),
            'Section tags not injected'
        );
	}
}
