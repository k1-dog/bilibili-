<?php
	if($_GET['action'] == 'cmt')
	{
		if($_POST['cmt_text'])
			$f_ctt=$_POST['cmt_text'];
	}
	require_once 'cookie_check.php';
	require_once 'dom_xml.php';
	$dom_xml=new dom_xml();
	if(!empty($f_ctt))
	{
		$dom_xml->_insert_cmt_xml(1,$f_ctt);
	}
	$usercmt=$dom_xml->_insert_cmt_xml(0," ");
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>留言导航</title>
		<link rel="stylesheet" type="text/css" href="./css/basic.css"/>
		<link rel="stylesheet" type="text/css" href="./css/cmt.css"/>
	<?php
?>
	</head>
	<body>
		<?php 
			require 'includes/header.inc.php';
		?>
		<div id="cmt">
			<h2>留言板</h2>
			<form id='cmt' action='cmt.php?action=cmt' method='post'>
				<dl>
					<dd>标  题:<input type="text" name="cmt_title" class="ctitle"></dd>
					<dt><textarea name='cmt_text' id="cmt_plain" text=""></textarea></dt>
					<dd><input type='submit' class="submit" value='发布留言'/></dd>
				</dl>
			</form>
			<dl>
				<textarea  id="cmt_text" text="快到碗里来" disabled="true">
				<?php
				$s=0;
				foreach ($usercmt as $key => $value) {
		      	# code...
		      		if(count($value)==0)
		      			continue;
			 		for($j=0;$j<count($value)-1;$j++)
			      	{
			      		$_usercmt=$value[$j];
			      		$s++;
			      	?>
<?php echo "      ---------------------\n第--{$s}--话\n"; ?><?php echo "    {$key}~~~:"."{$_usercmt}\n"; ?>
			    <?php
	      			}
      			}
	?>
				</textarea>
			</dl>
		</div>
		<?php require 'includes/footer.inc.php';?>
	</body>
	</html>