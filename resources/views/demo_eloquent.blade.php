<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo relation ship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <h1>List user and files</h1>
    <form action="{{url('/eloquent')}}">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="editor_name" placeholder="Editor name">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="file_name" placeholder="File name">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @foreach ($users as $user)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#file-{{$user->id}}" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">
                    {{$user->name}}
                </button>
            </li>
        @endforeach
      </ul>
      <div class="tab-content" id="myTabContent">
        @foreach ($users as $user)
            <div class="tab-pane fade" id="file-{{$user->id}}" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <table class="table table-hover table-bordered">
                    <thead>
                        <th>
                            <td>Filename</td>
                            <td>Deadline</td>
                            <td>Status</td>
                            <td>Priority</td>
                            <td>Sync Status</td>
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($user->files as $file)
                            <tr>
                                <td>{{$file->id}}</td>
                                <td>{{$file->filename}}</td>
                                <td>{{$file->deadline}}</td>
                                <td>{{$file->status}}</td>
                                <td>{{$file->priority}}</td>
                                <td>{{$file->synchronize}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>