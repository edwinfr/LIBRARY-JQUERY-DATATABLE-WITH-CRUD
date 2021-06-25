
<?php
include("database_connection.php");?>
<html>  
  <head>  
        <title>PHP Ajax Crud usando JQuery UI Dialog</title>  
		<link rel="stylesheet" href="jquery-ui.css">
        <link rel="stylesheet" href="bootstrap.min.css" />
		<script src="jquery.min.js"></script>
		  
		<script src="jquery-ui.js"></script>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center">PHP Ajax Crud con JQuery UI Dialog</a></h3><br />
			<br />
			<div align="right" style="margin-bottom:5px;">
			<button type="button" name="add" id="add" class="btn btn-success btn-xs">Agregar Nueva Persona</button>
			</div>
<?php
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
		<a href="index.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="index.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
	</div>

			<div class="table-responsive" id="user_data">
				
			</div>
			<br />
		</div>
		
		<div id="user_dialog" title="Agregar Nueva persona">
			<form method="post" id="user_form">
				<div class="form-group">
					<label>Ingrese su Nombre</label>
					<input type="text" onkeypress="return soloLetras(event);" name="first_name" id="first_name" class="form-control" />
					<span id="error_first_name" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Ingresar email</label>
					<input type="text"  name="email" id="email" class="form-control" />
					<span id="error_email" class="text-danger"></span>
				</div>
				<div class="form-group">
					<label>Ingrese su salario</label>
					<input type="text" onkeypress="return soloNumeros(event);" name="salary" id="salary" class="form-control" />
					<span id="error_salary" class="text-danger"></span>
				</div>
               
				<div class="form-group">
					<input type="hidden" name="action" id="action" value="insert" />
					<input type="hidden" name="hidden_id" id="hidden_id" />
					<input type="submit" name="form_action" id="form_action" class="btn btn-info" value="Guardar" />
					<input type="button" name="cancel" id="cancel" class="btn btn-info" value="Cancelar" />
				</div>
			</form>
		</div>
		
		<div id="action_alert" title="Accion">
		<input type="button" name="_aceptar" id="aceptar" class="btn btn-info" value="aceptar" />
		</div>

		
		
		<div id="delete_confirmation" title="Confirmacion">
		<p>Are you sure you want to Delete this data?</p>
		</div>
		
    </body>  
</html>  



<script src="pagination.js" type="text/javascript"></script>
<script>  
$(document).ready(function(){  

	/*load_data();
    
	function load_data()
	{
		location.href = "index.php";
		/*$.ajax({
			url:"fetch.php",
			method:"POST",
			success:function(data)
			{
				$this.html();
				$("#user_dete").html(dete);
			}
		});
	}*/

	

   
/*/
    $('#user_data').pagination({
        dataSource: [1, 2, 3, 4, 5, 6, 7, ... , 195],
        callback: function(data, pagination) {
            // template method of yourself
            var html = template(data);
            dataContainer.html(html);
        }
    })
*/



	
	$("#user_dialog").dialog({
		autoOpen:false,
		width:400
	});
	
	$('#add').click(function(){
		$('#user_dialog').attr('title', 'Add Data');
		$('#action').val('insert');
		$('#form_action').val('Insertar');
		$('#user_form')[0].reset();
		$('#form_action').attr('disabled', false);
		$("#user_dialog").dialog('open');
	});

	$('#cancel').click(function(){
		$("#user_dialog").dialog('close');
	});
	//controlar y validar formulario
	$('#user_form').on('submit', function(event){
		event.preventDefault();
		var error_first_name = '';
		var error_last_name = '';
		var error_email= '';

		if($('#email').val() == '')
		{
			error_email = 'email es requerido';
			$('#error_email').text(error_email);
			$('#email').css('border-color', '#cc0000');
		}
		else if(!validar_email($('#email').val())){
            error_email = 'email no es valido';
			$('#error_email').text(error_email);
			$('#email').css('border-color', '#cc0000');
		}
		else
		{
			error_email = '';
			$('#error_email').text(error_email);
			$('#email').css('border-color', '');
		}


		if($('#first_name').val() == '')
		{
			error_first_name = 'Nombre es requerido';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '#cc0000');
		}
		else
		{
			error_first_name = '';
			$('#error_first_name').text(error_first_name);
			$('#first_name').css('border-color', '');
		}


		if($('#salary').val() == '')
		{
			error_salary = 'Salario es requerido';
			$('#error_salary').text(error_salary);
			$('#salary').css('border-color', '#cc0000');
		}
		else if($('#salary').val()==0){
			error_salary = 'Salario debe ser mayor a cero';
			$('#error_salary').text(error_salary);
			$('#salary').css('border-color', '#cc0000');
		}else{
			error_salary = '';
			$('#error_salary').text(error_salary);
			$('#salary').css('border-color', '');
		}
		
		if(error_first_name != '' || error_salary != ''|| error_email != '')
		{
			return false;
		}
		else
		{
			$('#form_action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:form_data,
				success:function(data)
				{
					$('#user_dialog').dialog('close');
					$('#action_alert').html(data);
					$('#action_alert').dialog('open');
					//load_data();
					//location.href = "index.php";
					$('#form_action').attr('disabled', false);
				}
			});
		}
		
	});
	
	$('#action_alert').dialog({
		autoOpen:false,
		buttons:{
			Ok : function(){
				$(this).dialog('close');
				location.href = "index.php";
			}
		}
	});
	
	$(document).on('click', '.edit', function(){
		var id = $(this).attr('id');
		var action = 'fetch_single';
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{id:id, action:action},
			dataType:"json",
			success:function(data)
			{
				$('#first_name').val(data.first_name);
				$('#salary').val(data.salary);
				$('#email').val(data.email);
				$('#user_dialog').attr('title', 'Edit Data');
				$('#action').val('update');
				$('#hidden_id').val(id);
				$('#form_action').val('Modificar');
				$('#user_dialog').dialog('open');
			}
		});
	});
	
	$('#delete_confirmation').dialog({
		autoOpen:false,
		modal: true,
		buttons:{
			Ok : function(){
				var id = $(this).data('id');
				var action = 'delete';
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{id:id, action:action},
					success:function(data)
					{
						$('#delete_confirmation').dialog('close');
						$('#action_alert').html(data);
						$('#action_alert').dialog('open');
						//load_data();
						//location.href = "index.php";
					}
				});
			},
			Cancelar : function(){
				$(this).dialog('close');
			}
		}	
	});
	
	$(document).on('click', '.delete', function(){
		var id = $(this).attr("id");
		var val = $(this).attr("val");
		$('#delete_confirmation').text('Desea eliminar a '+val);
		$('#delete_confirmation').data('id', id).dialog('open');
	});


	
});

function validar_email(email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	return emailReg.test(email);
}

function soloLetras(e){
	var key=e.keyCode || e.which;
	var tecla=String.fromCharCode(key).toLowerCase();
	var letras=" áéíóúabcdefghijklmnopqrstuvwxyz";

	var especiales=[8,37,39,46];

	var tecla_especial=false;

	for (var i in especiales){
		if(key == especiales[i]){
			tecla_especial=false;
			break;
		}
	}
	if(letras.indexOf(tecla) == -1 && !tecla_especial)
		return false;
}

function soloNumeros(e){
	var key=e.keyCode || e.which;
	var tecla=String.fromCharCode(key).toLowerCase();
	var numeros="0123456789";

	var especiales=[46];

	var tecla_especial=false;

	for (var i in especiales){
		if(key == especiales[i]){
			tecla_especial=true;
			break;
		}
	}
	if(numeros.indexOf(tecla) == -1 && !tecla_especial)
		return false;
}


</script>

