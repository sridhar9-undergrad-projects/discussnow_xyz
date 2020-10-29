
//discussion votes 
function dis_vote(dis,u_id,v){
	//alert("hey "+dis);
	var votes = $("#dis").text();
	
	$.post(
		"includes/queries.php",
		{
			vote : v,
			dis_id:dis,
			uid:u_id
		},
		function(data,status){
			//alert("data -> "+data+" status:"+status);
			if(status=="success"){
				votes = Number(votes);
				if(data==1 && v==1){//upvote
					
					$("#dis").text(votes+1);
				}
				else if(data==1 && v==0){//downvote
					$("#dis").text(votes-1);
				}
				else{
					alert("you just voted !");//otherwise
				}
			}
			else{
				alert("error occured!");
			}
		}

	);

}


//post votes

function vote(u_id,p_id,v){
	var votes = $("#post"+p_id).text();
	//alert(votes);
	$.post(

		"includes/queries.php",
		{
			vote: v,
			user_id:u_id,
			post_id:p_id
		},
		function (data,status){
			//alert("data "+data+" status "+status);
			if(status=="success"){
				votes = Number(votes);
				if(data==1){
					if(v==1){
						$("#post"+p_id).text(votes+1) ;
					}
					if(v==0){
						$("#post"+p_id).text(votes-1) ;
					}
				}
				else{
					alert("you just did that !");
				}
			}
			else{
				alert("error occured!");
			}
		}
		);
}



//add post to the discussion 


function update_post (dis_status,u_id,disid){ 

	var mgs = $("#post-me").val();

	//if status is 1 then user has the permission to add post but i am not checking it now
	//alert("mgs "+ mgs);
	//alert("working");
	var d = new Date();
	var t = d.getTime();
	//var t = Math.round(new Date().getTime()/1000.0);
	//alert ("time "+t);
	tm = Number(t);
	tm = Math.round(tm/1000.0);
	// var tm = "1487654321";
	//alert("time "+ t);
	// tm = Number(tm);


	$.post(

		"includes/queries.php",
		{
			content : mgs,
			uid : u_id,
			dis_id : disid,
			time : tm
		},
		function (data , status){
			if(status == "success"){
				//text area to null
				$("#post-me").val(""); 
				
				//up - down buttons to vote
				var vote_area = "<div class='ans-votes' id='ans-votes'"+data+"> <table><tr><td><button class='btn btn-primary btn-sm' id='up'"+data+ "onclick='vote("+u_id+","+data+",1)'>up</button></td></tr><tr><td><span style='font-size: 140%;font-weight: 130%;' id='post'"+data+" > 0 </span></td></tr><tr><td><button class='btn btn-primary btn-sm' id='down'"+data+" onclick='vote("+u_id+","+data+",0)'>Down</button></td></tr></table></div>";
				$(".ans-border:last").after(vote_area);

				//increment total posts count
				count = $("#total_posts").text();
				count = Number(count);
				count++;
				$("#total_posts").text(count);

				// //add ans content
				var content = "<div class='ans-content'>" + mgs+"</div>";
				$(".ans-border:last").after(content);

				// to get border line
				var str = "<div class='ans-border'></div>";
				$(".ans-border:last").after(str);
				

			}
			else{
				alert("error occured !");
			}
			
		}
		);

		

		//add ans-footer		
		$.post(
			"includes/queries.php",
			{
				get_name : u_id
			},
			function(data,status){
				if(status=="success"){
					author_name = data;

					var d = new Date();
					var hours = d.getHours();
					var min = d.getMinutes();
					var sec = d.getSeconds();

					var full_year = d.getFullYear();
					var month = d.getMonth() + 1;//starts form 0
					var date = d.getDate();

					full_date = date + "/" + month +"/"+full_year+" "+ hours+":"+min +":"+sec ;
					alert("confirm post!");
					var str = "<div class='ans-footer'><table><tr><td><span ></span>posted "+ full_date +"</td></tr><tr><td>"+author_name+"</td></tr><tr><td>1000</td></tr></table></div>";
					//alert("i am after");
					$(".ans-border:last").after(str);
					}
					
				//alert("i am in");
			}
			);


}

//index.php check password
function check_pass(){
	alert("hi");
	var password = $("#pd1").val();
    var confirmPassword = $("#pd2").val();

    if (password != confirmPassword){
       alert("passords do not match!");
       window.location.href = "index.php";
    }
}

//to create a new discussion 

function new_dis(uid){

	var title = $("#discussion_title").val();
	var content = $("#discussion_content").val();
	$("#discussion_content").val("");
	$("#discussion_title").val("");
	//alert("hai");
	//$("#close_me").click();
	$('#myModal').modal('toggle'); 
	//$('#modal').modal().hide();
	//$("#modal .close").click()
	//alert("title : "+title+"content: "+content);

	if(title.length<10 || content.length<10){
		alert("content too short !");
		return ;
	}
	var d = new Date();
	d = d.getTime();
	t = Number(d);
	time = Math.round(t/1000.0);
	$.ajax({
		url:"includes/queries.php",
		data : {u_id:uid ,dis_title: title,dis_content:content,unix_time:time},
		method : "post",
		success : function (data){
			//window.open("http://localhost/Myphpfiles/mnnit_forums/discussion-page.php?dis_id="+data,"_blank");
			// window.location.href = "http://localhost/Myphpfiles/mnnit_forums/discussion-page.php?dis_id="+data;
			window.location.href = "discussion-page.php?dis_id="+data;
		}

		});

}



