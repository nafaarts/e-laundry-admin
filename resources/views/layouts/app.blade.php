<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    @yield('custom_styles')
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 mx-3" href="/">
            <img src="{{ url('img/logo.svg') }}" width="110" height="32" alt="Tabler"
                class="navbar-brand-image">
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <span>{{ auth()->user()->name }}</span>
        </div>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                    <li><a class="dropdown-item"
                            onclick="return document.getElementById('logout-form').submit()">Keluar</a></li>
                </ul>
            </li>
            <li class="nav-item d-none" id="notification">
                <a class="nav-link position-relative" href="{{ route('laundry-orders') }}">
                    <div id="order-info" class="bg-danger position-absolute text-white rounded d-none"
                        style="font-size: 12px; padding: 0 5px; top: -0.2px; right: 10px">
                        -</div>
                    <i class="fas fa-bell fa-fw fs-3"></i>
                </a>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    @include('layouts.navigation')
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Login Sebagai :</div>
                    <span class="text-uppercase">{{ auth()->user()->role }}</span>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid p-4">
                    @yield('content')
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; E-Laundry {{ date('Y') }}</div>
                        <div>
                            <a href="#">Muhammad Chaizir</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('js/scripts.js') }}"></script>
    @yield('custom_scripts')

    @if (auth()->user()->role == 'laundry')
        <script>
            document.getElementById('notification').classList.remove('d-none')
            const getNotification = async () => {
                try {
                    let getData = await fetch('/api/notification/' + '{{ auth()->user()->laundry->id }}')
                    let result = await getData.json()
                    if (result !== 0) {
                        document.getElementById('order-info').classList.remove('d-none')
                        document.getElementById('order-info').textContent = result
                    } else {
                        document.getElementById('order-info').classList.add('d-none')
                    }
                } catch (error) {
                    console.error(error)
                }
            }
            getNotification()
            setInterval(() => {
                getNotification()
            }, 5000);
        </script>
    @endif
</body>

</html>
