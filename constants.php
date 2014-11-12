<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define( 'OIPA_URL', 'http://dev.oipa.openaidsearch.org/api/v3/');
// define( 'OIPA_URL', 'http://localhost:8000/api/v3/');
if (function_exists("site_url")){
	define( 'SITE_URL', site_url());
}
define( 'EMPTY_LABEL', 'No information available');
define( 'DEFAULT_ORGANISATION_ID', '41120');
define( 'DEFAULT_ORGANISATION_NAME', 'UN-HABITAT');
define( 'OIPA_PER_PAGE', 10);
define( 'GOOGLE_ANALYTICS_CODE', 'UA-33916702-1');