@php
$user = Auth::user();
@endphp
@if($sidebar != null)
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('admin')}}" class="brand-link">
        <img src="{{url('nksoft/img/admin.svg')}}" alt="{{__('nksoft::common.Control Panel')}}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{__('nksoft::common.Control Panel')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('admin') }}" class="nav-link">
                      <i class="nav-icon far fa-bell"></i>
                      <p>
                        Thông báo
                        <span class="badge badge-danger right">{{ $newOrder }}</span>
                      </p>
                    </a>
                </li>
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                @foreach($sidebar as $item)
                    @php
                        $childs = unserialize($item->child);
                        $roleIds = json_decode($item->roles_id);
                    @endphp
                    @if($item->link == '#')
                        @if(in_array($user->role_id, $roleIds))
                            <li class="nav-header">{{trans('nksoft::common.'.strtolower($item->title))}}</li>
                            @if(count($childs) > 0)
                                @foreach($childs as $child)
                                    @php
                                        $childRoleIds = json_decode($child['roles_id']);
                                    @endphp
                                    @if(in_array($user->role_id, $childRoleIds))
                                        <li class="nav-item">
                                            <a href="{{url('admin/'.$child['link'])}}" class="nav-link @if(isset($active) && $active == $child['link']) active @endif">
                                                <i class="{{$child['icon']}}"></i>
                                                <p>{{trans('nksoft::common.'.strtolower($child['title']))}}</p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    @else
                        @if(in_array($user->role_id, $roleIds))
                        <li class="nav-item">
                            <a href="{{url('admin/'.$item->link)}}" class="nav-link @if(isset($active) && $active == $item->link) active @endif">
                                <i class="{{$item->icon}}"></i>
                                <p>{{trans('nksoft::common.'.strtolower($item->title))}}</p>
                            </a>
                        </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
@endif
