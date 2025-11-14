<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Reset & base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #eef2f7;
            color: #333;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
            letter-spacing: 1px;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: #764ba2;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            background-color: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        nav a:hover {
            background-color: #764ba2;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Main content */
        main {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        main h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #764ba2;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(90deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #5a67d8, #6b46c1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-link {
            color: #764ba2;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #6b46c1;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
            color: #555;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Login</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('about') ?>">About</a>
        <a href="<?= base_url('contact') ?>">Contact</a>
        <a href="<?= base_url('login') ?>">Login</a>
        <a href="<?= base_url('register') ?>">Register</a>
    </nav>

    <!-- Main Content -->
    <main>
        <h2>Welcome Back</h2>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="mt-3 text-center">
                <a href="<?= base_url('register') ?>" class="btn btn-link">Create an account</a>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer>
        &copy; 2025 Your ITE311-LU. All rights reserved.
    </footer>

</body>
</html>