<?php
	require_once('config.php');
	require_once(ABSPATH.'includes/functions.php');
	// 检查是否已经安装图床
	if ( $migs_db->get_var("SHOW TABLES LIKE 'mi_options'") === null ) {
		$install_dir = SITE_URL.'install.php';
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $install_dir");
		exit();
	}
	/* 
	 * @ $_GET['PAGE_TITLE'] => the title you want to set
	 * @ $_GET['STYLE_LOAD'] => the style you want to load
	 */
	$MIname = get_option('site');
	$MIdesc = get_option('desc');
	$_GET['PAGE_TITLE'] = '首页 | '. $MIname;
	require_once(ABSPATH.'includes/template/header.php'); 
?>
	<div id="content">
		<div id="x-photos">

			<?php				
				$qpage = count_page();
				$prep = $qpage['prep'];
				$paged = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
                if(!$paged) $paged = 1;
                $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
				if( $category != '' ) $category = "AND category='$category'";
				$offset = ( $paged - 1 ) * $prep;

				if ( $photos = $migs_db->get_results("SELECT * FROM $migs_db->photos WHERE status = '1' $category ORDER BY uid DESC LIMIT $offset, $prep") ) {
					foreach ( $photos as $photo ) {
					?>
					<div id="photo-<?php echo $photo->uid;?>" class="x-photo">
						<a class="p-img zoomimg" href="<?php echo photo_url($photo->storage, $photo->name);//photo_link($photo->uid);?>">
							<img src="<?php echo "http://$photo->storage.bcs.duapp.com/thumb/$photo->name";//thumb_url($photo->storage, $photo->name);?>" alt="<?php echo $photo->title;?>" width="200" />
							<span class="x-title"><?php echo $photo->title;?></span>
						</a>
						<div class="x-meta">
							<a class="p-link" href="<?php echo photo_url($photo->storage, $photo->name);?>"></a>
							<a class="p-view" href="<?php echo photo_link($photo->uid);?>">VIEW</a>
							<!--<a class="p-like" href="#">LIKE</a>-->
						</div>
					</div><!-- #photo-<?php echo $photo->uid;?> -->
					<?php
					}
				}
			?>
			
			<div class="clear"></div>
		</div><!-- #x-photo -->
		
		<div id="navigation">
			<?php 
				prev_page( $_GET['category'] , $paged , $qpage['pages']);
				next_page( $_GET['category'] , $paged , $qpage['pages']);
			?>
			<div class="clear"></div>
		</div>
		<div class="x-shadow l-shadow"></div>
		<div class="x-shadow r-shadow"></div>
	</div><!-- #content -->
	
<?php
	/* 
	 * @ $_GET['SCRIPT_LOAD'] => the script you want to load!
	 */
	require_once(ABSPATH.'includes/template/footer.php'); 
?>