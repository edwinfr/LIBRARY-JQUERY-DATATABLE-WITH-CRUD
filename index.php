
<html>  
    <head>  
        <title>PHP Ajax Crud usando JQuery UI Dialog</title>  
		<link rel="stylesheet" href="jquery-ui.css">
        <link rel="stylesheet" href="bootstrap.min.css" />
		<script src="jquery.min.js"></script>  
		<script src="jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center">PHP Ajax Crud con JQuery UI Dialog</a></h3><br />
			<br />
			<div align="right" style="margin-bottom:5px;">
			<button type="button" name="add" id="add" class="btn btn-success btn-xs">Agregar Nueva Persona</button>
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
				</div>
			</form>
		</div>
		
		<div id="action_alert" title="Action">
			
		</div>
		
		<div id="delete_confirmation" title="Confirmation">
		<p>Are you sure you want to Delete this data?</p>
		</div>
		
    </body>  
</html>  




<script>  
$(document).ready(function(){  

	load_data();
    
	function load_data()
	{
		$.ajax({
			url:"fetch.php",
			method:"POST",
			success:function(data)
			{
				$('#user_data').html(data);
			}
		});
	}
	
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
					load_data();
					$('#form_action').attr('disabled', false);
				}
			});
		}
		
	});
	
	$('#action_alert').dialog({
		autoOpen:false
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
						load_data();
					}
				});
			},
			Cancel : function(){
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

