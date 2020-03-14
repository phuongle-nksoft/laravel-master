<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{$title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach($breadcrumb as $index => $item)
                    @if($index == (count($breadcrumb) - 1))
                    <li class="breadcrumb-item active">{{$item['title']}}</li>
                    @else
                    <li class="breadcrumb-item"><a href="{{$item['link']}}">{{$item['title']}}</a></li>
                    @endif
                    @endforeach
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->