function dis_vote(dis,u_id,v){
	alert("hey "+dis);
	var votes = $("#dis").text();
	
	$.post(
		"../includes/queries.php",
		{
			vote : v,
			dis_id:dis,
			uid:u_id
		},
		function(data,status){
			alert("hey");
			//alert("data -> "+data+" status:"+status);
		}

	);

}