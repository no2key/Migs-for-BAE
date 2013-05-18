<?php
/**
 * MySQL 操作
 */
// 添加图片
function add_photo($author, $name, $title, $filesize, $width, $height, $upload, $update, $domain, $status = '1') {
	global $migs_db;
	$migs_db->query("INSERT INTO $migs_db->photos (author, name, title, size, width, height, upload_time, update_time, storage, status) VALUES ('$author', '$name', '$title', '$filesize', '$width', '$height', '$upload', '$update', '$domain', '$status')");
}

// 获取图片信息
function get_photo($hash, $type = 'uid') {
	global $migs_db;
	$photo = $migs_db->get_row("SELECT * FROM $migs_db->photos WHERE $type = '$hash'");
	return $photo;
}

// 获取单一图片信息
function get_photo_info($hash, $get, $type = 'uid') {
	global $migs_db;
	$photo = $migs_db->get_var("SELECT $get FROM $migs_db->photos WHERE $type = '$hash'");
	return $photo;
}

/**
 * 更新格式：数组、非数组
 * array(
 * 	array("user_status", "0"),
 * 	array("user_status", "0")
 * )
 */
// 更新图片信息
function update_photo($hash, $array, $type = 'uid') {
	global $migs_db;
	foreach ($array as $value){
		$key = $value[0];
		$value = $value[1];
		$result = $migs_db->query("UPDATE $migs_db->photos SET $key = '$value' WHERE $type ='$hash'");
	}
}

// 删除图片
function delete_photo($hash, $type = 'uid') {
	global $migs_db;
	$migs_db->query("DELETE FROM $migs_db->photos WHERE $type = '$hash'");
}

// 图片查看链接
function photo_link($pid) {
  //return SITE_URL.'photo.php?id='.$pid;
	return SITE_URL.'photo/'.$pid;
}

// 图片地址
function photo_url($stor, $name) {
	return "http://$stor.bcs.duapp.com/$name";
	//global $storage;
	//return $storage->generate_put_object_url($stor, 'original/'.$name);
	//mwj return $storage->getUrl($stor, 'original/'.$name);
}

// 上一张图片
function prev_photo($hash, $status = 1) {
	global $migs_db;
	if ( $status == '1' ) {
		$prev = $migs_db->get_row("SELECT uid, title FROM $migs_db->photos WHERE uid > $hash AND status = '1' ORDER BY uid LIMIT 0, 1");
	} else {
		$prev = $migs_db->get_row("SELECT uid, title FROM $migs_db->photos WHERE uid > $hash ORDER BY uid LIMIT 0, 1");
	}
	if ( $prev->uid != '' ) {
		$photo['uid'] = $prev->uid;
		$photo['title'] = $prev->title;
		return $photo;
	} else {
		return false;
	}
}

// 下一张图片
function next_photo($hash, $status = 1) {
	global $migs_db;
	if ( $status == '1' ) {
		$next = $migs_db->get_row("SELECT uid, title FROM $migs_db->photos WHERE uid < $hash AND status = '1' ORDER BY uid DESC LIMIT 0, 1");
	} else {
		$next = $migs_db->get_row("SELECT uid, title FROM $migs_db->photos WHERE uid < $hash ORDER BY uid DESC LIMIT 0, 1");
	}
	if ( $next->uid != '' ) {
		$photo['uid'] = $next->uid;
		$photo['title'] = $next->title;
		return $photo;
	} else {
		return false;
	}
}

// 上一张图片链接
function prev_photo_link($hash, $status = 1) {
	if ( $photo = prev_photo($hash, $status) ) {
		echo '<a id="prev-link" class="page prev" title="'.$photo['title'].'" href="'.SITE_URL.'photo.php?id='.$photo['uid'].'">PREV</a>';
	}
}

// 下一张图片链接
function next_photo_link($hash, $status = 1) {
	if ( $photo = next_photo($hash, $status) ) {
		echo '<a id="next-link" class="page next" title="'.$photo['title'].'" href="'.SITE_URL.'photo.php?id='.$photo['uid'].'">PREV</a>';
	}
}

// 获取文件后缀名
function get_filetype($name) {
	$type  = explode("." , $name); 
    $count = count($type) - 1;
    return '.'.$type[$count]; 
}

// 生成、获取缩略图
function thumb_url($stor, $file, $size = '200') {
	global $storage;
	if ( $storage->is_object_exist($stor, '/thumb/'.$file) ) {
		return "http://$stor.bcs.duapp.com/thumb/$file";
		
	} elseif (  $storage->is_object_exist($stor, '/'.$file) ) {
		//创建sdk对象
		$fetch= new BaeFetchUrl();
		//发起一次get请求
		$fetch->get("http://".APP_NAME.".duapp.com/includes/thumb-image.php?source_img=http://$stor.bcs.duapp.com/$file&size=$size");
		//获取http code
		if($fetch->getHttpCode() != '200' )
		    return FALSE;
		//获取图像数据
		$content= $fetch->getResponseBody();
		//获取mime类型
		$image_size=getimagesize("http://$stor.bcs.duapp.com/$file");
		$mime_type=$image_size['mime'];
		$opt=array(
			'headers' => array(
				'Expires' => 'access plus 10 years',
				'Content-Type' => $mime_type
			)
		);
		//写入
		$storage->create_object_by_content($stor, '/thumb/'.$file, $content, $opt);
		return "http://$stor.bcs.duapp.com/thumb/$file";
		
	
		/*// 读取原始图片信息
		//mwj $img_data = $storage->read($stor, 'original/'.$file);get_object
		$image = $storage->get_object($stor, '/original/'.$file);
		$img_data = $image->body;
		//mwj $img_url = $storage->getUrl($stor, 'original/'.$file);
		$img_url = $storage->generate_get_object_url($stor, '/original/'.$file);
				//$img_info = getimagesize($img_url);
		
		// 生成缩略图
		$img = new SaeImage();
		$img->setData( $img_data );
		$img_info = $img->getImageAttr();
		$img->resize(200);
		$resizeRa = 200/$img_info[0];
		$cropYend = $img_info[1] * $resizeRa;
		if ( $cropYend > 150 ) {
			$cropy = 150/$cropYend;
			$img->crop(0, 1, 0, $cropy);
		}
		$storage->write($stor, $size.'/'.$file, $img->exec());
		$img->clean();
		return $storage->getUrl($stor, $size.'/'.$file);*/

		
	} else {
	
		return false;
		
	}
}