{{$header}}

<div class="container" role="main">
    <h1>Content</h1>

    <table class="table table-striped">
        <tr>
            <td>$var1</td><td>{{$var1}}</td>
        </tr>
        <tr>
            <td>$var2</td><td>{{$var2}}</td>
        </tr>
        <tr>
            <td>$var3</td><td>{{$var3}} &larr; this parameter isn't provided by the controller.</td>
        </tr>
        <tr>
            <td>Controller</td><td>{{$controller}}</td>
        </tr>
        <tr>
            <td>PHP-FPM server hostname</td><td>{{$hostname}}</td>
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
