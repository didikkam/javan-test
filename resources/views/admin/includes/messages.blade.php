<div class="container-fluid">
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br />
            @endforeach
        </div>
    @endif

    @if (session()->get('flash_success'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('flash_success') }}
        </div>
    @endif

    @if (session()->get('flash_warning'))
        <div class="alert alert-warning" role="alert">
            {{ session()->get('flash_warning') }}
        </div>
    @endif

    @if (session()->get('flash_info') || session()->get('flash_message'))
        <div class="alert alert-info" role="alert">
            {{ session()->get('flash_info') }}
        </div>
    @endif

    @if (session()->get('flash_danger'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('flash_danger') }}
        </div>
    @endif

</div>
