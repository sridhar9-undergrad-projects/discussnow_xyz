<?php
    require_once ('load.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST' and empty($_POST['login_user']) and empty($_POST['login_pass'])){
      
      $queries->register('index.php');
    }
    $logged = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' and !empty($_POST['login_user']) and !empty($_POST['login_pass'])){
        $logged = $queries->login('home-page.php');
    }
    if(!empty($logged)){
        echo "Username, password combination is incorrect!";
    }
    if(!empty($_GET['action']) && $_GET['action']=='logout'){
      $logout = $queries->logout();
      //echo "logout successfull<br>";
      header ("Location: index.php");
      exit(0);
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
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
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" data-toggle="collapse" data-target="#main-navbar" class="navbar-toggle">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!--<a href="#" class="navbar-brand" width="30px" height="30px"><img src="images/to-do-list.jpg" alt="To do list">To do list</a>-->
				<a href="#" class="navbar-brand" style="margin-left:100px;"><span id="brand"><span style="color:#cce6ff;">Mnnit</span> <span style="color:#ffb3b3;">Forums</span></span></a>
				
			</div>
			<div class="collapse navbar-collapse" id="main-navbar">
				<ul class="nav navbar-nav navbar-right" style="margin-top:5px;">
					
				
	              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-top:5px; margin-bottom:5px;">
                    Sign in
                  </button>		
                  <li style="margin-right:100px;"></li>														
						
				</ul>
			</div>
		</div>	
	</nav>

	<!-- For sign in model -->

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   
  		<div class="modal-dialog" role="document">
    		<div class="modal-content">
     			 <div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        			<h4 class="modal-title" id="myModalLabel" color="white">Sign in</h4>
     			 </div>
          		<div class="modal-body">
            			<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" >
    						<div class="form-group">
    	                        <div class="col-sm-8 col-sm-offset-1">
    	                              <input class="form-control" name="login_user"  >
    	                        </div>
    	                    </div>
    	                    <div class="form-group">
    	                        <div class="col-sm-8 col-sm-offset-1">
    	                              <input  type="password" class="form-control" name="login_pass">
    	                        </div>
    	                    </div>
                            <div align="right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Sign in </button>
                            </div>
                        </form>
          		</div>
          		<!-- <div class="modal-footer">
            	   
         	     </div> -->
        </div>
      
    </div>
    
  </div>
	
<!-- end of model   -->


      <!-- error prompt  -->

      <?php  if(!empty($_GET['user']) && $_GET['user']=='yes') : ?>
          <div class="alert alert-danger">
            <strong>Error!</strong> Username already exists try different!
         </div>
         <?php  ?>
      <?php endif; ?>
       <?php  if(!empty($_GET['reg']) && $_GET['reg']=='yes') : ?>
          <div class="alert alert-success">
            <strong>Registered Successfully!</strong> Please login to continue
        </div>
      <?php endif; ?>

     <!-- end error prompt  -->

      <div class="container">
          <div class="row">
              <div class="col-sm-offset-0">
                  <div style="font:lighter 400% helvitica;">Create your account</div>
              </div>
          </div>
          
              <div class="col-sm-6 ">
                  <form class="form-horizontal" style="margin-top:20px" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="post">
                      <div class="form-group">
                          <div class="col-sm-10">
                              <input type="text" class="form-control" name="fname" placeholder="Full name" required>
                          </div>
                      </div>
                      <div class="form-group">
                      		<div class="col-sm-10">
                              <input type="text" class="form-control" name="username" placeholder="User name" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-10">
                              <input type="email" class="form-control" name="email" placeholder="someone@example.com" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-10">
                              <input type="password" class="form-control" id="pd1" name="password1" placeholder="password" required>
                              <span id="helpBlock" class="help-block">Password must be atleast 8 characters long</span>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-10">
                              <input type="password" class="form-control" id="pd2" name="password" placeholder="re-enter password" required>
                          </div>
                      </div>
                      <!--
                      <div class="form-group">
                        <div class="radio">
                          <div class="col-sm-4">
                              <label style="font-size:130%;font-weight:200;">
                                  <input type="radio" name="sex" value="male"/>
                                  Male
                              </label>
                          </div>
                        </div>                             
                      </div>
                      <div class="form-group">
                         <div class="radio">
                            <div class="col-sm-4">
                              <label style="font-size:130%;font-weight:200;">
                                  <input type="radio" name="sex" value="female"/>
                                  Female
                              </label>           
                            </div>              
                         </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-10">
                          <div class="input-group">
                              <div class="input-group-addon">
                                +91
                              </div>
                              <input name="phoneNumber" placeholder="Phone number" class="form-control">
                          </div>
                        </div> 
                      </div>
                      -->
                      <div class="form-group">
                          <div class="col-sm-10">
                              <div class="checkbox">
                                  <label>
                                      <input type="checkbox" name="terms" required="">
                                      I agree to the terms &amp; conditions .
                                  </label>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-4">
                              <button  onclick="check_pass()" type="submit" class="btn btn-primary">Create account</button>
                          </div>
                      </div>

                  </form>
              </div>
              
          
        
      </div>

    <script type="text/javascript" src="js/jquery.js"></script>
    <!--<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
    <script src = "js/bootstrap.js"></script>

</body>
</html>