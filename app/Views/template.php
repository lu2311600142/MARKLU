<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My CodeIgniter App</title>

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* Global Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }

    /* Navbar */
    .navbar {
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .nav-link {
      transition: color 0.3s ease;
    }
    .nav-link:hover {
      color: #0d6efd !important;
    }

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: white;
      padding: 100px 20px;
      text-align: center;
      border-radius: 0 0 30px 30px;
      animation: fadeIn 1.5s ease-in-out;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: bold;
      animation: slideDown 1s ease;
    }
    .hero p {
      font-size: 1.2rem;
      opacity: 0.9;
      animation: slideUp 1s ease;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }
    .card i {
      font-size: 2.5rem;
      color: #0d6efd;
      margin-bottom: 15px;
    }

    /* Footer */
    footer {
      background: #212529;
      color: #adb5bd;
      padding: 25px;
      text-align: center;
      margin-top: 50px;
      border-radius: 20px 20px 0 0;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideDown {
      from { transform: translateY(-30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"><i class="bi bi-code-slash"></i> CI4 + Bootstrap</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-house-fill"></i> Home</a></li> 
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-info-circle-fill"></i> About</a></li>
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-envelope-fill"></i> Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Welcome to My CodeIgniter + Bootstrap App</h1>
      <p>Clean, modern, animated, and responsive design using Bootstrap 5</p>
      <a href="#" class="btn btn-light btn-lg mt-3 shadow-sm"><i class="bi bi-rocket-takeoff-fill"></i> Get Started</a>
    </div>
  </section>

  <!-- Content Section -->
  <div class="container my-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center shadow-sm">
          <i class="bi bi-house-door-fill"></i>
          <h5 class="fw-bold">Home</h5>
          <p>Explore the main page of your CI4 app with Bootstrap styling.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center shadow-sm">
          <i class="bi bi-person-badge-fill"></i>
          <h5 class="fw-bold">About</h5>
          <p>Learn more about this project and its amazing features.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center shadow-sm">
          <i class="bi bi-telephone-fill"></i>
          <h5 class="fw-bold">Contact</h5>
          <p>Reach out for inquiries, collaborations, or feedback anytime.</p>
        </div>
      </div>
    </div>

    <!-- Render Dynamic Content -->
    <div class="mt-5">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; <?= date('Y') ?> My CodeIgniter App |  CI4 + Bootstrap</p>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
