

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
$output .= '</tbody></table>';
echo $output;
/*}else{

			$pdo=$connect;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 3;

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM tbl_sample ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_contacts = $pdo->query('SELECT COUNT(*) FROM tbl_sample')->fetchColumn();

$total_row = $stmt->rowCount();
$output = '
<div class="content read">
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
	foreach($contacts as $row)
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
echo $output; ?>
<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="fetch.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="fetch.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
<?// }
?>