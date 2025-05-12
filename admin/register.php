<?php
// session_start();
if (!empty($_SESSION['username_nanas'])) {
    header('location:home');
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Penjualan Nanas Subang</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="assets/css/login.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto text-center">
        <div class="card shadow-sm mx-auto" style="max-width: 400px; border-radius: 15px;">
            <div class="card-body">
                <form class="needs-validation" novalidate action="proses/register_pelanggan.php" method="post">
                    <img class="mb-2" src="assets/img/nnas.png" alt="" width="130" height="120">
                    <h1 class="h3 mb-3 fw-normal">Register Pelanggan</h1>

                    <div class="form-floating mb-3">
                        <input name="nama" type="text" class="form-control" id="floatingInput" placeholder="nama" required>
                        <label for="floatingInput">Nama</label>
                        <div class="invalid-feedback">
                            Isi Nama Anda.
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="form-floating mb-3">
                        <input name="username" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Email address</label>
                        <div class="invalid-feedback">
                            Username Invalid.
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="form-floating mb-3">
                        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        <div class="invalid-feedback">
                            Password Invalid.
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="form-floating mb-3">
                        <input name="nohp" type="number" class="form-control" id="floatingInput" placeholder="12345" required>
                        <label for="floatingInput">No Telphone</label>
                        <div class="invalid-feedback">
                            Isi Nomor Telp Anda.
                        </div>
                    </div>

                    <div class="form-check text-start my-3">
                        <label class="form-check-label" for="flexCheckDefault">
                            <a href="login" class="text-decoration-none">Sign In</a>
                        </label>
                    </div>

                    <button class="btn btn-primary w-100 py-2" type="submit" name="submit_validate" value="123">Register</button>
                    <p class="mt-5 mb-3 text-body-secondary">&copy;2024</p>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>