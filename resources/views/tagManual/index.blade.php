@extends('layouts.ProjectGeneral')

@section('title', 'Tutorial')

@section('content')
	<div class="row" id="crud">
		<br><br><br>
		<button class="btn btn-info" v-on:click="create">New Tag</button>
		<br><br>
			<ul id="sortable">
				<li v-for="tag in datos" v-bind:id="tag.id" class="elemento">
					<a href class="btn btn-default" v-on:click.prevent="manual(tag.id)"><span class="badge">@{{tag.id}}</span> @{{tag.tittle}}</a>
					<a href class="btn btn-success" v-on:click.prevent="edit(tag.id)">edit</a>
					<a href class="btn btn-danger" v-on:click.prevent="destroy(tag.id)">X</a>
				</li>
			</ul>
		<div class="modal fade" id="edit" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                <center><h4 class="modal-title">@{{tittleModal}}</h4></center>
		            </div>
		            <div class="modal-body">
		            	<label>@{{tittleModal}}</label>
		                <input type="text" v-model="dataTag.tittle" class="form-control">
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button class="btn btn-primary" v-if="crear" v-on:click="store">Save</button>
		                <button class="btn btn-success" v-else  v-on:click="update">Save</button>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="modal fade" id="destroy" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                <center><h4 class="modal-title">Eliminar Tag</h4></center>
		            </div>
		            <div class="modal-body">
		            	<p>Desea Eliminar el Tag "@{{dataTag.tittle}}"</p>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		                <button class="btn btn-primary" v-on:click="confirmarDestroy">Delete</button>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
@endsection


@section('Scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
	ul>li{
		list-style: none;
		margin: 5px;
	}
</style>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#sortable" ).sortable({
    	stop:function(){
    		var order = [];
			$('.elemento').each(function() {
			    order.push($(this).attr('id'));
			});
			$.post('/manual-tags/order',{order: order}, function(data){
			});
    	}
    });
  } );
</script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript">
	new Vue({
            el: '#crud',
            created: function(){
                this.getDatos();
            },
            data:{
                datos: [],
                dataTag: [],
                tittleModal: null,
                crear: null,
            },
            methods:{
                getDatos: function(){
                    var ruta = '{{route("manual-tags.show")}}';
                    axios.get(ruta)
                    .then(response => {
                        this.datos = response.data;
                    })
                    .catch(function (error) {
                       console.log('Error: ' + error);
                    }); 
                },
                manual:function(id)
                {
                	window.open('/manual?tagManual='+id, '_blank');
                },
                edit:function(id)
                {
                	axios.get('/manual-tags/'+id+'/edit')
                	.then(response =>{
                		this.dataTag = response.data;
                		this.tittleModal = 'Editar Tag';
                		this.crear = false;
                		$('#edit').modal();
                	})
                	.catch(function (error){
                		console.log('Error: ' + error);
                	});
                },
                update:function(){
                	var tittle =  this.dataTag.tittle;
                	axios.patch('/manual-tags/'+this.dataTag.id, {tittle})
                	.then(response => {
                		//console.log(response.data);
                		this.getDatos();
                		$('#edit').modal('hide');
                		toastr.success('el Tag ha sido actualizado');
                	})
                	.catch(function (error){
                		console.log('Error: ' + error);
                	});
                },
                create:function()
                {
                	this.tittleModal = 'Crear Tag';
                	this.crear = true;
                	this.dataTag = [];
                	$('#edit').modal();
                },
                store:function()
                {
                	var tittle =  this.dataTag.tittle;
                	axios.post('/manual-tags', {tittle})
                	.then(response => {
                		this.getDatos();
                		$('#edit').modal('hide');
                		toastr.success('el Tag ha sido creado');
                	})
                	.catch(function (error){
                		console.log('Error: ' + error);
                	});
                },
                destroy:function(id)
                {
                	axios.get('/manual-tags/'+id+'/edit')
                	.then(response =>{
                		this.dataTag = response.data;
                		$('#destroy').modal();
                	})
                	.catch(function (error){
                		console.log('Error: ' + error);
                	});
                },
                confirmarDestroy:function()
                {
                	id = this.dataTag.id;
                	axios.delete('/manual-tags/'+id)
                	.then(response => { 
                		this.getDatos();
                		$('#destroy').modal('hide');
                		toastr.error('el Tag ha sido eliminado');
                	});
                }
            }
        });
</script>


@endsection