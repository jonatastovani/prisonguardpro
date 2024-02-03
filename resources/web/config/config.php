<?php

$urlDomain = getenv("HOST") ? getenv("HOST") : 'stylusbox.ojalmeida.dev';
define('URL_DOMAIN', 'https://'.$urlDomain);


// define('URL_DOMAIN', 'https://'.getenv("HOST"));

define('DEBUG_MODE', getenv("HOST") ? false : true);
define('DEBUG_MODE_STACK', false);
define('URL_ZIPCODE', '/api/zipcode/');
define('API_CLIENTS', '/api/clients/');
define('API_WORKFORCE_DEPARTMENTS', '/api/workforce/departments/');
define('API_WORKFORCE_EMPLOYEES', '/api/workforce/employees/');
define('API_WORKFORCE_ROLES', '/api/workforce/roles/');
define('API_PRODUCTS', '/api/products/');
define('API_PRODUCTS_ITEMS', '/api/products/items/');
define('API_PRODUCTS_TEMPLATES', '/api/products/templates/');
define('API_ORDERS', '/api/orders/');
define('API_BUDGETS', '/api/budgets/');
define('API_AUTH_TOKEN', '/api/auth/token');


define('SYSTEM_DISPLAY_NAME', 'STYLUSBOX');