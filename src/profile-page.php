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
		$uid = $row['user_id'];				//uid
	}
	// user profile posts ------ start -------

	global $db;
	//user name
	$query = "SELECT full_name FROM user where user_id=$uid";
	$result = $db->select($query);
	$row = $result->fetch_assoc();
	$user_name = $row['full_name'];					//user name
	//votes of user
	$table = 'votes';
	$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE user_id=$uid AND type=1";
	$result = $db->select ($query);
	$row = $result->fetch_assoc();
	$up_votes = $row['cnt'];
	$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE user_id=$uid AND type=0";
	$result = $db->select ($query);
	$row = $result->fetch_assoc();
	$down_votes = $row['cnt'];
	$user_votes = $up_votes - $down_votes;		//user votes

	//posts of user
	$table = "posts";
	//echo $uid;
	$query = "SELECT * FROM $table where user_id=$uid ORDER BY unix_time DESC";
	$result = $db->select($query);
	if(empty($result)){
		echo "No Recent posts";
		return;
	}
	$cnt =0;
	while ($row = $result->fetch_assoc()){
		$cnt++;
		//print_r($row);
		$p_content[] = $row['p_content'];	//post content
		$p_time[] = $row['unix_time'];		//time stamp
		$p_id[] = $row['p_id'];			//post id
		$p_did[] = $row['dis_id'];
		$p_userid[] = $row['user_id'];
	}
	//echo $cnt;
	//exit(0);
	if($cnt!=0){
		foreach ($p_did as $key => $value) {
			$table = "discussions";
			$query = "SELECT dis_title FROM $table WHERE dis_id = $p_did[$key]";
			$result = $db->select($query);
			//print_r($result);
			while ($row = $result->fetch_assoc()) {
				$p_dtitle[] = $row['dis_title']; 		//discussion title
			}
		}
		foreach ($p_id as $key => $value) {
			$p_votes[] = $queries->get_post_votes($value); 		// post votes
			//$h_pauthor[] 
			$query = "SELECT full_name FROM user WHERE user_id=$p_userid[$key]";
			$result = $db->select($query);
			$row = $result->fetch_assoc();
			$p_author[] = $row['full_name']; 		//post author name
		}
	}

	//user profile posts ------- ends -------

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width" initial-scale="1.0">
		<title>Profile</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="style.css" rel="stylesheet">

		<style type="text/css">
			body {
					background-color: rgb(248,248,248);
				}
			
		</style>
</head>
<body>

	<nav class="navbar navbar-inverse" id="corner-off">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" data-toggle="collapse" data-target="#main-navbar" class="navbar-toggle">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!--<a href="#" class="navbar-brand" width="30px" height="30px"><img src="images/to-do-list.jpg" alt="To do list">To do list</a>-->
				<a href="home-page.php" class="navbar-brand" style="margin-left:100px;"><span id="brand"><span style="color:#cce6ff;">Mnnit</span> <span style="color:#ffb3b3;">Forums</span></a>
				
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
							<li><a href="#">Profile</a></li>
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

	<!-- For new discussion  -->

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
	                              <input type="text" class="form-control" id="discussion_title" name="dis_title" placeholder="Discussion title">
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
        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        			<button type="button" class="btn btn-primary" onclick="new_dis(4)" >Start discussion</button>
     	 		</div>
    		</div>
    	</div>
    </div>

	<div id="page-author">
		<table >
			<tr>
				<td>User: <?php echo $user_name; ?> </td>
			</tr>
			<tr><td>Points: <?php echo $user_votes; ?> </td></tr>	
			<tr><td>Total Posts: <?php echo $cnt; ?> </td></tr>
		</table>
	</div>
	<div id="main-content">
		<div id="dis">
		<?php if($cnt!=0) :?>
			<?php foreach ($p_id as $key => $value) : ?>
				<?php  $content = $p_content[$key]; $dis_title= $p_dtitle[$key]; $author = $p_author[$key]; $time = $p_time[$key]; 
				$votes = $p_votes[$key] ; $dis_id = $p_did[$key]; $pid = $value?>
				<div class="dis-content">
						<?php echo $content; ?>
				</div>
				<div class="middle">
					Posted in 
				</div>
				<div class="dis-header">
					<a href="discussion-page.php?dis_id=<?php echo $dis_id ?>" style="text-decoration:none !important;" target="_blank">
						<?php echo $dis_title; ?>
					</a>
				</div>
				<div class="ans-border">
							
				</div>
				<div class="dis-left-footer">
					<!-- <div ><span style="font-weight:130%;font-size:120%;"> <?php  $votes; ?></span> Votes </div> -->
					<table>
							<tr><td><button class="btn btn-primary btn-sm" id="up<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,<?php echo $pid ?>,1)">up</button></td></td>
							<tr><td><span style="font-size: 140%;font-weight: 130%;" id="post<?php echo $pid?>" > <?php echo $votes;?> </span></td></tr>
							<tr><td><button class="btn btn-primary btn-sm" id="down<?php echo $pid ?>" onclick="vote(<?php echo $uid ?>,
							<?php echo $pid ?>,0)">Down</button></td></tr>
							
					</table>
					
				</div>
				<div class="dis-right-footer">
					<table>
						<tr><td><?php echo $author;  ?></td></tr>
						<tr><td><?php $time = date('d/m/Y h:i A' ,$time) ; echo $time; ?></td></tr>
					</table>
				</div>
				<div class="seperator">
					
				</div>
			<?php endforeach; ?>
			<?php else: ?>
				<blockquote class="blockquote">
				  <p class="mb-0">You have 0 posts!</p>
				</blockquote>
		<?php endif; ?>
			<!-- <div class="dis-content">
					is is discume my first to do completed task .byeeeeThis is my to do list .I need to do this by so and so .so i wrote it down in my to do list.
 				If i complete it then this would become my first to do completed task ssiis is discume my first to do completed task .byeeeeThis is my to do list .I need to do this by so and so .so i wrote it down in my to do list.
 				If i complete it then this would become my first to do completed task ssi
			</div>
			<div class="middle">
				Posted in 
			</div>
			<div class="dis-header">
				<a href="#" style="text-decoration:none !important;">this is discume my first to do completed task .byeeeeThis is my to do list .I need to do this by so and so .so i wrote it down in my to do list.
 				If i complete it then this would become my first to do completed task ssion</a>
			</div>
			<div class="ans-border">
						
			</div>
			<div class="dis-left-footer">
				<div ><span style="font-weight:130%;font-size:120%;">12</span> Votes </div>
				<table>
						<tr><td><button class="btn btn-primary btn-sm" id="up">up</button></td>
						<td><button class="btn btn-primary btn-sm" id="down" style="margin-left:10px;">Down</button></td></tr>
						
				</table>
				
			</div>
			<div class="dis-right-footer">
				<table>
					<tr><td>Sridhar</td></tr>
					<tr><td>17-10-2016 12:20 pm</td></tr>
				</table>
			</div>
			<div class="seperator">
				
			</div> -->
		</div>
	</div>




	<script type="text/javascript" src="mnnit_forums_jquery.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
    <!-- <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
    <script src = "js/bootstrap.js"></script>
</body>
</html>