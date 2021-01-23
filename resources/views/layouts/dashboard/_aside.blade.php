<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('dashboard_files/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-th"></i><span>@lang('site.dashboard')</span></a>
            </li>

            <li>
                <router-link to="/users"><i class="fa fa-users"></i><span>المستخدمين</span></router-link>
            </li>

            <li>
                <router-link to="/states"><i class="fa fa-map"></i><span>الولايات</span></router-link>
            </li>

            <li>
                <router-link to="/towns"><i class="fa fa-university"></i><span>المحليات</span></router-link>
            </li>

            <li>
                <router-link to="/units"><i class="fa fa-university"></i><span>الوحدات الإداريه</span></router-link>
            </li>

            <li>
                <router-link to="/zones"><i class="fa fa-road"></i><span>المناطق</span></router-link>
            </li>

            <li>
                <router-link to="/clients"><i class="fa fa-road"></i><span>العملاء</span></router-link>
            </li>


        </ul>

    </section>

</aside>

