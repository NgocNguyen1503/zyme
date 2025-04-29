@extends('index')

@section('content')
    <div class="container">
        <h3>ユーザ管理</h3>
        <div class="col-md-12">
            <form action="{{ url('/users/update', $user->id) }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name"> 名前 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email"> メール <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="password"> パスワード <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" value="{{ $user->password }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <br>
                        <button type="submit" class="btn btn-success">保存</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#flipFlop">画像選択</button>
                <button class="btn btn-success">
                    <a href="{{ url('/users/multiple-upload/' . $user->id) }}" style="color: white;">画像取り込み</a>
                </button>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered table-hover">
                <thead>
                    <td>項目</td>
                    <td>ファイル名</td>
                    <td>締め切り</td>
                    <td>優先度</td>
                    <td>状態</td>
                    <td>操作 / Googleドライブに保存 </td>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->filename }}</td>
                        <td>{{ $file->deadline }}</td>
                        <td>{{ $file->txt_priority }}</td>
                        <td>{{ $file->txt_synchronize }}</td>
                        <td>
                            @if ($file->status == 1)
                            <button class="btn btn-secondary">処理</button>
                            @elseif ($file->status == 2)
                            <a href="{{ url('/users/show/confirm/' . $file->id) }}"
                                class="btn btn-primary">
                                承認する
                            </a>
                            @else
                            <button class="btn btn-success">終わり</button>
                            @endif
                            @if ($file->synchronize == 0)
                                <a href="{{ url('/users/synchronize/' .$file->id) }}" class="btn btn-primary">
                                    ドンボ
                                </a>
                            @else
                            <button class="btn btn-success">ドンボ</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $files->links() !!}
        </div>
    </div>

    <div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <form action="{{ url('/users/single-upload', $user->id) }}" method="POST"  enctype="multipart/form-data">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalLabel">ファイルの選択</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">ファイル名</label>
                    <br>
                    <label for="file" class="btn btn-success">画像選択</label>
                    <input style="visibility: hidden" type="file" name="file" id="file">
                </div>
                <div class="form-group">
                    <label for="dateline">締め切り</label>
                    <input type="date" name="dateline" id="dateline" placeholder="" class="form-control">
                </div>
                <div class="form-group">
                    <label for="priority">優先度</label>
                    <select name="priority" id="priority" class="form-control">
                        <option value="3">High</option>
                        <option value="2">Medium</option>
                        <option value="1">Low</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-success">保存</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            </div>
          </div>
        </div>
        </form>
    </div>
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <!-- Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        // Initialize tooltip component
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        // Initialize popover component
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
    </script>
@endsection
