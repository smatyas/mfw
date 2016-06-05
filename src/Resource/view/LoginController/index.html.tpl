<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}} | MFW</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main2.css"> <!-- intentionally included to make the main request alter between different web workers -->
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">MFW</a>
        </div>
    </div>
</nav>

<div class="container" role="main">
    <h1>Login</h1>

    {{$errors}}

    <form action="/login/check" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="form-group">
            <label for="username" class="control-label col-xs-2">Username</label>
            <div class="col-xs-4">
                <input type="text" class="form-control" id="username" name="username">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="control-label col-xs-2">Password</label>
            <div class="col-xs-4">
                <input type="password" class="form-control" id="password" name="password">
            </div>
        </div>
        <div class="form-group">
            <label for="captcha" class="control-label col-xs-2">Captcha</label>
            <div class="col-xs-2">
                <input type="text" class="form-control" id="captcha" name="captcha">
            </div>
            <div class="col-xs-2">
                <img src="/login/captcha" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-4">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </form>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
</body>
</html>
