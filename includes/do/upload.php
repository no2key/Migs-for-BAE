<?php
if ($_FILES["Filedata"]) {
	if ($_FILES["Filedata"]["error"] > 0) {
		fail( "Error: " . $_FILES["Filedata"]["error"] );
	} else {
		$title = $_FILES["Filedata"]["name"];
		$filetype = $_FILES["Filedata"]["type"];
      	$name = date("Y/m/").$title;//date("Y/m/").md5(uniqid(rand(),1)).get_filetype($title);
		$filesize = $_FILES["Filedata"]["size"] / 1024;
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
		//获取mime类型
		$image_size=getimagesize($_FILES['Filedata']["tmp_name"]);
		$opt=array(
			'headers' => array(
				'Expires' => 'access plus 1 years',
				'Content-Type' => $image_size['mime']
			)
		);
		// 存储到 storage
		$result=$storage->create_object($domain, '/'.$name, $_FILES['Filedata']['tmp_name'] , $opt);
		//mwj if ( $storage->errno == 0 ) {
		if ( $result->isOK() ) {
			// 生成缩略图
			//mwj $img_data = $storage->read($domain, 'original/'.$name);
			$img_data = $storage->get_object($domain, '/'.$name );
			$img_info = getimagesize($_FILES['Filedata']['tmp_name']);
			$width = $img_info[0];
			$height = $img_info[1];
			
			// 存储到数据库
			// $migs_db->query("INSERT INTO mi_photos (author, name, title, size, width, height, upload_time, update_time, storage, status) VALUES ('$author', '$name', '$title', '$filesize', '$width', '$height', '$upload', '$update', '$domain', '1')");
			// $uid = $migs_db->get_var("SELECT uid FROM mi_photos WHERE name = '$name'");
			
			add_photo($author, $name, $title, $filesize, $width, $height, $upload, $update, $domain, '1');
			$uid = get_photo_info($name, 'uid', 'name');
			
			// 返回链接
			$link = photo_url($domain, $name);
			$thumb = thumb_url($domain, $name);
			?>
				<div id="photo-<?php echo $uid;?>">
					<div class="l photo-item">
						<?php echo '<a href="'.$link.'"><img src="'.$thumb.'" alt="preview" /></a>';?>
					</div>
					<div class="r photo-meta">
						<ul>
							<li><span class="pmeta">图片名称：</span>
								<span id="title-<?php echo $uid;?>" class="editable"><?php echo $title; ?></span>
								<input id="edititle-<?php echo $uid;?>" class="edititle" type="text" value="<?php echo $title; ?>" onblur="update_title('<?php echo $uid;?>');" />
							</li>
							<li><span class="pmeta">图片规格：</span><?php echo $width.'*'.$height; ?></li>
							<li><span class="pmeta">外链地址：</span><?php echo '<a href="'.$link.'">URL</a>'?></li>
                            <li><span class="pmeta">所属相册：</span>
<span id="category-<?php echo $uid;?>" class="editable">默认相册</span>
<?php $category_info = $migs_db->get_results("SELECT id,cate_name FROM mi_category ORDER BY id DESC");?>
                        <select id="editcategory-<?php echo $uid;?>" name="category" class="edititle" onblur="update_category('<?php echo $uid;?>');">
                            <?php foreach($category_info as $single){?>
							<option value="<?php echo $single->id; ?>"<?php if($single->id == '1'){?> selected="selected"<?php }?>><?php echo $single->cate_name; ?></option><?php }?>
                         </select>
                            </li>
							<li><span class="pmeta" style="display:block;">图片描述：</span>
								<div id="desc-<?php echo $uid;?>" class="editable">图片没有描述,点击这里以编辑...</div>
								<textarea id="editdesc-<?php echo $uid;?>" class="editdesc" onblur="update_desc('<?php echo $uid;?>');" >图片没有描述,点击这里以编辑...</textarea>
							</li>
						</ul>
					</div>
					<div class="clear"></div>
				</div>
			<?php
		} else {
			fail('存储文件失败');
		}
	}
} else {
	fail('没有上传文件');
}