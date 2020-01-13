<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->

    <div class="logo-outer">

      <a href="{{ route('admin-dashboard') }}"><img src="{{ asset('img/final_white_logo.png') }}" alt="" class=""
           style="opacity: .8"></a>

      <div class="clearfix"></div>

    </div>

    

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image"> 
          
          @if( !empty( Auth::user()->profile->profile_pic ) )
          <img src="{{ asset('profile_photos') }}/{{ Auth::user()->profile->profile_pic }}" class="img-circle elevation-2" alt="User Image">
          @else
          <img src="{{ asset('img/demo-user.png') }}" class="img-circle elevation-2" alt="User Image">
          @endif

        </div>
        <div class="info" style="margin: 0 auto;">
          <a href="{{ route('admin-dashboard') }}" class="d-block">Welcome!! {{ ucwords(Auth::user()->name) }}</a>
        </div>
      </div>-->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!--<li class="nav-item has-treeview menu-close">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin-user') }}" class="nav-link">
                  <i class="far fas fa-chevron-right"></i>
                  <p>All Users</p>
                </a>
              </li>

            </ul>
          </li>-->

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-circle"></i>
              <p>
                My Account
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="fas fa-ellipsis-v"></i>
                  <p> Welcome, {{ ucwords( Auth::user()->name ) }}</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin-user') }}" class="nav-link {{ (request()->is('admin/user*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview {{ (request()->is('admin/blog*')) ? 'menu-open' : 'menu-close' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Blog
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('articles') }}" class="nav-link {{ (request()->is('admin/blog/article*')) ? 'active' : '' }}">
                  <i class="fas fa-ellipsis-v"></i>
                  <p>Articles</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('blog-categories') }}" class="nav-link {{ (request()->is('admin/blog/category*')) ? 'active' : '' }}">
                  <i class="fas fa-ellipsis-v"></i>
                  <p>Categories</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('techniques') }}" class="nav-link {{ (request()->is('admin/techniques*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Techniques
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin-services') }}" class="nav-link {{ (request()->is('admin/services*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Services
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('issues') }}" class="nav-link {{ (request()->is('admin/issues*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>
                Issues
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('insurance') }}" class="nav-link {{ (request()->is('admin/insurance*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Insurance
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview {{ (request()->is('admin/newsletter*')) ? 'menu-open' : 'menu-close' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
                Newsletter
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('subs-lists') }}" class="nav-link {{ (request()->is('admin/newsletter/list*')) ? 'active' : '' }}">
                  <i class="fas fa-ellipsis-v"></i>
                  <p>Lists</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('subs') }}" class="nav-link {{ (request()->is('admin/newsletter/subscribers*')) ? 'active' : '' }}">
                  <i class="fas fa-ellipsis-v"></i>
                  <p>Subscribers</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin-services') }}" class="nav-link {{ (request()->is('admin/settings*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin-services') }}" class="nav-link {{ (request()->is('admin/apperance*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-magic"></i>
              <p>
                Apperance
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin-services') }}" class="nav-link {{ (request()->is('admin/reports*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Reports
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href=""
           onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                         <i class="nav-icon fas fa-power-off"></i>
                         <p>
                             {{ __('Logout') }}
                         </p>

        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>