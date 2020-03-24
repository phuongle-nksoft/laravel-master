@if($sidebar != null)
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{url('admin')}}" class="brand-link">
    <img src="{{url('nksoft/img/admin.svg')}}" alt="Control panel" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Control Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        @foreach($sidebar as $item)
          @if($item->link == '#')
            <li class="nav-header">{{trans('nksoft::common.'.$item->title)}}</li>
            @php
              $childs = unserialize($item->child);
            @endphp
            @if(count($childs) > 0)
              @foreach($childs as $child)
              <li class="nav-item">
                <a href="{{url('admin/'.$child['link'])}}" class="nav-link @if(isset($active) && $active == $child['link']) active @endif">
                  <i class="{{$child['icon']}}"></i>
                  <p>{{trans('nksoft::common.'.$child['title'])}}</p>
                </a>
              </li>
              @endforeach
            @endif
          @else
            <li class="nav-item">
              <a href="{{url('admin/'.$item->link)}}" class="nav-link @if(isset($active) && $active == $item->link) active @endif">
                <i class="{{$item->icon}}"></i>
                <p>{{trans('nksoft::common.'.$item->title)}}</p>
              </a>
            </li>
          @endif
        @endforeach
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
@endif
