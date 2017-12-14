<!DOCTYPE html>

<html>

@include('partials.htmlheader')

<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
    @include('partials.mainheader')

    @include('partials.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('main-content')
    </div><!-- /.content-wrapper -->

    @include('partials.controlsidebar')

</div><!-- ./wrapper -->
@include('partials.scripts')
@include('partials.footer')

</body>
</html>