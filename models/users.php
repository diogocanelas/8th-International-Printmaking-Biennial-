<?php


class Users {

	public function validatePassword($db,$username,$password){
	 $validation = array();
    try{
    	$results = $db->prepare("select Users.user_id
	          FROM Users
	          WHERE Users.user_name = '".$username."' 
	          AND Users.user_password = '".$password."' ");  
        if($results->execute()){
          $validation=$results->fetch(PDO::FETCH_ASSOC);
        }else{
          $validation = NULL;
        }
    }catch(Exception $e){
        echo $e->getMessage();
        die("CONNECTION FAILED");
    }
    return $validation;
  }

}