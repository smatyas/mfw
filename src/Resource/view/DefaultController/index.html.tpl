{{$header}}

<div class="container" role="main">
    <h1>Content</h1>

    <div class="panel panel-default">
        <div class="panel-body">
            This table demonstrates the templating by showing different substituted values from the container.
        </div>
    </div>
    <table class="table table-striped">
        <tr>
            <td>$var1</td><td>{{$var1}}</td>
        </tr>
        <tr>
            <td>$var2</td><td>{{$var2}}</td>
        </tr>
        <tr>
            <td>$var3</td><td>{{$var3}} <strong>&larr; this parameter isn't provided by the controller.</strong></td>
        </tr>
        <tr>
            <td>Controller</td><td>{{$controller}}</td>
        </tr>
        <tr>
            <td>PHP-FPM server hostname</td><td>{{$hostname}} <strong>&larr; the hostname is altering between the 2 PHP servers.</strong></td>
        </tr>
        <tr>
            <td>Session ID</td><td>{{$session_id}}</td>
        </tr>
        <tr>
            <td>Session data</td><td>{{$session_data}}</td>
        </tr>
    </table>
</div>

{{$footer}}
