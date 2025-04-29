@extends('index')

@section('content')
    <div class="col-md-12">
        <div class="container">
            <h1>List editors</h1>
            <div class="col-md-12">
                <form action="{{url('/users/store')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label for="name" class="text-danger">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="熊田一聡（一般社員）">
                    </div>
                    <div class="form-group">
                        <label for="email" class="text-danger">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label for="password" class="text-danger">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="********">
                    </div>
                    <button class="btn btn-success">保存</button>
                </form>
            </div>
        </div>
    </div>
@endsection