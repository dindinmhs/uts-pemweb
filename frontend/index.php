<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFB Salon</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Optional: Custom Styles -->
    <style>
        /* Custom styles for sections */
        .hero-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        .about-section,
        .services-section {
            padding: 60px 0;
        }

        .contact-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }

        .service-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .service-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">IFB Salon</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light" href="login.php" role="button">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section Beranda (2 Kolom) -->
    <section id="home" class="hero-section text-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4">Selamat Datang di IFB Salon</h1>
                    <p class="lead">Layanan terbaik untuk kecantikan dan kenyamanan Anda.</p>
                    <a href="login.php" class="btn btn-primary btn-lg">Pesan Sekarang</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://plus.unsplash.com/premium_photo-1661380558859-40df8dd91dfd?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Salon Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Section Tentang Kami (2 Kolom) -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-4">Tentang Kami</h2>
                    <p class="lead">Kami adalah salon yang berfokus pada memberikan layanan kecantikan terbaik untuk Anda.</p>
                    <p>Dengan berbagai layanan, pengalaman yang nyaman, dan staf yang terlatih, kami berkomitmen untuk membuat Anda merasa lebih percaya diri dan cantik.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="About Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Section Layanan -->
    <section id="services" class="services-section text-center">
        <div class="container">
            <h2 class="display-4">Layanan Kami</h2>
            <p class="lead">Kami menawarkan berbagai layanan kecantikan untuk memenuhi kebutuhan Anda.</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="service-card">
                        <h4>Potong Rambut</h4>
                        <p>Cocok untuk segala jenis gaya rambut. Kami menyediakan potongan rambut sesuai dengan tren terkini.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <h4>Manikur & Pedikur</h4>
                        <p>Layanan perawatan kuku yang membuat tangan dan kaki Anda lebih cantik dan sehat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card">
                        <h4>Perawatan Wajah</h4>
                        <p>Treatment untuk meremajakan kulit wajah Anda dengan produk-produk berkualitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Kontak -->
<section id="contact" class="contact-section text-center">
    <div class="container">
        <h2 class="display-4">Kontak Kami</h2>
        <p class="lead">Jika Anda memiliki pertanyaan atau ingin membuat reservasi, hubungi kami di bawah ini.</p>

        <!-- Alamat dan Email -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <p><strong>Email:</strong> <a href="mailto:ifbsalon@gmail.com">ifbsalon@gmail.com</a></p>
                <p><strong>Alamat:</strong> Jl. Raya No. 123, Bandung, Indonesia</p>
                <p><strong>Telepon:</strong> +62 21 555 1234</p>
            </div>
        </div>
    </div>
</section>


    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2024 IFB Salon | All rights reserved</p>
            <p>Follow Us: 
                <a href="#" class="text-white">Facebook</a> |
                <a href="#" class="text-white">Instagram</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS and Popper.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
