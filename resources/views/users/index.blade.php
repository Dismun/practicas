@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Gestion de Usuarios</h3></div>
                    <div class="card-body">
                    	@php
                    	 $parametros= array(
                    	 		
                    	 		array('label'=>'Nombre' , 'type' => 'text', 'name' => 'name','value' => ''),
                    	 		array('label' => 'Role' , 'type' => 'select', 'name' => 'rol', 'value' => 0, 'opciones' => $todoslosroles),
                    	 		array('label'=>'E-Mail' , 'type' => 'text', 'name' => 'email','value' => '')
                    	 );
                    	@endphp
                    	@include('compo.buscar')
                    
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <br>
						<table class="table table-striped">
							<thead> 
					        	<tr> 
					        		<th>Nombre usuario</th> 
					        		<th>Roles</th>
					        		<th>E-Mail</th>
					        		{{-- <th>Clave</th> --}}
					        		<th>Acción</th>
					        	</tr> 
							</thead>
							<tbody>
							@foreach($usuarios as $user)
								<tr>
					       	 		<td>{{$user->name}}</td>
					       	 		<td>
					       	 			@foreach($user->roles as $rol)
	                        				{{ $rol->name }},
	                        			@endforeach
	                        		</td>
	                        		<td>{{$user->email}}</td>
	                        		{{-- <td>{{$user->password}}</td> --}}
	                        		<td data-id-user="{{$user->id}}" id="accion">

										<i 
										class="material-icons edit url" 
										style="color:green"  
										data-toggle="modal" 
										data-target="#edit_form" 
										data-name="{{$user->name}}" 
										data-roles="{{$user->roles}}"
										data-mail="{{$user->email}}"
										
										>edit</i>

									    <i 
									    class="material-icons delete url" 
									    style="color:red" 
									    >delete</i>

									</td>
								</tr>
					       	 @endforeach
					       </tbody>
					    </table>
					    <div id="dialog">
	        			</div>
                    </div>
                    
                </div>
            </div>
        </div>



	  <!-- Modal Editar Usuario-->
	  <div class="modal fade" id="edit_form" role="dialog">
	    <div class="modal-dialog">	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title">Editando usuario</h4>
	        </div>
	        <div class="modal-body">
	        	
	        		<div class="input-group">
					      <input id="name_usr" type="text" class="form-control" name="name" placeholder="Nombre" required>
					</div>
	        		<div class="input-group">
					   	<input id="mail_usr" type="email" class="form-control" name="email" placeholder="Email" required>
					</div>
					
					<div id="roles">

					</div>
					<div class="input-group">
						<label>Añadir Rol.....</label>
						<div class="input-group">
							<select class="form-control" name="nuevo_rol" id="nuevo_rol">
								@foreach($todoslosroles as $rol)
									<option value="{{$rol->id}}">{{$rol->description}}</option>
								@endforeach
							</select>
							<i class="material-icons url" id="suma_rol" style="font-size:36px">note_add</i>
						</div>
					</div>      	
	        </div>
	        <div class="modal-footer">
	        	<i class="material-icons url" id="cancela" data-dismiss="modal" style="font-size:48px;color:red">cancel</i>
	        	<i class="material-icons url" id="guarda" data-dismiss="modal" style="font-size:48px;color:green">save</i>
	        	
	        </div>
	        <div id="resultado">
	        </div>
	         
	      </div>
	      
	    </div>
	  </div>

    </div>
@endsection



@section('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
       
    $(document).ready(function() {

            $('.edit').on( "click", function(){            	
            	var user_id = $(this).parent().attr('data-id-user');
            	var nombre = $(this).attr('data-name');
            	var mail = $(this).attr('data-mail');
            	var roles =$(this).attr('data-roles');
            	
            	var texthtml='';
            	$('#name_usr').attr("value",nombre);
            	$('#mail_usr').attr("value",mail);
            	
            	$('#roles').html('Roles......<br> <input type="hidden" value="'+user_id+'" name="user_id" id="user_id">');
            	// console.log(roles);
            	$.each(JSON.parse(roles),function(i,item) {
            		 texthtml='<div class="input-group"> <input id="rol_'+item.id+'" readonly type="text" class="form-control" name="rol_'+item.id+'" placeholder="'+item.description+'"> <i class="material-icons url roles" style="font-size:36px;" data-rol-id="'+item.id+'" id="borra_'+item.id+'">delete</i></div>';

            		$("#roles").append(texthtml);    		
            	});   
            });






            $('#suma_rol').on("click", function(){
            	var rol=$('#nuevo_rol').val();
            	var user_id= $('#user_id').val();
	               	$.ajax({
	                data:  parametros = { 
						_token:'{{ csrf_token() }}',
						user_id : user_id,
						rol_id : rol

	                },
	                url:   'sumarolausuario_ajax',
	                type:  'post',
	                beforeSend: function () {
	                		$("#resultado").attr("class","alert alert-success");
	                       	$("#resultado").html("Procesando, espere por favor...");
	                       	//console.log('paso por envio');
	                },
	                success:  function (response) {
	                		if(response['error'] == 'Ok') {
	                			response['error']='Rol añadido al usuario';
	                			$("#resultado").attr("class","alert alert-success");
	                			texthtml='<div class="input-group"> <input id="rol_'+rol+'" readonly type="text" class="form-control" name="rol_'+rol+'" placeholder="'+response['rol']['description']+'"> <i class="material-icons url roles" style="font-size:36px;" data-rol-id="'+rol+'" id="borra_'+rol+'">delete</i> </div>';

	            					$("#roles").append(texthtml);
	                		}
	                		
	                		else
								$("#resultado").attr("class","alert alert-info");

	                        $("#resultado").html(response['error']);
	                      	//console.log(response);
	                }
	            });
            });






            $('#roles').on( "click",'i.roles', function(){       
            // el i.role captura los eventos de los elementos creados dinamicamente con clase roles     	
            	var user_id = $('#user_id').val();
            	var rol_id = $(this).attr('data-rol-id');
            	// var rol_id = $(this).children("div").children("i").attr('data-rol-id');
            	//console.log(user_id , rol_id);
                $.ajax({
	                data:  parametros = { 
						_token:'{{ csrf_token() }}',
						user_id : user_id,
						rol_id : rol_id

	                },
	                url:   'quitarolausuario_ajax',
	                type:  'post',
	                beforeSend: function () {
	                		$("#resultado").attr("class","alert alert-success");
	                       	$("#resultado").html("Procesando, espere por favor...");
	                       	//console.log('paso por envio');
	                },
	                success:  function (response) {
	                		if(response['error'] == 'Ok') {
	                			response['error']='Rol quitado al usuario';
	                			$("#resultado").attr("class","alert alert-success");
	                			//texthtml='<div class="input-group"> <input id="rol_'+rol+'" readonly type="text" class="form-control" name="rol_'+rol+'" placeholder="'+response['rol']['description']+'"> <i class="material-icons url" style="font-size:36px;">delete</i> </div>';

	            					//$("#roles").append(texthtml);
	            				$("#rol_"+rol_id).remove();
	            				$("#borra_"+rol_id).remove();
	                		}
	                		
	                		else
								$("#resultado").attr("class","alert alert-info");

	                        $("#resultado").html(response['error']);
	                      	//console.log(response);
                	}
            	});       
            });

            $('#guarda').on("click", function(){
            	var user_id = $('#user_id').val();
            	var name = $('#name_usr').val();
            	var mail = $('#mail_usr').val();

            	$.ajax({
	                data:  parametros = { 
						_token:'{{ csrf_token() }}',
						user_id : user_id,
						name : name,
						mail : mail
	                },
	                url:   'actualizausuario_ajax',
	                type:  'post',
	                beforeSend: function () {
	                		$("#resultado").attr("class","alert alert-success");
	                       	$("#resultado").html("Procesando, espere por favor...");
	                       	//console.log('paso por envio');
	                },
	                success:  function (response) {
	                		if(response['error'] == 'Ok') {
	                			response['error']='Usuario Actualizado';
	                			$("#resultado").attr("class","alert alert-success");
	                			//texthtml='<div class="input-group"> <input id="rol_'+rol+'" readonly type="text" class="form-control" name="rol_'+rol+'" placeholder="'+response['rol']['description']+'"> <i class="material-icons url" style="font-size:36px;">delete</i> </div>';

	            					//$("#roles").append(texthtml);
	                		}
	                		
	                		else
								$("#resultado").attr("class","alert alert-info");

	                        $("#resultado").html(response['error']);
	                      	location.reload();
                	}
            	});       
            });
            	
          

            $('#cancela').on("click", function(){
            	location.reload();
            });

           $('.delete').on( "click", function(e){            	
            	var user_id = $(this).parent().attr('data-id-user');
 				// console.log(user_id);  	
          		e.preventDefault();
          		//mostramos el cuadro de dialogo con los botones
          		$('#dialog').fadeIn(800, function() {

            			$(this).html('¿Realmente desea eliminar este Usuario?<br><br>');
            			$(this).append("<input type='button' class='btn btn-info' id='ejecutar_proceso' value='Aceptar'>  ");
            			$(this).append("<input type='button' class='btn btn-success' id='cerrar_dialogo' value='Cancelar'>");
         		 });
        	
        		$('#dialog').on("click", "#ejecutar_proceso", ejecutar);
        		$('#dialog').on("click", "#cerrar_dialogo", cerrar);
        		//función que sirve para cerrar el cuadro de dialogo
        		
        		function cerrar() {
          			$('#dialog').fadeOut();
       			 }
        		//función para ejecutar el proceso
        		function ejecutar() {
            	
	            	$.ajax({
		                data:  parametros = { 
							_token:'{{ csrf_token() }}',
							user_id : user_id,
		                },
		                url:   'borrausuario_ajax',
		                type:  'post',
		                beforeSend: function () {
		                		$("#resultado").attr("class","alert alert-success");
		                       	$("#resultado").html("Procesando, espere por favor...");
		                       	//console.log('paso por envio');
		                },
		                success:  function (response) {
		                		if(response['error'] == 'Ok') {
		                			response['error']='Usuario Eliminado';
		                			$("#resultado").attr("class","alert alert-success");
		                			//texthtml='<div class="input-group"> <input id="rol_'+rol+'" readonly type="text" class="form-control" name="rol_'+rol+'" placeholder="'+response['rol']['description']+'"> <i class="material-icons url" style="font-size:36px;">delete</i> </div>';

		            					//$("#roles").append(texthtml);
		                		}
		                		
		                		else
									$("#resultado").attr("class","alert alert-info");

		                        $("#resultado").html(response['error']);
		                      	location.reload();
	                	}
	            	});
	            }       
	        });

        $('#buscar').on("click", function(){
    		$('#form_buscar').submit();
    	});
    });

    </script>
@endsection

@section('style')
<style type="text/css">
	.url {cursor: pointer;}
</style>
@endsection
