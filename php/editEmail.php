<?php
$newEmail = $_POST["newEmail"];
$oldEmail = $_POST["oldEmail"];
require '../php/connect.php';
if(!empty($_POST)){
	if ($conn){
		$query = "SELECT * FROM dbo.UserAccount WHERE Email = :Email AND 
		UserTypeId = (SELECT UserTypeId FROM dbo.UserTypes WHERE UserType='Admin')";
		$query_params = array(':Email' => $oldEmail);
		$stmt = $conn->prepare($query);
		$result = $stmt->execute($query_params) or die();
		$row = $stmt->fetch();
        
        if($row){
                $new_uery = "UPDATE dbo.UserAccount SET Email = :Email
				WHERE UserId = :ID";
                $new_query_params = array(
					':Email' => $newEmail,
					':ID' => $row['UserId']);
                $new_stmt = $conn->prepare($new_query);
                $new_result = $new_stmt->execute($new_query_params) or die();
				$new_row = $new_stmt->fetch();
				if($new_row){
					if (session_status() == PHP_SESSION_NONE) {
							session_start();
					}
					unset($_SESSION['editUserEmail']);
					$_SESSION['editUserEmail'] = $new_row['Email'];
				} else {
					$err_msg = "error updating user with email: $oldEmail";
				}
		} else {
			    $err_msg = "Cannot find user with email: $oldEmail.  Try again";
		}
	}
}
echo $err_msg;
?>
