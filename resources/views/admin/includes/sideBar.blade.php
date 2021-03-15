
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="{{ url(\Settings::get('application_logo')) }}"  height="60px" width="60px" style="border-radius:20%!important"/>
                    <ul class="dropdown-menu animated fadeInLeft m-t-xs">
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="rounded-circle" src="{{ url(\Settings::get('favicon_logo')) }}"  height="60px" width="60px" style="border-radius:20%!important"/>
                </div>
            </li>
            <li class="@if(Request::segment('2') == 'dashboard') active @endif">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">
                        Dashboard
                    </span>
                </a>
            </li>
            <li class="@if(Request::segment('2') == 'user') active @endif">
                <a href="{{ route('admin.index') }}">
                    <i class="fa fa-users"></i>
                    <span class="nav-label">Users </span>
                </a>
            </li>


            <li class=" @if(Request::segment('2') == 'cms') active @endif">
                <a href="{{ route('admin.cms.index') }}">
                    <i class="fa fa-edit"></i>
                    <span class="nav-label">
                        CMS Pages
                    </span>
                </a>
            </li>
           
            <li class="@if(Request::segment('2') == 'settings') active @endif">
                <a href="{{ url('admin/settings') }}">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-label">
                        Site Settings
                    </span>
                </a>
            </li>
        </ul>
    </div>
</nav>