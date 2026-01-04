<!DOCTYPE html>

<html lang="en">

<head>
    @include('layouts.head')
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-4">
        @if ($errors->any())

            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <ul>


                    @foreach ($errors->all() as $message)
                        <li>
                            {{ $message }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    @include('layouts.script')

</html>
