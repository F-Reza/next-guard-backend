<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Admin Login - Next Guard</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

<div class="login-page d-flex align-items-center justify-content-center p-3">

    <div class="card login-card w-100">

        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">

                <div class="fw-bold fs-3">
                    Next Guard
                </div>

                <div class="text-muted">
                    Admin Control Panel
                </div>

            </div>


            @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

            @endif


            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            @if($errors->any())

                <div class="alert alert-danger">

                    @foreach($errors->all() as $error)

                        <div>{{ $error }}</div>

                    @endforeach

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('admin.login.submit') }}"
            >

                @csrf


                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control form-control-lg"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >

                </div>


                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control form-control-lg"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary btn-lg w-100"
                >
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Sign In
                </button>

            </form>

        </div>

    </div>

</div>

</body>

</html>