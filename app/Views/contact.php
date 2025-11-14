<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
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
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        main p {
            font-size: 1.2rem;
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
        <h1>Contact Us</h1>
    </header>

    <nav>
        <a href="<?= base_url('/') ?>">Home</a>
        <a href="<?= base_url('about') ?>">About</a>
        <a href="<?= base_url('contact') ?>">Contact</a>
        <a href="<?= base_url('login') ?>">Login</a>
        <a href="<?= base_url('register') ?>">Register</a>
    </nav>

    <main>
        <p>This is the Contact page. Here you can write information about how visitors can reach you.</p>
    </main>

    <footer>
        &copy; 2025 Your ITE311-LU. All rights reserved.
    </footer>

</body>
</html>
