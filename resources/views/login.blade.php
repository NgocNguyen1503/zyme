<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #login-panel {
            margin: 0 auto;
        }
        #login-form {
            margin: 20% auto;
            border: 1px solid #00000057;
            padding: 25px;
            border-radius: 15px;

        }
    </style>
    </head>
  <body>
    <div class="container" id="login-panel">
        <div class="col-md-3" id="login-form">
            <h1>ログイン</h1>
            <form action="{{ url('/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="ユーザ名">
                    <br>
                    <input type="password" name="password" class="form-control" placeholder="パスワード">
                    <br>
                    <button class="btn btn-primary">ログイン</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>