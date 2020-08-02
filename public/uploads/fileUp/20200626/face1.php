<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>用户注册页面</title>
		<link rel="stylesheet" type="text/css" href="./css/face.css"/>
		<link rel="stylesheet" type="text/css" href="./css/basic.css"/>
		<script type="text/javascript" src="XML_JS/opener.js"></script>
	</head>
	<body>
		<div id="face">
			<h3>选择头像</h3>
			<dl>
				<?php foreach (range(0,3) as $num) {?>
				<dd><img src="face/m0<?php echo $num;?>.jpg" alt="face/m0<?php echo $num;?>.jpg" title="头像<?php echo $num;?>"/></dd>
				<?php }?>
			</dl>
		</div>
	</body>
