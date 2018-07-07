/**
 * @file DTRT Content Sections frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_contentsections_config`.
 * @version 0.0.1
 * @since   0.7.0 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global document, jQuery, wpdtrt_contentsections_config */
/* eslint-disable no-unused-vars */

/**
 * @namespace wpdtrt_contentsections_ui
 */
const wpdtrt_contentsections_ui = {

    /**
     * Initialise front-end scripting
     * @since 0.0.1
     */
    init: () => {
        "use strict";

        console.log("wpdtrt_contentsections_ui.init");
    }
}

jQuery(document).ready( ($) => {

    "use strict";

	const config = wpdtrt_contentsections_config;
    wpdtrt_contentsections_ui.init();
});

/* eslint-enable no-unused-vars */
