<?php 
	require_once ('load.php');
	global $db;
	global $u_name;
	$log = $queries->check_login();
	if($log==false){
		// $redirect ="http://localhost/Myphpfiles/mnnit_forums/index-page.php";
		$redirect = "index.php";
		header("Location : $redirect");
		exit;
	}
	else{
		$cookie = $_COOKIE ['log_auth'];
		$u_name = $cookie ['user']; 		//user name
		// echo $tmpname;
		// exit(0);
		$query = "SELECT user_id from user where user_name='$u_name'";
		$result = $db->select($query);
		$row = $result->fetch_assoc();
		$uid = $row['user_id'];
	}
	if (!empty($_GET['dis_id'])){
		$dis_id = $_GET['dis_id']; 		//dis_id
	}
	else{
		echo "please provide dis_id in header --> url?dis_id=15";
		//$dis_id = 1;
		exit(0);
	}
	//$dis_id = 1;
	//$uid = 10;
	// echo "hello";
	
	$dis_details = $queries->get_details ($dis_id);
	//print_r($dis_details);
	$admin_id = $dis_details[1];
	$author_name = $queries->get_name ($admin_id,'user');
	$dis_time = $dis_details[5];
	$dis_title = $dis_details[3];
	$dis_content = $dis_details[4];
	$dis_status = $dis_details[2];
	//echo "hai";
	// $val = $queries->check_to_vote_for_dis($dis_id,$uid,1);
	// if ($val){
	// 	echo "true";
	// }
	// else{
	// 	echo "false";
	// }
	//exit(0);
	$dis_votes = $queries->get_dis_votes ($dis_id);

	$priority = $queries->get_priority ($dis_id); //arr['post_id'] = votes in decreasing order

	if ($priority!=false)
	{
			foreach ($priority as $key => $value) {
				$posts [] = $key;
			}
			$length = count($posts);

			$posts_str = implode(" ,", $posts);
			//echo $posts_str;
			//exit(0);
			$table = "posts";
			$query = "SELECT COUNT(*) AS cnt FROM $table";
			//echo $query;
			$arr = $db->select($query);
			while ($row = $arr->fetch_assoc()){
				$total = $row['cnt'];
			}
			//echo "total "+$total+"<br>";
			//print_r($row);
			//exit(0);
			//$total = $res['cnt'];
			$table = "posts";
			$start = $total - $length;
			$query = "SELECT p_id,user_id,p_content,unix_time FROM $table ORDER BY FIELD (p_id ,$posts_str),unix_time LIMIT $start ,$length";
			//echo $query;
			$result = $db->select ($query);
			
			$i=0;
			while ($row = $result->fetch_assoc ()){
			
				$res[$i][] = $row['p_id'];
				$res[$i][] = $row['user_id'];
				$res[$i][] = $row['p_content'];
				$res[$i][] = $row['unix_time'];
				$i++;
				
			} 
			//print_r ($row);
			// if(!empty($i))
			// 	echo "i not empty";
			// if(!isset($row)){
			// 	echo " it is not set";
			// }
			// if (!empty($row)){
			// 	echo "no row";
			// }
			//echo "<br>" . $i . "<br>";
			//print_r($post_uid);
			// foreach ($res as $row) {
			// 	echo "<br>";
			// 	print_r($row);
			// }


			// while ($arr = $result->fetch_object()){
			// 	$row[] = $arr;
			// }
			// if (!isset($row))
			// 	echo "it is not set";
			// print_r($row);
			//exit(0);
	}


?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width" initial-scale="1.0">
		<title>Discussion page</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">

		<style type="text/css">
			body {
					background-color: rgb(248,248,248);
				}
			h1 {
					font-size: 165%;
					font-weight: 130%;
					width:97%;

			}
			a{

				color:blue;
			}
		</style>
		<!-- <script type="text/javascript" >
			function dis_vote (a,b,c){
				alert("heyuyy");
			}
		</script> -->
	</head>
<body>

	<nav class="navbar navbar-inverse" id="corner-off">
		<div class="container">
			<div class="navbar-header">
				<button type="button" data-toggle="collapse" data-target="#main-navbar" class="navbar-toggle">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!--<a href="#" class="navbar-brand" width="30px" height="30px"><img src="images/to-do-list.jpg" alt="To do list">To do list</a>-->
				<a href="home-page.php" class="navbar-brand" style="margin-left:100px;"><span id="brand"><span style="color:#cce6ff;">Mnnit</span> <span style="color:#ffb3b3;">Forums</span></span></a>
				
			</div>
			<div class="collapse navbar-collapse" id="main-navbar">
				<ul class="nav navbar-nav navbar-right">
					
					
					<li>
						<a href="#" title="Notifications">
							<span class="glyphicon glyphicon-globe"></span>
						</a>
					</li>
					
					<li class="dropdown">
						<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown" title="Settings">
							<span style="color:white !important;"><?php echo $u_name; ?></span>  
						</a>
						<ul class="dropdown-menu">
							<li><a href="profile-page.php" target="_blank">Profile</a></li>
							<li><a href="#">Settings</a></li>
							<li role="sepetator" class="divider"></li>
							<li><a href="index.php?action=logout">Log out  <span class="glyphicon glyphicon-off" 
							style="margin-left:5px;"></span></a></li>
						</ul>
					</li>
					<li style="margin-right:200px;"></li>																
						
				</ul>
			</div>
		</div>	
	</nav>


	<div id="main-content">
		<div id="question-header">
			<h1><?php echo $dis_title ;?></h1>
		</div>
		<div id="votes">
			<table>
				<tr><td><button class="btn btn-primary btn-sm" id="up" onclick="dis_vote(<?php echo $dis_id ?>,<?php echo $uid ?>,1)">up </button></td></tr>
				<tr><td><span style="font-size: 140%;font-weight: 130%;" id="dis" > <?php echo $dis_votes ;?></span></td></tr>
				<tr><td><button class="btn btn-primary btn-sm" id="down" onclick="dis_vote(<?php echo $dis_id ?>,<?php echo $uid ?>,0)">Down </button></td></tr>
			</table>

		</div>
		<div id="question-content">
				<?php  echo $dis_content; ?>
		</div>
		<div id="question-footer">
			 <table>
				<tr><td><span ></span>asked  <?php $p_time = date('d/m/Y h:i A' ,$dis_time) ; echo $p_time; ?></td></tr>
				<tr><td><?php echo $author_name ?></td></tr>
				<tr><td>1000</td></tr>
			</table>
		</div>
		
		<div id="content">
			<div id="ans">
			<?php
				// if(empty($i)){
				// 	echo "i is empty";
				// }
				// if(empty($row)){
				// 	echo "row is empty";
				// }
				// if(isset($i)){
				// 	echo "it is set";
				// }
			?>
				<?php if(!empty($i)): ?>
				<div id="ans-header">
					<span id="total_posts"><?php echo $i ?></span> posts
				</div>
				<?php else : ?>
					<div id="ans-header"
					<?php echo "0 posts" ?>
					</div>
				<?php endif; ?>
				<div id="seperator">
			
				</div>
				<?php if(!empty($i)): ?>
					<?php foreach ($res as $row): ?>
						<?php  $pid = $row[0] ;$author_id = $row[1]; $content = $row[2];$time = $row[3] ?>
						<?php $p_author_name = $queries->get_name($uid,'user');//$author_votes = $queries->get_user_votes($uid);?>
						<div id="accepted-ans">
							<div id="ans-votes<?php echo $pid ?>" class="ans-votes">
								<table>
									<tr><td><button class="btn btn-primary btn-sm" id="up<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,<?php echo $pid ?>,1)">up</button></td></tr>
									<tr><td><span style="font-size: 140%;font-weight: 130%;" id="post<?php echo $pid?>" > <?php echo $priority[$pid] ;?> </span></td></tr>
									<tr><td><button class="btn btn-primary btn-sm" id="down<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,<?php echo $pid ?>,0)">Down</button></td></tr>
								</table>					
							</div>
							<div class="ans-content">
									<?php echo $content; ?>
							</div>
							<div class="ans-footer">
								 <table>
									<tr><td><span ></span>posted <?php $time = date('d/m/Y h:i A' ,$time) ; echo $time; ?> </td></tr>
									<tr><td><?php $name = $queries->get_name($author_id,'user');echo $name ?></td></tr>
									<tr><td>1000</td></tr>
								</table>
							</div>
							<div class="ans-border">
								
							</div>
						</div>
					<?php endforeach; ?>
				<?php else:
							//echo "no posts dude";
				?>
				<?php endif; ?>

			</div>

		</div>
	</div>
	<!-- <div id="">
		
	</div> -->
	
	<div id="sub-post">
		Submit your own post
	</div>
	<div id="post-area">
		<!--<form onsubmit="return false"> -->
			<textarea id="post-me" rows="10"></textarea>
			
			<button class="btn btn-primary" type="buttton" id="submit-btn" 
			onclick="update_post('1',<?php echo $uid ?>,<?php echo $dis_id ?>)">Submit</button>
			
		<!--</form> -->
	</div>
	<div id="page-footer">
		
	</div>

	<script type="text/javascript" src="mnnit_forums_jquery.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>	
	<!-- <script type="text/javascript" src="mnnit_forums.js"></script>
    <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script src = "js/bootstrap.js"></script>
</body>
</html>