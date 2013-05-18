<?php
//缩略图函数
function img_create_small($big_img,$size  = '200' ){//大图文件地址，缩略宽，缩略高
    $imgage=getimagesize($big_img);//获取大图信息
    switch ($imgage[2]){//判断图像类型
    case 1:
     $im=imagecreatefromgif($big_img); 
     break;
    case 2:
     $im=imagecreatefromjpeg($big_img);
     break;
    case 3:
     $im=imagecreatefrompng($big_img);
     break;  
    }
    $src_W=imagesx($im);//获取大图宽
    $src_H=imagesy($im);//获取大图高
	$width=(int)$size;
	$height=(int)$src_H*$width/$src_W;
    $tn=imagecreatetruecolor($width,$height);//创建小图
    imagecopyresized($tn,$im,0,0,0,0,$width,$height,$src_W,$src_H);//复制图像并改变大小
    //输出图像
	switch ($imgage[2]){//判断图像类型
    case 1:
	 header('Content-type: image/gif');
     imagegif($tn);
     break;
    case 2:
	 header('Content-type: image/jpeg'); 
     imagejpeg($tn);
     break;
    case 3:
	 header('Content-type: image/png'); 
     imagepng($tn);
     break;  
    }
	imagedestroy($tn);
}
//img_create_small('http://bcs.duapp.com/clouds/original%2Fe06c2bf71769d7cfe3dcd9dfc201069d.jpg','200');
if( isset($_REQUEST['source_img'])&&isset($_REQUEST['size']) )
{
	img_create_small($_REQUEST['source_img'],$_REQUEST['size']);
}
else
{
	header('HTTP/1.1 404 Not Found');
	header("status: 404 Not Found");
	echo "参数不合法，请重试！";
}
?>