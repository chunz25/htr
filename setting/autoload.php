<?php
$auto['dailyreport']['packages']            = array();
$auto['dailyreport']['libraries']            = array('database', 'session', 'cache', 'cetak_phpexcel', 'template_cetak', 'tp_connpgsql');
$auto['dailyreport']['helper']                = array('url', 'language', 'tp_input', 'tp_tanggal', 'form');
$auto['dailyreport']['config']                = array();
$auto['dailyreport']['language']            = array();
$auto['dailyreport']['model']                = array();

$auto['siap']['packages']        = array();
$auto['siap']['libraries']        = array('database', 'session', 'cache', 'image_proses', 'cetak_phpexcel', 'template_cetak', 'tp_connpgsql', 'tp_notification');
$auto['siap']['helper']            = array('url', 'language', 'tp_input', 'tp_tanggal');
$auto['siap']['config']            = array();
$auto['siap']['language']        = array();
$auto['siap']['model']            = array();

$auto['master']['packages']        = array();
$auto['master']['libraries']    = array('database', 'session', 'cache', 'cetak_phpexcel', 'template_cetak');
$auto['master']['helper']        = array('url', 'tp_input', 'language');
$auto['master']['config']        = array();
$auto['master']['language']        = array();
$auto['master']['model']        = array();


/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Libraries
| 2. Helper files
| 3. Plugins
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your system/application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/

//$autoload['libraries'] = array('database','tp_akses','template_cetak','hakakses');


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

//$autoload['helper'] = array('tp_input','tp_tanggal','tp_oracle_sp','number','tp_currency','cookie');


/*
| -------------------------------------------------------------------
|  Auto-load Plugins
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['plugin'] = array('captcha', 'js_calendar');
*/

//$autoload['plugin'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

//$autoload['config'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

//$autoload['language'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

//$autoload['model'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Core Libraries
| -------------------------------------------------------------------
|
| DEPRECATED:  Use $autoload['libraries'] above instead.
|
*/
 //$autoload['core'] = array('Client_Loxader');



/* End of file autoload.php */
/* Location: ./system/application/config/autoload.php */
