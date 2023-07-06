<!DOCTYPE html>
<html lang="en">
@include('layouts.partial.top_head')
<body>
<!-- Begin page -->
<div id="wrapper">
    @include('layouts.partial.top_bar')

    @include('layouts.partial.sidebar')

    <div class="content-page">
       @yield('content')
        <!-- content -->
        <footer class="footer">
            © 2023 MIS ACI
        </footer>
    </div>
</div>
@include('layouts.partial.footer')
</body>

</html>
