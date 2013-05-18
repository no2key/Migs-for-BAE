<?php

 # 设置绝对位置
if ( !defined('ABSPATH') )	define('ABSPATH', dirname(__FILE__) . '/');
 # 设置站点名称
define('SITE_NAME', 'Migs');
 # 设置站点 URL
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');

/**
 * # DATABASE SETTINGS
 */
 # 设置数据库名称

define('DB_NAME', 'JqUzzSRvfkeaOmisgofE');
 # 数据库用户名
define('DB_USER', getenv('HTTP_BAE_ENV_AK'));

 # 数据库密码
define('DB_PASSWORD', getenv('HTTP_BAE_ENV_SK'));

 # 数据库主机
define('DB_HOST', getenv('HTTP_BAE_ENV_ADDR_SQL_IP').':'.getenv('HTTP_BAE_ENV_ADDR_SQL_PORT'));

 # 定义存储的bucket
//define('DOMAIN','');

//定义应用名称，需要设置，如shuratu.duapp.com的名称为shuratu
define('APP_NAME','shuratu');

//百度BCS AK 公钥
define ( 'BCS_AK', '' );
//百度BCS SK 私钥
define ( 'BCS_SK', '' );