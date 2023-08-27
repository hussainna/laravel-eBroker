<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between">
                <div class="logo"">
                    <a href="{{ url('home') }}">

                        <img src="{{ url('assets/images/logo/sidebar_logo.png') }}" alt="Logo" srcset="">



                    </a>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                @if (has_permissions('read', 'dashboard'))
                    <li class="sidebar-item">
                        <a href="{{ url('home') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'categories') || has_permissions('read', 'bedroom'))

                    @if (has_permissions('read', 'unit'))
                        <li class="sidebar-item">
                            <a href="{{ url('parameters') }}" class='sidebar-link'>
                                <i class="bi bi-x-diamond"></i>
                                <span>Facilities</span>
                            </a>
                        </li>
                    @endif

                    @if (has_permissions('read', 'categories'))
                        <li class="sidebar-item">
                            <a href="{{ url('categories') }}" class='sidebar-link'>
                                <i class="fas fa-align-justify"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                    @endif



                @endif
                @if (has_permissions('read', 'customer'))
                    <li class="sidebar-item">
                        <a href="{{ url('customer') }}" class='sidebar-link'>
                            <i class="bi bi-person-circle"></i>
                            <span>Customer</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'property'))
                    <li class="sidebar-item">
                        <a href="{{ url('property') }}" class='sidebar-link'>
                            <i class="bi bi-building"></i>
                            <span>Property</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'customer'))
                    <li class="sidebar-item">
                        <a href="{{ url('property-inquiry') }}" class='sidebar-link'>
                            <i class="bi bi-question-square"></i>
                            <span>Property Enquiries</span>
                        </a>
                    </li>
                @endif
                @if (has_permissions('read', 'slider'))
                    <li class="sidebar-item">
                        <a href="{{ url('slider') }}" class='sidebar-link'>
                            <i class="bi bi-sliders"></i>
                            <span>Slider</span>
                        </a>
                    </li>
                @endif


                <li class="sidebar-item">
                    <a href="{{ url('article') }}" class='sidebar-link'>
                        <i class="bi bi-vector-pen"></i>
                        <span>Article</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('advertisement') }}" class='sidebar-link'>
                        <i class="bi bi-badge-ad"></i>
                        <span>Advertisement</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('package') }}" class='sidebar-link'>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                            <path fill="#000" fill-rule="evenodd"
                                d="M1.5 9A1.5 1.5 0 0 1 3 7.5h18A1.5 1.5 0 0 1 22.5 9v11a1.5 1.5 0 0 1-1.5 1.5H3A1.5 1.5 0 0 1 1.5 20V9ZM3 8.5a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h18a.5.5 0 0 0 .5-.5V9a.5.5 0 0 0-.5-.5H3Z"
                                clip-rule="evenodd" />
                            <path fill="#000" fill-rule="evenodd"
                                d="M9.77 10.556a.5.5 0 0 1 .517.034l5 3.5a.5.5 0 0 1 0 .82l-5 3.5A.5.5 0 0 1 9.5 18v-7a.5.5 0 0 1 .27-.444zm.73 1.404v5.08l3.628-2.54-3.628-2.54zM20 6H4V5h16v1zm-2-2.5H6v-1h12v1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Package</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('calculator') }}" class='sidebar-link'>
                        <i class="bi bi-calculator"></i>
                        <span>Calculator</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('payment') }}" class='sidebar-link'>
                        <i class="bi bi-cash"></i>
                        <span>Payment</span>
                    </a>
                </li>
                @if (has_permissions('read', 'notification'))
                    <li class="sidebar-item">
                        <a href="{{ url('notification') }}" class='sidebar-link'>
                            <i class="bi bi-bell"></i>
                            <span>Notification</span>
                        </a>
                    </li>
                @endif

                @if (has_permissions('read', 'users_accounts') ||
                        has_permissions('read', 'about_us') ||
                        has_permissions('read', 'privacy_policy') ||
                        has_permissions('read', 'terms_condition'))
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="submenu" style="padding-left: 0rem">
                            @if (has_permissions('read', 'users_accounts'))
                                <li class="submenu-item">
                                    <a href="{{ url('users') }}">Users Accounts</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'about_us'))
                                <li class="submenu-item">
                                    <a href="{{ url('about-us') }}">About Us</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'privacy_policy'))
                                <li class="submenu-item">
                                    <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'terms_condition'))
                                <li class="submenu-item">
                                    <a href="{{ url('terms-conditions') }}">Terms & Condition</a>
                                </li>
                            @endif
                            @if (has_permissions('read', 'system_settings'))
                                <li class="submenu-item">
                                    <a href="{{ url('system-settings') }}">System Settings</a>
                                </li>
                            @endif
                            <li class="submenu-item">
                                <a href="{{ url('firebase_settings') }}">Firebase Settings</a>
                            </li>

                            <li class="submenu-item">
                                <a href="{{ url('language') }}">Languages</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ url('system_version') }}" class='sidebar-link'>
                            <i class="fas fa-cloud-download-alt"></i>
                            <span>System Update</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
