
<?php

//fetch.php

include("database_connection.php");

$key=$_POST['key'];
    $query = "
		SELECT * FROM tbl_sample WHERE first_name LIKE '%{$key}%'
		";
//$query = "SELECT * FROM tbl_sample";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$total_row = $statement->rowCount();
$output = '
<table class="table table-striped table-bordered">
	<tr>
	<th>#</th>
		<th>Nombre</th>
		<th>Email	</th>
		<th>Salario	</th>
		<th>Editar</th>
		<th>Eliminar</th>
	</tr>
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
				<button type="button" name="edit" class="btn btn-primary btn-xs edit" id="'.$row["id"].'">Editar</button>
			</td>
			<td width="10%">
				<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'"  val="'.$row["first_name"].' '.$row["email"].'">Eliminar</button>
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
$output .= '</table>';
echo $output;

?>