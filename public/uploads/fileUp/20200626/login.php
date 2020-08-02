<?php
	ob_start();
	if($_GET['action'] == 'login')
	{
		if($_POST['f_name'])
			$f_name=$_POST['f_name'];
		if($_POST['f_pass'])
			$f_pass=$_POST['f_pass'];
		$_clean=array();
		$_clean['fname']=$f_name;
		$_clean['fpass']=$f_pass;
		require_once 'dom_xml.php';
  		$dom_xml=new dom_xml();
		$check_clean=$dom_xml->login_get_xml($f_name);
		print_r($check_clean);
		if($_clean['fname']==$check_clean['name'])
		{
			if($_clean['fpass']==$check_clean['pass'])
			{
				setcookie('user_ack',$f_name);
				header('Location: index.php');
			}
		}
	}
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>login导航</title>
		<link rel="stylesheet" type="text/css" href="./css/basic.css"/>
		<link rel="stylesheet" type="text/css" href="./css/login.css"/>
	</head>
	<body>
		<?php require 'includes/header.inc.php';?>
		<div id="login">
			<h2>登陆</h2>
			<form method="post" name="login" action="login.php?action=login">
				<dl>
					<dt>请认真填写以下内容</dt>
					<dd>用 户 名：----<input type="text" name="f_name" class="text"/>(*必填)</dd>
					<dd>密    码：   ------<input type="password" name="f_pass" class="text"/>(*必填)</dd>
					<dd><input type="submit" class="submit" value="登陆"></dd>
				</dl>
			</form>
		</div>
		<?php require 'includes/footer.inc.php';?>
	</body>