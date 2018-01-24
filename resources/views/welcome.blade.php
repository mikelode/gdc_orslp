<html>
	<head>
		<title>VILCABAMBA</title>
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
		<style type="text/css">
			.content{
				
			}
		</style>
		<script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
	</head>
	<body>
		<div class="content">
			<form id="frmMultiFiles" enctype="multipart/form-data">
				{{ csrf_field() }}
				<label>Fecha</label>
				<input type="date" name="ndateFiles">
				<br>
				<label>Seleccione la carpeta</label>
				<input type="file" name="nctrlFiles[]" id="ctrlFiles" webkitdirectory directory multiple/>
				<progress class="form-control" value="0"></progress>
				<br>
				<button id="verFolder">Registrar Todos</button>
				<a href="{{ url('/') }}" >Volver al Sistema</a>
				<br>
			</form>
		</div>
		<div id="result"></div>
	</body>

<script>
$(document).ready(function() {

	$('#verFolder').click(function(ev) {
		ev.preventDefault();
		var folder = $('#ctrlFiles');
		var form = $('#frmMultiFiles')[0];
		var frmData = new FormData(form);

		$.ajax({
			url: 'read/files',
			type: 'post',
			data: frmData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result){
				$('#result').html(result);
			},
			xhr: function(){
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){
	                myXhr.upload.addEventListener('progress', function(ev){
	                    if(ev.lengthComputable){
	                        $('progress').attr({
	                            value: ev.loaded,
	                            max: ev.total,
	                        });
	                    }
	                }, false);
	            }
	            return myXhr;
	        },
		})
		.fail(function() {
			console.log("error");
		});
	});

});
</script>

</html>
