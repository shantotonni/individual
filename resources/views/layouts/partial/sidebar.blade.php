<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Main</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect"><i class="ti-home"></i><span>Dashboard</span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="ti-email"></i>
                        <span>
                            KPI <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                        </span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('kpiData.list') }}">KPI Info</a></li>
                        <li><a href="{{ route('kpi.entry.list') }}">KPI Data Entry</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('individual.dashboard') }}" class="waves-effect"><i class="ti-calendar"></i><span> Individual Dashboard</span></a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="waves-effect" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                        <i style="color: red" class="mdi mdi-logout"></i>
                        <span> Logout </span>
                    </a>
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
