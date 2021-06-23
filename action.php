<?php
include('database_connection.php');
if(isset($_POST["action"]))
{
	if($_POST["action"] == "insert")
	{
		$query = "INSERT INTO tbl_sample (first_name, salary,email) VALUES ('".$_POST["first_name"]."', ".$_POST["salary"].", '".$_POST["email"]."')";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p> Datos Insertados </p>';
	}
	if($_POST["action"] == "fetch_single")
	{
		$query = "
		SELECT * FROM tbl_sample WHERE id = '".$_POST["id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['first_name'] = $row['first_name'];
			$output['salary'] = $row['salary'];
			$output['email'] = $row['email'];
		}
		echo json_encode($output);
	}
	if($_POST["action"] == "update")
	{
		/*$query = "
		SELECT * FROM tbl_sample WHERE id = '".$_POST["hidden_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$out['first_name'] = $row['first_name'];
			$out['salary'] = $row['salary'];
			$out['email'] = $row['email'];
		}
		
		if($out['first_name']==$_POST["first_name"]&&$out['salary']==$_POST["salary"]&&$out['email']==$_POST["email"]){
			echo '<p>No realizo ningun cambio</p>';
		}
		else{*/
			$query = "
			UPDATE tbl_sample 
			SET first_name = '".$_POST["first_name"]."', 
			salary = '".$_POST["salary"]."', 
			email = '".$_POST["email"]."' 
			WHERE id = '".$_POST["hidden_id"]."'
			";
			$statement = $connect->prepare($query);
			$statement->execute();
			echo '<p>Datos actualizados</p>';
		//}
	}
	if($_POST["action"] == "delete")
	{
		$query = "DELETE FROM tbl_sample WHERE id = '".$_POST["id"]."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		echo '<p>Datos Eliminados</p>';
	}
}

?>