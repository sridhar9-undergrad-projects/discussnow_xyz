<?php

		require_once ('config.php');	

		if (!class_exists('db')){

			class db{
				

				function __construct (){
					// echo "<br> object has been formed";
					$this->connect();
				}

				function connect (){
					global $mysqli;
					$mysqli = new mysqli (DB_HOST,DB_USER,DB_PASS,DB_NAME);
					
					if ($mysqli -> connect_errno){
						echo "<br>error : " . $mysqli -> connect_error;
						exit();
					}
					// else
					// 	echo "connection is set JoombaDatabase";



				}

				function clean ($array){
					// foreach ($array as $key => $value) {
					// 	$mysqli_arr [] = $mysqli;
					// }
					// return array_map('mysqli_real_escape_string', $mysqli_arr ,$array);
					return $array;
				}
                 
				function hash_password ($password){
					$secure_pass = md5 ($password);
					return $secure_pass;
				}

				function insert ($table, $fields ,$values){

					global $mysqli;

					$fields = implode(", ",$fields);
					$values = implode ("', '",$values);
					// echo "-------<br>";
					// print_r($fields);
					// echo "----------<br>";
					// print_r($values);
					
					$query = "INSERT INTO $table ($fields) VALUES ('$values')";
					

					$result = $mysqli -> query($query);
					if ($result)
						return $mysqli->insert_id ;
					else{
						echo '<br>error occured : ' . $mysqli->error;
						exit();
					}


				}

				function select ($sql){

						global $mysqli ;
						
						$selected_data = $mysqli->query($sql);
						return $selected_data;
						
				}

			}

		}
		global $db;
		$db = new db();


?>