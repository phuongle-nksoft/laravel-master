@php
    $listHistories = $histories->groupBy('type');
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{url('/')}}" class="nav-link">Home</a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @if (Auth::user()->role_id == 1)
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-danger navbar-badge">{{ $histories->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">{{ $histories->count() }} {{trans('nksoft::common.Notifications')}}</span>
            @foreach ($listHistories as $type => $item)
            <div class="dropdown-divider"></div>
            <a href="{{ url('admin/'.$type) }}" class="dropdown-item bg-danger">
                <i class="fas fa-trash-alt mr-2"></i> {{ $item->count() }} {{ trans('nksoft::common.'.$type) }}
            </a>
            @endforeach
        </div>
    </li>
    @endif

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-users-cog"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{ url('admin/users/'.Auth::user()->id.'/edit') }}" class="dropdown-item">
            <i class="fas fa-key"></i> {{trans('nksoft::common.Profile')}}
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ url('admin/logout') }}" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> {{trans('nksoft::common.logout')}}
          </a>
        </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
