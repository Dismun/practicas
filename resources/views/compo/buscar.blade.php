	@php
		$ncol=count($parametros);
		$col=10;
		if($ncol==2)
		  	$col=5;
		if($ncol==3)
		  	$col=3;
		if($ncol>3)
		  	$col=4; 


	@endphp
<form method="GET" id="form_buscar">

<div class="row" style="background-color:Azure;">
	
		
		@foreach($parametros as $param)
			<div class="col-sm-{{$col}} ">
				@if($param['type']=='select')
					<select id="{{ $param['name'] }}" class="form-control" name="{{ $param['name'] }}" placeholder="{{ $param['label'] }}">
						<option value="0">{{ $param['label'] }} : Todos</option>
						@foreach($param['opciones'] as $op)
						 <option value="{{ $op->id }}" > {{ $op->name }} </option>
						@endforeach
					</select>

				@else
					<input id="{{ $param['name'] }}" type="{{ $param['type'] }}" class="form-control" name="{{ $param['name'] }}" placeholder="{{ $param['label'] }}">
				@endif
			</div>
		@endforeach
		
	<div class="col-sm-2" ><button class="btn btn-success form-control" id="buscar" >Buscar</button>
	</div>
	
</div>

</form>

