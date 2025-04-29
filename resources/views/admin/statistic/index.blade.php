@extends('index')

@section('content')
    <div class="col-md-12">
        <h1>ユーザ追加</h1>
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <form action="{{ url('/statistic') }}" method="GET">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="">
                        <div class="col-md-8">
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">検索</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="btn-action-import-export">
        <a href="{{ url('/statistic/export') }}" class="btn btn-primary">エクスポート</a>
        <a href="{{ url('/statistic/import') }}" class="btn btn-primary">インポート</a>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <th>#</th>
            <th>名前</th>
            <th>メール</th>
            <th>完了</th>
            <th>支払い状況</th>
            <th>給料</th>
            <th>操作</th>
        </thead>
        <tbody>
            @if (count($salaries) > 0)
                @foreach ($salaries as $salary)
                    <tr>
                        <td>{{ $salary->id }}</td>
                        <td>{{ $salary->user->name }}</td>
                        <td>{{ $salary->user->email }}</td>
                        <td>{{ count($salary->user->files) }}</td>
                        <td>{{ $salary->txt_status }}</td>
                        <td>{{ $salary->salary }}</td>
                        <td>
                            @if ($salary->status == App\Models\Salary::STATUS_PAID)
                                <a href="#" class="btn btn-secondary" disabled>有料</a>
                            @else
                                <a href="{{ url('/statistic/salary/paid/' . $salary->id) }}" class="btn btn-primary">有料</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection