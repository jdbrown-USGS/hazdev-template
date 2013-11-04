<?php

date_default_timezone_set('UTC');

$LIB_DIR = realpath(dirname(__FILE__));
$APP_DIR = dirname($LIB_DIR);
$HTDOCS_DIR = $APP_DIR . '/htdocs';
$CONF_DIR = $APP_DIR . '/conf';
$HTTPD_CONF = $CONF_DIR . '/httpd.conf';
$PHP_INI = $CONF_DIR . '/php.ini';


// work from lib directory
chdir($LIB_DIR);

// add lib directory to include path
$include_path = get_include_path() . PATH_SEPARATOR . $LIB_DIR;


// create conf directory if it doesn't exist
if (!is_dir($CONF_DIR)) {
	mkdir($CONF_DIR, umask(), true /*recursive*/);
}

file_put_contents($PHP_INI, '
	;; autogenerated at ' . date('r') . '

	; add template lib directory to include path
	include_path = "' . $include_path . '"
');

// write apache configuration
file_put_contents($HTTPD_CONF, '
	## autogenerated at ' . date('r') . '

	# add template lib directory to include path
	php_value include_path "' . $include_path . '"

	# template alias for css/js/images
	Alias /template ' . $HTDOCS_DIR . '

	# permissions for template directory
	<Location /template>
		Order Allow,Deny
		Allow From all
	</Location>
');
