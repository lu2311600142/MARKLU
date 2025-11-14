<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        main h2 {
            margin-bottom: 20px;
            color: #764ba2;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        input:focus {
            border-color: #764ba2;
            box-shadow: 0 0 5px rgba(118, 75, 162, 0.5);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-success {
            background: linear-gradient(90deg, #667eea, #764ba2);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #5a67d8, #6b46c1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .btn-link {
            background: transparent;
            color: #764ba2;
            text-decoration: underline;
            margin-left: 10px;
        }

        /* Alerts */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            text-align: left;
        }

        .alert-danger {
            background: #ffe5e5;
            color: #c53030;
        }

        .alert-success {
            background: #e6fffa;
            color: #2f855a;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
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

    <header>
        <h1>Register</h1>
    </header>

    <nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('about') ?>">About</a>
        <a href="<?= base_url('contact') ?>">Contact</a>
        <a href="<?= base_url('login') ?>">Login</a>
        <a href="<?= base_url('register') ?>">Register</a>
    </nav>

    <main>
        <h2>Create your account</h2>

        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('register') ?>">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= old('name') ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label>Password (min 6 characters)</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirm" required>
            </div>

            <button type="submit" name="reg" class="btn btn-success">Register</button>
            <a href="<?= base_url('login') ?>" class="btn btn-link">Login</a>
        </form>
    </main>

    <footer>
        &copy; 2025 Your ITE311-LU. All rights reserved.
    </footer>

</body>
</html>