

<html>  
  <head>  
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head> 

<?php
//include 'index.php';
//fetch.php

include("database_connection.php");



$key=$_POST['key'];
//if($key!=""){
$key=$_POST['key'];
//    $query = "SELECT * FROM tbl_sample WHERE first_name LIKE '%{$key}%'";
$query = "SELECT * FROM tbl_sample";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
$output = '
<table  id="teble" class="display" style="width:100%">
	<thead>
	<tr>
		<th>#</th>
		<th>Nombre</th>
		<th>Email	</th>
		<th>Salario	</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>
	</thead>
	<tbody>
';
if($total_row > 0)
{
	foreach($result as $row)
	{
		$output .= '
		<tr>
		   <td width="10%">'.$row["id"].'</td>
			<td width="40%">'.$row["first_name"].'</td>
			<td width="40%">'.$row["email"].'</td>
			<td width="40%" style=" text-align: left;" >$ '.$row["salary"].'</td>
			<td width="10%">
				<button type="button" name="edit" class="btn btn-primary btn-xs edit" id="'.$row["id"].'"><h4>Editar</h4></button>
			</td>
			<td width="10%">
				<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'"  val="'.$row["first_name"].' '.$row["email"].'"><h4>Eliminar</h4></button>
			</td>
		</tr>
		
		';
	}
}
else
{
	$output .= '
	<tr>
		<td colspan="4" align="center">Datos no encontrados</td>
	</tr>
	';
}
$output .= '</tbody></table>';
echo $output;
