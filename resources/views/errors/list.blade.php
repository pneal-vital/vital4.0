
<!-- Beginning of errors/list.blade.php -->

<!-- { { var_dump($errors) } } -->
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- End of errors/list.blade.php -->
