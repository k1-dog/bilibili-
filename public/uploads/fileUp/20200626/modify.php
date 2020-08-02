<?php
	require "cookie_check.php";
	if($_GET['action'] == 'modify')
	{
		if($_POST['r_name'])
			$r_name=$_POST['r_name'];
		if($_POST['r_pass'])
			$r_pass=$_POST['r_pass'];
		if($_POST['r_age'])
			$r_age=$_POST['r_age'];
		if($_POST['r_sex'])
			$r_sex=$_POST['r_sex'];
		$_clean=array();
		$_clean['rname']=$r_name;
		$_clean['rpass']=$r_pass;
		$_clean['rage']=$r_age;
		$_clean['rsex']=$r_sex;
		require_once 'dom_xml.php';
  		$dom_xml=new dom_xml();
  		$dom_xml->_update_xml($_clean);
	}
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>用户注册页面</title>
		<link rel="stylesheet" type="text/css" href="./css/register.css"/>
		<link rel="stylesheet" type="text/css" href="./css/basic.css"/>
	</head>
	<body>
		<?php require 'includes/header.inc.php';?>
		<div id="register">
			<h2>会员注册</h2>
			<form method="post" name="modify" action="modify.php?action=modify">
				<dl>
					<dt>请认真填写以下内容</dt>
					<dd>用 户 名：----<input type="text" name="r_name" class="text"/>(*必填)</dd>
					<dd>密    码：    -------<input type="password" name="r_pass" class="text"/>(*必填)</dd>
					<dd>确认密码：-----<input type="ack_password" name="r_passack" class="text"/>(*必填)</dd>
					<dd>年    龄：     --------<input type="text" name="r_age" class="text"/></dd>
					<dd>性    别： ----<input type="radio" name="r_sex" value="女" checked="checked"/>女<input type="radio" name="r_sex" value="男" />男</dd>
					<dd><input type="submit" class="submit" value="修改"></dd>
				</dl>
			</form>
		</div>
	</body>
	</html>