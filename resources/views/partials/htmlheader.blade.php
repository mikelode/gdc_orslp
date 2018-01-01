<head>
    <meta charset="UTF-8">
    <title> @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('/css/bootstrap-3.3.5-dist/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('/css/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('/css/ionicons-2.0.1/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset('/css/skins/skin-blue.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom style -->
    <link href="{{ asset('/css/symva.css') }}" rel="stylesheet" type="text/css">
    <!-- iCheck -->
    <link href="{{ asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link href="{{ asset('/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="{{ asset('/plugins/summernote-master/dist/summernote.css') }}" rel="stylesheet" type="text/css" />
    <!-- CSS Personalizado -->
    <link href="{{ asset('/css/tramapp.css') }}" rel="stylesheet" type="text/css" />
    <!-- datatables -->
    <!--<link href="{{ asset('/plugins/DataTables/datatables.min.css') }}" rel="stylesheet" type="text/css">-->
    <link href="{{ asset('/plugins/DataTables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/plugins/DataTables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap datetimepicker -->
    <link href="{{ asset('/plugins/Bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap X-Editable -->
    <link href="{{ asset('/plugins/bootstrap3-editable/bootstrap3-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css">
    <!-- Morris --> 
    <link href="{{ asset('/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
    <script src="{{ asset('/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('/js/respond.min.js') }}"></script>
    <!--<![endif]-->
    <script src="{{ asset('/js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('/js/tramapp.js') }}"></script>
    
</head>
