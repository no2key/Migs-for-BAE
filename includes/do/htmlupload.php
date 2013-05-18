<?php
if ($_FILES["file"]) {
	if ($_FILES["file"]["error"] > 0) {
	
		echo "Error: " . $_FILES["file"]["error"];
		
	} else {
		if (($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") ) {
		$title = $_FILES["file"]["name"];
		$filetype = $_FILES["file"]["type"];
         $name = date("Y/m/").$title;	//date("Y/m/").md5(uniqid(rand(),1)).get_filetype($title);
		$filesize = $_FILES["file"]["size"] / 1024;
		$author = $_SESSION['uid'];
		$upload = date("Y-m-d H:i");
		$update = date("Y-m-d H:i");
		
		$json = get_option('stor');
		$stor = json_decode($json);
		$i = 0;
		// 2143289344 = 2GB*1024*1024*1024 - 4MB*1024*1024
		/*mwj while ( $storage->getDomainCapacity($stor[$i]) > 2143289344 ) {
			$i++;
		}*/
		$domain = $stor[$i];
		$opt=array(
			'headers' => array(
				'Expires' => 'access plus 1 years',
				'Content-Type' => $filetype
			)
		);
		$response=$storage->create_object($domain, '/'.$name, $_FILES['file']['tmp_name'], $opt);
		if ( $response->isOK() ) {
			// 生成缩略图
			$img_data = $storage->get_object($domain, '/'.$name);
			$img_info = getimagesize($_FILES['file']['tmp_name']);
			$width = $img_info[0];
			$height = $img_info[1];
			add_photo($author, $name, $title, $filesize, $width, $height, $upload, $update, $domain, '1');
			$uid = get_photo_info($name, 'uid', 'name');
			$url = photo_link($uid);
			header("Location: $url");
			exit();
		}
		} else {
			header("refresh:1;url=".SITE_URL."upload.php");
			echo '你提交的不是图片...';
			exit;
		}
		
	}
}