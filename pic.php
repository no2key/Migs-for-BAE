<?php
require('config.php');
require_once(ABSPATH.'includes/functions.php');
	$_GET['PAGE_TITLE'] = '图片地址转换 | Shura';
require(ABSPATH.'includes/template/header.php');
?>
<script type="text/javascript">
function getLink(){
	//获取原链接
	var old = document.getElementById("old");
	//非空验证
	if(old.value.length == 0){
		alert("链接不能为空");
		old.focus();
		return false;
	}
	//提取原链接中关键代码
	old = old.value.split("com/")[1];
	//拼接最终结果
	urlnew = "http://pic.chenxuefeng.net.cn/"+old;
	document.getElementById("old").value = urlnew;
  	$("#old").select();
}
</script>
<div id="content">
<form id="shura">
<p>链接：<input style="width:500px;" id="old" value="" type="text">
<input id="btn" value="转换" onclick="getLink()" type="button"></p>
<p>将pcs图片地址转换为博客图片地址</p>
</form>
</div>
<?php require(ABSPATH.'includes/template/footer.php'); ?>