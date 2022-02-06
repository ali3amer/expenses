<aside class="main-sidebar">

    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">
            @if(Auth::user()->power == 'developer')
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
                    <router-link to="/tables"><i class="fa fa-table"></i><span>البنود</span></router-link>
                </li>

                <li>
                    <router-link to="/fields"><i class="fa fa-tasks"></i><span>الحقول</span></router-link>
                </li>


                <li>
                    <router-link to="/supplies"><i class="fa fa-apple"></i><span>العينيات</span></router-link>
                </li>
                <li>
                    <router-link to="/mangePrograms"><i class="fa fa-file-text"></i><span>إدارة البرامج</span>
                    </router-link>
                </li>
            @endif

            <li>
                <router-link to="/clients"><i class="fa fa-users"></i><span>المستفيدين</span></router-link>
            </li>

            <li>
                <router-link to="/programs"><i class="fa fa-file-text"></i><span>البرامج</span></router-link>
            </li>

            <li>
                <router-link to="/reports"><i class="fa fa-file-text"></i><span>التقارير</span></router-link>
            </li>

                <li>
                    <router-link to="/lists"><i class="fa fa-file-text"></i><span>بنشيتات الصرف</span></router-link>
                </li>


        </ul>

    </section>

</aside>

