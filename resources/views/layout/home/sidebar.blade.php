 <!-- menu profile quick info -->
 <div class="profile clearfix">
    <div class="profile_pic">
      <img src="{{ asset('images/user.png') }}" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
      <span>@lang('layout.welcome')</span>
      <h2>{{ substr_replace(Auth::user()->email, '
        ', 17, 0); }}</h2>
    </div>
  </div>
  <!-- /menu profile quick info -->

  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>General</h3>
      <ul class="nav side-menu">
        <li><a><i class="fa fa-home"></i> @lang('layout.home') <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="{{ route('home') }}">@lang('layout.dashboard')</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i> @lang('layout.categories') <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="{{ route('categories.list') }}">@lang('layout.lists')</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-clone"></i>@lang('layout.courses') <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="{{ route('courses.list') }}">@lang('layout.lists')</a></li>
          </ul>
        </li>

        <li><a><i class="fa fa-sitemap"></i>{{ __('layout.lesson') }}<span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
              <li>
                <a href="{{ route('lesson.list') }}">{{ __('layout.lists') }}</a>
              </li>
          </ul>
        </li>

      </ul>
    </div>
    

  </div>
  <!-- /sidebar menu -->

  <!-- /menu footer buttons -->
  <div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
      <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
      <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
      <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <form action="{{ route('logout') }}" method="post">
      @csrf
      <button type="submit" class="btn btn-link glyphicon glyphicon-off" aria-hidden="true"
      data-toggle="tooltip" data-placement="top" title="Logout" ></button>
    </form>
  </div>
  <!-- /menu footer buttons -->
</div>
</div>