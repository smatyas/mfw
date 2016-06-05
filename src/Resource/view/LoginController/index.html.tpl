{{$header}}

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

{{$footer}}
