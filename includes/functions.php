<?php
	//	百度BCS操作类入口
	require_once(ABSPATH.'bcs/bcs.class.php');
	//  数据库操作类
	require_once(ABSPATH.'includes/migs_db.php');
	//	用户操作类
	require_once(ABSPATH.'includes/class.user.php');
	//	数据库选项函数
	require_once(ABSPATH.'includes/options.php');
	//	数据库图片函数
	require_once(ABSPATH.'includes/photos.php');
	//	首页分页函数
	require_once(ABSPATH.'includes/nav.page.php');
	
	function fail($s) {
		header('HTTP/1.0 500 Internal Server Error');
		echo $s;
		exit;
	}
	
	function update_stor_attr($allowReferer) {
		global $storage;
		$expires = 'ExpiresActive On
ExpiresDefault "access plus 30 days"
ExpiresByType image/gif "access plus 1 year"
ExpiresByType image/jpg "access plus 1 year"
ExpiresByType image/png "access plus 1 year"
';
		$attr = array('expires'=>$expires, 'allowReferer'=>$allowReferer);
		$json = get_option('stor');
		$stor = json_decode($json);
		for ( $i = 0; $i < 5; $i++ ) {
			$ret = $storage->setDomainAttr($stor[$i], $attr);
		}
		if ($ret === false) {
			var_dump($storage->errno(), $storage->errmsg());
			return false;
		} else {
			return true;
		}
	}
	
	if ( !isset($storage) )
		$storage = new BaiduBCS (BCS_AK, BCS_SK );
		//$storage = new SaeStorage();
		
	session_start();