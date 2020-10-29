<?php
	require_once('load.php');
	//get user id from cookies -------------
	global $db;
	global $u_name;
	$log = $queries->check_login();
	if($log==false){
		$redirect ="index.php";
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
	// home post contents ----- start ------
	$table = "posts";
	$query = "SELECT * FROM $table ORDER BY unix_time DESC";
	$result = $db->select($query);
	if(empty($result)){
		echo "No Recent posts";
		return;
	}
	$cnt =0;
	while ($row = $result->fetch_assoc()){
		$cnt++;
		$h_pid[] = $row['p_id'];		//post id
		$h_uid[] = $row['user_id'];
		$h_did[] = $row['dis_id'];
		$h_pcontent[] = $row['p_content'];
		$h_time[] = $row['unix_time'];
	}
	//print_r($h_did);
	foreach ($h_did as $key => $value) {
		$table = "discussions";
		$query = "SELECT dis_title FROM $table WHERE dis_id = $h_did[$key]";
		$result = $db->select($query);
		//print_r($result);
		while ($row = $result->fetch_assoc()) {
			$h_dtitle[] = $row['dis_title']; 		//discussion title
		}
	}
	foreach ($h_pid as $key => $value) {
		$h_pvotes[] = $queries->get_post_votes($value); 		// post votes
		//$h_pauthor[] 
		$query = "SELECT full_name FROM user WHERE user_id=$h_uid[$key]";
		$result = $db->select($query);
		$row = $result->fetch_assoc();
		$h_pauthor[] = $row['full_name'];
	}
	//exit(0);
	// home post contents ----- end -----

	//trending discussion --------- start---------

	$table = "discussions";
	$query = "SELECT dis_id, admin_id, dis_title, unix_time from $table order by unix_time desc limit 6";
	$result = $db->select($query);
	while ($row = $result->fetch_assoc()) {
		$t_dtitle[] = $row['dis_title']; 	//discussion title
		$tmp = $row['admin_id'];
		$t_time[] = $row['unix_time']; 	//time
		$t_did[] = $row['dis_id']; 		//trending discussion id
		$query = "SELECT full_name FROM user WHERE user_id=$tmp ";
		$tmpr = $db->select($query);
		$tmp_row = $tmpr->fetch_assoc();
		$t_dauthor[] = $tmp_row['full_name'];  //discussion author
		
 	}
 	// print_r($t_dtitle);
 	// exit(0);
	//trending discussion --------- end ---------

?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">

	<style type="text/css">
		body {
			background-color: rgb(248,248,248);
		}
		a {
			text-decoration: none !important;
		}
	</style>
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
				<a href="#" class="navbar-brand" style="margin-left:100px;"><span id="brand"><span style="color:#cce6ff;">Mnnit</span> <span style="color:#ffb3b3;">Forums</span></a>
				
			</div>
			<div class="collapse navbar-collapse" id="main-navbar">
				<ul class="nav navbar-nav navbar-right">
					
					<li>
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" style="margin-top:10px; ">
							New Discussion
						</button>
					</li>
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
							<li><a href="index.php?action=logout">Log out  <span class="glyphicon glyphicon-off" style="margin-left:5px;"></span></a></li>
						</ul>
					</li>
					<li style="margin-right:200px;"></li>																
						
				</ul>
			</div>
		</div>	
	</nav>

	<!-- For new discussion  onclick="new_dis(4)" -->

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  		<div class="modal-dialog" role="document">
    		<div class="modal-content">
     			 <div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        			<h4 class="modal-title" id="myModalLabel">Create Your New discussion</h4>
     			 </div>
      			<div class="modal-body">
        			<form class="form-horizontal" >
						<div class="form-group">
	                        <div class="col-sm-10 ">
	                              <input type="text" class="form-control" name="dis_title" id="discussion_title" placeholder="Discussion title">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="col-sm-10 ">
	                              <textarea type="text" class="form-control" id="discussion_content" name="dis_content"  rows="8"></textarea>
	                              
	                        </div>
	                    </div>
                    </form>
      			</div>
      			<div class="modal-footer">
        			<button type="button" class="btn btn-default" id="close_me" data-dismiss="modal">Close</button>
        			<button type="button" class="btn btn-primary" onclick="new_dis(4)" >Start discussion</button>
     	 		</div>
    		</div>
    	</div>
    </div>

	<div id="page-author">
				<button type="button" class="btn btn-info">Home</button></br></br>
				<?php echo $cnt;?> Posts
	</div>

	<div id="main-content">
		<div id="dis">
			
			<?php foreach ($h_pid as $key => $value) :?>
				<?php $post_content = $h_pcontent[$key]; $dis_title = $h_dtitle[$key]; $p_votes = $h_pvotes[$key]; $author = $h_pauthor[$key];      $time = $h_time[$key];  $dis_id = $h_did[$key]; $pid = $value?>	
			
				<div class="dis-content">
						<?php echo $post_content; ?>
				</div>
				<div class="middle">
					Posted in 
				</div>
				<div class="dis-header">
					<a href="discussion-page.php?dis_id=<?php echo $dis_id ?>" 
					style="text-decoration:none !important;" target="_blank"> <?php echo $dis_title; ?> </a>
				</div>
				<div class="ans-border">
							
				</div>
				<div class="dis-left-footer">
					<!-- <div ><span style="font-weight:130%;font-size:120%;"> <?php echo $p_votes; ?> </span> Votes </div> -->
					<table>
							<tr><td><button class="btn btn-primary btn-sm" id="up<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,<?php 
							echo $pid ?>,1)">up</button></td>
							<tr><td><span style="font-size: 140%;font-weight: 130%;" id="post<?php echo $pid?>" > <?php echo $p_votes;?> </span></td></tr>
							<tr><td><button class="btn btn-primary btn-sm" id="down<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,<?php echo $pid ?>,0)">Down</button></td></tr>
							
					</table>
					
				</div>
				<div class="dis-right-footer">
					<table>
						<tr><td> <?php echo $author; ?> </td></tr>
						<tr><td><?php $time = date('d/m/Y h:i A' ,$time) ; echo $time; ?></td></tr>
					</table>
				</div>
				<div class="seperator">
					
				</div>
			<?php  endforeach; ?>

			
		</div>
	</div>

	<div id="side-content">
		<div id="side-content-header">
			Trending <br> Disscussions
		</div>
		<?php foreach ($t_did as $key => $dis_id) : ?>
			<?php $t_title = $t_dtitle[$key]; $t_author = $t_dauthor[$key]; $time = $t_time[$key]; ?>
			<div class="add-dis">
				<a href="discussion-page.php?dis_id=<?php echo $dis_id ?>" target="_blank">
					<?php echo $t_title; ?>
				</a>
			</div>
			<div class="add-dis-author">
				<table>
					<tr>
						<td><?php echo $t_author; ?></td>
					</tr>
					<tr>
						<td> <?php $p_time = date('d/m/Y h:i A' ,$time) ; echo $p_time; ?> </td>
					</tr>
				</table>
			</div>
			<div class="add-border">
				
			</div>
		<?php endforeach; ?>
		
	</div>



	<script type="text/javascript" src="mnnit_forums_jquery.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
    <!--<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script src = "js/bootstrap.js"></script>

</body>
</html>