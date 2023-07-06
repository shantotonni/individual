<!-- END wrapper --><!-- jQuery  -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/waves.min.js') }}"></script>
<!--Chartist Chart-->
<script src="{{ asset('assets/plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- peity JS -->
<script src="{{ asset('assets/plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<!-- end demo js-->
{!! Toastr::message() !!}
@stack('js')
<script src="{{ asset('assets/pages/dashboard.js') }}"></script>
<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>
