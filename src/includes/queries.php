<?php
		
     	require_once('db.php');
		if (!class_exists('queries')){
			class queries {


				/*********** login functions *************/

				function register ($redirect){
					global $db;

					//check to make sure form submission is coming from our script     

					if (!empty( $_POST ) && !empty($_POST['fname']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email'])){
						//require_once('db.php');
						//check user exists -------- start-----------
						// $table = 'user';
						// $tmp_name = $_POST['username'];
						// $query = "SELECT * from $table where user_name = '$tmp_name'";
												
						

						
						// if(!empty($res)){
						// 	header("Location: $redirect?user=yes");
						// 	exit(0);
						// }

						//check user exists -------- end-----------
						$table = 'user';
						$fields = array ('full_name','user_name','e_mail','password','unix_time');
						
                       
						$values = $db->clean($_POST);
						 
						//print_r($values);

						$fullname = $_POST['fname'];
						$username = $_POST['username'];
						$password = $_POST['password'];
						$useremail = $_POST['email'];
						$time = time();
						//userreg = date('Y-m-d h:i:s' ,$userreg);

						$userpass = $db->hash_password ($password);


						$values = array (
										"$fullname",
										"$username",
										"$useremail",
										"$userpass",
										"$time"
						);
						
						$val = $db->insert($table ,$fields ,$values);

						if ($val) {
							//echo "<br> New record has been added";
							// header ("Location: http://localhost/Myphpfiles/mnnit_forums/$redirect");
							header("Location: $redirect?reg=yes");
							exit();
						}
						else{
							die('<br>Form submission failed');
						}

					}
					else{
						//echo "fill and submit";

						// header("Location: http://localhost/Myphpfiles/mnnit_forums/$redirect");
						header("Location: $redirect");
						exit();
					}

				}


				function get_id_num ($user_name){
					global $db;
					$table = 'user';
					$query = "SELECT user_id FROM $table WHERE user_name='$user_name'";
					$res = $db->select ($query);
					while ($row = $res->fetch_assoc ()){    	//fetch_assoc is used here
						$val = $row['user_id'];
					}

					return $val;
				}

				function login ($redirect){
					global $db;

					if ($_POST['login_user'] && $_POST['login_pass']){
						//echo "in login fun";
						$values = $db->clean($_POST);

						$subname = $values['login_user'];
						$subpass = $values['login_pass'];

						$table = 'user';

						$sql = "SELECT * FROM $table WHERE user_name = '" . $subname . "'";


						$results = $db-> select ($sql);
						
						while ($row = $results->fetch_object()){
							$data []= $row; 
						}
						if (empty($data)){
							//echo "<br>NO user exits with that name";
							return 'invalid';
						}
						//print_r($data);
						$id = $this->get_id_num($subname);


						foreach ($data as $arr) {
							foreach ($arr as $key => $value) {
								if ($key == 'password')
									$stopass = $value;	
							}						
						}
						
						//$stopass = $data["user_pass"];
						$subpass = $db->hash_password($subpass);

						//echo '<br>' . $stopass .'<br>' ;
						//echo '<br>' . $subpass .'<br>' ;
						//echo "<br>this is login";

						if ($subpass == $stopass){

							$auth_id = $db->hash_password($subpass);

							//set cookie 

							setcookie ('log_auth[user]',$subname,time() + 86400 ,'','','',TRUE);
							setcookie ('log_auth[auth_pass]',$auth_id,time() + 86400,'','','',TRUE);
							//setcookie('log_auth[uid]',$id,time()+86400,'/login/','','',TRUE);

							//build our redirect

							//change the header location
							//echo "this is location";
							// header("Location: http://localhost/Myphpfiles/mnnit_forums/$redirect?uid=$id");
							header("Location: $redirect");
							exit();

						}
						else{
							//echo "<br>this is login";
							$str = "invalid";
							return $str;
						}


					}
					else{

						return 'invalid';
					}

				}

				function logout(){
						$userout = setcookie('log_auth[user]','',time()-3600,'','','',true);
						$passout = setcookie('log_auth[auth_pass]','',time()-3600,'','','',true);
						//$id_out = setcookie ('log_auth[uid]','',time()-3600,'/login/','','',true);

						if ($userout == TRUE && $passout == TRUE){
							return TRUE;
						} 
						else{
							return FALSE;
						}
				}

				function check_login(){
						global $db;
						//echo "sridhar";
						$cookie = $_COOKIE ['log_auth'];

						$user = $cookie['user'];
						$authpass = $cookie ['auth_pass'];

						if (!empty ($cookie)){

							$table = 'user';
							$query = "SELECT * FROM $table WHERE user_name = '" . $user . "'";
							$results_db = $db->select ($query);
							//echo '<br>';
							//print_r($results_db);
							if (!$results_db){
								die('Sorry ,that username does not exists');
							}

							while ($row = $results_db->fetch_object()){
								$results [] = $row; 
							}

							foreach ($results as $arr) {
								foreach ($arr as $key => $value) {
									if ($key == 'password')
										$stopass = $value;	
								}						
							}

							//$stopass = $results['user_pass'];

							$stopass = $db->hash_password($stopass);

							if ($stopass == $authpass){
								$results = TRUE;
							}
							else{
								$results = FALSE ;
							}

							return $results;

						}
						else{

							//Build our redirct to login page
							//echo "this is final";
							// header ("Location: http://localhost/Myphpfiles/mnnit_forums/index-page.php");
							header("Location: index.php");
							exit(0);
						}


				}







				/*********** end of login functions *****/



				function add_discussion (){
					global $db;

					//check to make sure form submission is coming from our script     

					if (!empty( $_POST )){
						//require_once('db.php');
						$table = 'discussions';
						$fields = array ('admin_id','status','dis_title','dis_content','unix_time');
						
                       
						$values = $db->clean($_POST);
						 
						//print_r($values);

						$id = $_POST['id'];
						$status = $_POST['status'];
						$title = $_POST['dis_title'];
						$time = $_POST['time'];
						$content = $_POST['admin_post'];
						//userreg = date('Y-m-d h:i:s' ,$userreg);

						//$userpass = $insert->hash_password ($userpass);

						$query = "SELECT COUNT(*) AS cnt FROM $table WHERE admin_id=$id AND unix_time=$time";
						$res = $db->select($query);
						$val = $res->fetch_assoc();
						$val = $val['cnt'];
						if ($val!=0){
							echo "<br>duplicate entry in database ... please check";
							return ;
						}


						$values = array (
										"$id",
										"$status",
										"$title",
										"$content",
										"$time"
						);
						
						$val = $db->insert($table ,$fields ,$values);

						if ($val) {
							echo "<br> New record in discussion table has been added";
							
							//exit();
						}
						else{
							die('<br>new thread failed<br>');
						}

					// 	$query = "SELECT dis_id FROM discussions ORDER BY dis_id DESC LIMIT 1";
					// 	$last_id = $db->select($query);
					// 	while ($row = $last_id->fetch_assoc()){
					// 		$l_id =  $row ['dis_id'];
					// 	}
						
					// 	$last_id->free();
					// 	//print_r( $result);
					// 		//start from here  and above commented .
					// 	//echo "<br>" . $a;
					// 	//echo $last_id;
					// 	//exit();

					// 	$table = 'posts';
					// 	$fields = array ('user_id','dis_id','p_title','p_content','unix_time');
					// 	$title ="";
					// 	$content = $_POST['admin_post'];

					// 	$values = array (
					// 					"$id",
					// 					"$l_id",
					// 					"$title",
					// 					"$content",
					// 					"$time"
					// 	);

					// 	$val = $db->insert($table ,$fields ,$values);

					// 	if ($val == TRUE) {
					// 		echo "<br> New record in posts table has been added";
							
					// 		//exit();
					// 	}
					// 	else{
					// 		die('<br>thread creation failed<br>');
					// 	}
						

					// }
					// else{
					// 	echo "<br>fill and submit";
					// 	// header("Location: http://localhost/Myphpfiles/mnnit_forums/profile-view.php");
					// 	 exit();
					// }

					}
				}

				function get_priority ($dis_id){
					if (!empty($dis_id)){
						$table = "posts";
						global $db;
						$query = "SELECT p_id FROM $table WHERE dis_id=$dis_id";
						$results = $db->select ($query);
						while ($row = $results->fetch_assoc()){
							$posts[] = $row['p_id'];
						}
						//print_r($posts);
						if (empty($posts)){
							return false;
						}
						foreach ($posts as $key => $value) {
							$table = "votes";
							$query = "SELECT COUNT(*) AS cnt FROM $table WHERE post_id=$value AND type=1";
							$res = $db->select ($query);
							$val = $res->fetch_assoc();
							$up_votes = $val['cnt'];

							$query = "SELECT COUNT(*) AS cnt FROM $table WHERE post_id=$value AND type=0";
							$res = $db->select ($query);
							$val = $res->fetch_assoc();
							$down_votes = $val['cnt'];

							$votes=$up_votes-$down_votes;
							$priority["$value"] = $votes;

						}
						//print_r($priority);
						arsort($priority);
						return $priority;
						//print_r($priority);
						//exit(0);
					}
				}

				function my_posts ($uid){
					if (!empty($uid)){
						$table = "posts";
						global $db;
						//$query = "SELECT dis_id,p_title,p_content FROM $table WHERE user_id = $uid ORDER BY unix_time DESC";
						$query = "SELECT user_id,dis_id,p_content,unix_time FROM $table ORDER BY unix_time DESC";
						$results = $db->select($query);
						while ($row = $results->fetch_object()){
							$result [] = $row ;
						}
						if (!isset($result)){
							echo "<br>no user exist with that id -- my posts";
							//exit();
							return 0;
						}
						//print_r( $result);
						//exit();
						return $result;
					}
					else{
						echo "<br>something went wrong ";
						exit(1);
					}

				}

				function new_accepts($uid){
					if (!empty($uid)){
						
					}
				}

				function check_new_requests ($uid){
					if (!empty($uid)){
						$table = 'request';
						global $db ;
						$query = "SELECT dis_id,user_id FROM $table WHERE admin_id = $uid AND status = 0 ORDER BY unix_time DESC";
						$res = $db->select($query);
						
						
						
						while ($row = $res->fetch_object()){
							$result [] = $row;
						}
						if (!isset($result)){
							//echo "<br> no new request";
							return false;
						}
						
						//print_r($result);
						//exit();

						// $table = 'user';
						// foreach ($result as $arr) {
						// 	foreach ($arr as $key => $value) {
						// 		$query = "SELECT user_name FROM $table WHERE user_id = $value ";
						// 		$results = $db->select($query);
						// 		while ($row = $results->fetch_object()){
						// 			$val[] = $row ;
						// 		}
								
						// 	}
						// }

						// echo "<br>";
						// print_r($result);
						$i=0;
						foreach ($result as  $key => $val) {
									//echo "<br>" . $key;
									$disid  = $val->dis_id;
									$user_id  = $val->user_id;

									//echo "<br>" . $disid ." - ". $user_id ;
									
									$arr[$i][0] = "$user_id";
									$arr[$i++][1] = "$disid" ;
									
						}
						
						
						//print_r($arr);

						return $arr;
					}
					else{
						echo "<br> something went wrong .";
						exit(1);
					}
				}

				function change_status($dis,$uid,$status,$mod_time){
					$table = 'request' ;
					global $db ;
					$query = "UPDATE $table SET status=$status,unix_time=$mod_time WHERE dis_id = $dis AND user_id = $uid ";
					$ret_value = $db->select ($query);
					if ($ret_value){
						echo "<br> update sucessfull";
					}
					else{
						echo "<br> update failed";
					}

				}

			// Ajax work to store upvote and downvote in database for posts
				function check_to_vote($vote,$user,$post){
					global $db;

					//$query = "SELECT COUNT (*) FROM votes WHERE post_id=$post AND user_id=$user";
					$query = "SELECT COUNT(*) AS cnt FROM votes WHERE post_id=$post AND user_id=$user AND type=$vote";
					//echo "<br>" . $query;
					$res = $db->select($query);
					if (empty($res)){
						//echo "it is empty";
						return true;
					}
					
					while ($row = $res->fetch_assoc()){
						$value = $row['cnt'];	

					}
					//echo $value;
					$type = $vote;
					if (empty($value))
						{
							$type = $type==1?0:1;
							$query = "SELECT id AS id FROM votes WHERE post_id=$post AND user_id=$user AND type=$type";
							$res = $db->select ($query);
							//$type = $type==1?0:1;
							while ($row = $res->fetch_assoc()){
								$value = $row ['id'];
							}
							if(empty($value)){
								$type = $type==1?0:1; // to get actual type
								$table = 'votes';
								$fields = array ('post_id','type','user_id');
								$values = array (
												$post,
												$type,
												$user
										);

								$val = $db->insert ($table,$fields,$values);
								return true;
							}
							else{
								$type = $type==1?0:1; // to get actual type
								$query = "UPDATE votes SET type= $type WHERE id=$value";
								$ret_value = $db->select($query);
								if($ret_value){
									return true;
								}
								else{
									return false;
								}
							}

						}
					else
						{
							return false;
						}
				}

				function check_to_vote_for_dis ($dis_id,$uid,$type){
					global $db;
					$query = "SELECT id AS id FROM dis_votes WHERE dis_id=$dis_id AND user_id=$uid AND type=$type";
					$res = $db->select ($query);
					//$type = $type==1?0:1;
					while ($row = $res->fetch_assoc()){
						$value = $row ['id'];
					}
					
					if(empty($value)){
						$type = $type==1?0:1;
						$query = "SELECT id AS id FROM dis_votes WHERE dis_id=$dis_id AND user_id=$uid AND type=$type";
						$res = $db->select ($query);
						//$type = $type==1?0:1;
						while ($row = $res->fetch_assoc()){
							$value = $row ['id'];
						}
						if(empty($value)){
							$type = $type==1?0:1; // to get actual type
							$table = 'dis_votes';
							$fields = array ('dis_id','type','user_id');
							$values = array (
											$dis_id,
											$type,
											$uid
									);

							$val = $db->insert ($table,$fields,$values);
							return true;
						}
						else{
							$type = $type==1?0:1; // to get actual type
							$query = "UPDATE dis_votes SET type= $type WHERE id=$value";
							$ret_value = $db->select($query);
							if($ret_value){
								return true;
							}
							else{
								return false;
							}
						}

						
					}
					else{
						return false;
					}
					
				}
				


				function get_name($uid,$table){
					if (!empty($uid)){
						//$table = 'user';
						global $db;
						if ($table == 'user')
							$query = "SELECT full_name FROM $table WHERE user_id = $uid";
						if ($table == 'discussions')
							$query = "SELECT dis_title FROM $table WHERE dis_id = $uid";
						$results = $db->select($query);
						

						// if (!isset($result)){
						// 	echo "<br> query unsucessfull";
						// 	exit();
						// }

						while ($row = $results->fetch_object()){
							$result [] = $row;
						}

						if (empty ($result)){
							return ;
						}
						//print_r($result);
						if ($table == 'user')
							foreach ($result as $value) {
								$val = $value->full_name;
							}
						if ($table == 'discussions')
							foreach ($result as $value) {
								$val = $value->dis_title;
							}

						//echo "<br>" . $val;  ;
						return $val;
						
					}
				}

				function get_dis_votes ($dis_id){
					if (!empty($dis_id)){
						$table = 'dis_votes';
						global $db;
						$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE dis_id=$dis_id AND type=1";
						$result = $db->select ($query);
						$row = $result->fetch_assoc();
						$up_votes = $row['cnt'];
						$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE dis_id=$dis_id AND type=0";
						$result = $db->select ($query);
						$row = $result->fetch_assoc();
						$down_votes = $row['cnt'];
						$votes = $up_votes - $down_votes;
						return $votes;
					}
				}
				function get_post_votes($pid){
					if(!empty($pid)){
						$table = 'votes';
						global $db;
						$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE post_id=$pid AND type=1";
						$result = $db->select ($query);
						$row = $result->fetch_assoc();
						$up_votes = $row['cnt'];
						$query = "SELECT COUNT(*) AS cnt FROM $table  WHERE post_id=$pid AND type=0";
						$result = $db->select ($query);
						$row = $result->fetch_assoc();
						$down_votes = $row['cnt'];
						$votes = $up_votes - $down_votes;
						return $votes;
					}
				}

				function get_details ($uid){
					if (!empty($uid)){
						$table = 'discussions';
						global $db;
						// if ($table == 'user')
						// 	$query = "SELECT full_name FROM $table WHERE user_id = $uid";
						if ($table == 'discussions')
							$query = "SELECT * FROM $table WHERE dis_id = $uid";
						$results = $db->select($query);
						

						// if (!isset($result)){
						// 	echo "<br> query unsucessfull";
						// 	exit();
						// }

						while ($row = $results->fetch_assoc()){
							$result [] = $row['dis_id'];
							$result [] = $row['admin_id'];
							$result [] = $row['status'];
							$result [] = $row['dis_title'];
							$result [] = $row['dis_content'];
							
							$result [] = $row['unix_time'];
						}

						if (empty ($result)){
							return false;
						}
						else{
							return $result;
						}
						// print_r($result);
						// exit(0);
						
					}
				}

				function get_id ($with,$get,$table){
						global $db;
						if (isset($with) && $get && $table){
							$query = "SELECT $get FROM $table WHERE dis_id = $with";
							$results = $db->select($query);

							// if (!isset($result)){
							// 	echo "<br> query unsucessfull";
							// 	exit();
							// }

							while ($row = $results->fetch_object()) {
								$result[] = $row;
							}
							
							foreach ($result as $value) {
								$val = $value->admin_id;
							}
							return $val;
						} 
				}

				function send_request($dis_id,$uid,$admin_id,$time){
					global $db;
					if (isset($dis_id) && isset($uid) && isset($admin_id)){
						
						$table = 'request';
						$fields = array ('dis_id','user_id','admin_id','status','unix_time');
						$values = array ("$dis_id","$uid","$admin_id","0",$time);
						$ret = $db->insert($table,$fields,$values);
						if ($ret){
							echo "<br>new record has been added";
						}
						else{
							echo "<br> error updating request";
							exit();
						}
					}
					else{
						echo "<br>error ";
						exit();
					}
				}

				function check_request ($dis , $uid){
						global $db;
						if (isset($dis) && isset($uid)){
							//check user is admin if yes return false
							$table = 'discussions';
							$query = "SELECT status FROM $table WHERE dis_id = $dis AND admin_id = $uid";
							$results = $db->select($query);
							//print_r($results);
							if (isset($results)){
								//print_r($results);
								//echo "<br> this is admin for the post";
								return true;
							}

							$table = 'request';
							$query = "SELECT status FROM $table WHERE dis_id = $dis AND user_id = $uid";
							$results = $db->select($query);
							//print_r($results);
							if (!isset($results)){
								print_r($results);
								echo "<br> No record found";
								return false;
							}

							while ($row = $results->fetch_object()) {
								$result[] = $row;
							}
							
							foreach ($result as $value) {
								$val = $value->status;

								//echo "<br>" . $val;

								if ($val ==0 || $val==1 || $val ==2 || $val ==3){
									return true;
								}

							}
						}
				}


				function home_posts($uid){
					if (!empty($uid)){
						global $db;
						$table = "subscribers";
						$query  = "SELECT dis_id FROM $table WHERE user_id = $uid";
						$result = $db->select($query);

						if (empty($result)){
							echo "No subscriptions";
							return ;
						}
						while ($row = $result->fetch_assoc ()) {
							$arr[] = $row['dis_id'];
						}

						$table = "posts";
						$str = implode(' ,',$arr);
						$query = "SELECT user_id,dis_id,p_content,unix_time FROM $table WHERE dis_id IN ($str) ORDER BY unix_time DESC";
						$result = $db->select ($query);
						while($row = $result->fetch_assoc ()){
							$arr2[] = $row ['user_id'];
						}
						print_r($arr2);

					}

				}

				// returns array of pid (recent post id) : Doing this in home-page itself

				// function order_of_home_posts(){

				// }
			}

		}

		global $queries;
		$queries = new queries(); //instantiation of self object 

		//vote for the post 
		if (isset($_POST['vote']) && !empty($_POST['user_id']) && !empty($_POST['post_id']) ) {

			global $db;
			//echo "i am here";
			$vote = $_POST['vote'];
			$user = $_POST['user_id'];
			$post = $_POST['post_id'];
			$res = $queries->check_to_vote($vote,$user,$post);
			//$res = false;
			if ($res){

				echo "1";

				}
			else{
					echo "0";
				}

		}


		//vote for the discussion 	
		if (isset($_POST['vote']) && !empty ($_POST['dis_id']) && !empty($_POST['uid'])){
			global $db;
			
			$type = $_POST['vote'];
			$dis_id = $_POST['dis_id'];
			$uid = $_POST['uid'];
			$id = $queries->check_to_vote_for_dis ($dis_id,$uid,$type);
			
			if($id){

				//"UPDATE $table SET status=$status,unix_time=$mod_time WHERE dis_id = $dis AND user_id = $uid ";
				echo "1";

			}
			else{
				echo "0";
			}
			// if ($per){
			// 	$table = 'dis_votes';
			// 	$fields = array ('dis_id','type','user_id');
			// 	$values = array (
			// 					$dis_id,
			// 					$type,
			// 					$uid
			// 			);

			// 	$val = $db->insert ($table,$fields,$values);
			// 	if ($val){
			// 		echo "1";
			// 	}
			// 	else{
			// 		echo "2";
			// 	}
			// }
			// else{
			// 	echo "0";
			// }
		}


		//add post to the discussion in discussion page 
		if (!empty($_POST['content']) && !empty($_POST['uid']) && !empty($_POST['dis_id'])&& !empty($_POST['time'])){
			global $db;
			$content = $_POST['content'];
			$uid = $_POST['uid'];
			$dis_id = $_POST['dis_id'];
			$time = $_POST['time'];
			//echo "hai there";
			$table = "posts";
			$fields = array ('user_id','dis_id','p_content','unix_time');
			$values = array ($uid,$dis_id,$content,$time);

			$val = $db->insert ($table,$fields,$values);
			
			if ($val ){
				echo $val;
			}
			else{
				echo "0";
			}

		}

		if(!empty($_POST['get_name'])){
			$name = $queries->get_name($_POST['get_name'],"user");
			if($name){
				echo $name;
			}
			else{
				echo "UNKOWN";
			}
		}

		
		//to add a new discussion 

		if(!empty($_POST['u_id']) && !empty($_POST['dis_title']) && !empty($_POST['dis_content']) && !empty($_POST['unix_time'])){
			$uid = $_POST['u_id'];
			$title = $_POST['dis_title'];
			$content = $_POST['dis_content'];
			$unix_time = $_POST['unix_time'];

			global $db;
			$table = "discussions";
			$fields = array ('admin_id','status','dis_title','dis_content','unix_time');
			$values = array ($uid , "1",$title,$content,$unix_time);
			$val = $db->insert($table,$fields,$values);
			if($val){
				echo $val;
			}
			else{
				echo "0";
			}

		}



?>