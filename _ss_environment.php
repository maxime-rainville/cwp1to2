<?php
/* What kind of environment is this: development, test, or live (ie, production)? */
define('SS_ENVIRONMENT_TYPE', 'dev');

/* Database connection */
define('SS_DATABASE_SERVER', 'localhost');
define('SS_DATABASE_USERNAME', 'root');
define('SS_DATABASE_PASSWORD', '');
define('SS_DATABASE_NAME', 'cwp1to2');

/* Configure a default username and password to access the CMS on all sites in this environment. */
define('SS_DEFAULT_ADMIN_USERNAME', 'admin');
define('SS_DEFAULT_ADMIN_PASSWORD', 'admin');

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING['/home/maxime/Projects/cwp1to2'] = 'http://cwp1to2.max.silverstripe.com';
