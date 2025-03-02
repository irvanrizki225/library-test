<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')

            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <!-- Top navbar -->
                @include('layouts.partials.navbar')

                <!-- Dashboard Content -->
                @yield('content')
            </div>
        </div>
    </div>

    @include('layouts.partials.scripts')
</body>
</html>
