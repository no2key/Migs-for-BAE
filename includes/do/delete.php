<?php
if ( isset($_REQUEST['name']) && isset($_REQUEST['stor']) ) {

	if ( $storage->delete_object($_REQUEST['stor'], '/'.$_REQUEST['name'] ) ) {
	
		$storage->delete_object($_REQUEST['stor'], '/thumb/'.$_REQUEST['name']);
		delete_photo($_REQUEST['name'], 'name');
		echo '删除成功';
		
	} else {
	
		fail('删除失败');
		
	}
	
} else {

	fail('请指定 name 和 stor');
	
}