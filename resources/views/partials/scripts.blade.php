<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- Select2 -->
<script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('/plugins/summernote-master/dist/summernote.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap tree-menu -->
<script src="{{ asset('/js/bootstrap-tree.js') }}" type="text/javascript"></script>
<!-- Bootbox Alert -->
<script src="{{ asset('/js/bootbox.min.js') }}" type="text/javascript"></script>
<!-- JQuery UI -->
<script src="{{ asset('/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<!-- DataTables -->
<!--<script src="{{ asset('/plugins/DataTables/datatables.min.js') }}" type="text/javascript"></script>-->
<script src="{{ asset('/plugins/DataTables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/buttons.flash.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/jszip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/pdfmake.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/vfs_fonts.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/DataTables/buttons.print.min.js') }}" type="text/javascript"></script>
<!-- Momment para el Datetimepicker -->
<script src="{{ asset('/plugins/Moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/Moment/locale/es.js') }}" type="text/javascript"></script>
<!-- Bootstrap DateTimePicker -->
<script src="{{ asset('/plugins/Bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<!-- Typehead Bootstrap -->
<script src="{{  asset('/plugins/bootstrap-ajax-typeahead/js/bootstrap-typeahead.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap X-Editable -->
<script src="{{  asset('/plugins/bootstrap3-editable/bootstrap3-editable/js/bootstrap-editable.min.js') }}" type="text/javascript"></script>
<!-- Morris -->
<script src="{{  asset('/plugins/morris/raphael.min.js') }}" type="text/javascript"></script>
<script src="{{  asset('/plugins/morris/morris.min.js') }}" type="text/javascript"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->

<!-- AdminLTE App -->
<script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    var screen = $('#loading-screen');
	configureLoadingScreen(screen);

	function configureLoadingScreen(screen){
		$(document)
			.ajaxStart(function() {
				screen.fadeIn();
			})
			.ajaxStop(function() {
				screen.fadeOut();
			});
	}

    bootbox.addLocale('spanish',{
        OK : 'Aceptar',
        CANCEL : 'Cancelar',
        CONFIRM : 'Confirmar'
    });

    bootbox.setLocale('spanish');

    $('#dtpPeriodo').datetimepicker({
        format: "YYYY",
        viewMode: 'years'
    }).on('dp.change', function(e) {

      var periodo = e.date._d.getFullYear();
      var oldPeriodo = e.date._d.getFullYear();
      
      $.get('period/change', {'year':periodo, 'oldyear': oldPeriodo}, function(response){
        location.reload();
      });

    });

    var area_chart = Morris.Area({
  	  element: 'registro-chart',
  	  data: [<?php echo $data ?>],
  	  xkey: 'fecha',
  	  ykeys: ['value'],
  	  labels: ['Cantidad'],
      fillOpacity: 0.5,
  	});

</script>