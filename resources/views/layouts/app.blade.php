<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Dashboard</a>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/dashboard/') ? 'active' : '' }}" href="/dashboard/">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/dashboard/surat-masuk') ? 'active' : '' }}"
                                href="/dashboard/surat-masuk">
                                Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/dashboard/surat-keluar') ? 'active' : '' }}"
                                href="/dashboard/surat-keluar">
                                Surat Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('/dashboard/surat-revisi') ? 'active' : '' }}"
                                href="/dashboard/surat-revisi">
                                Revisi
                            </a>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link p-0" style="color: inherit; text-align: left;">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
