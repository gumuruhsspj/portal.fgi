<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Portal - Peserta Kursus Komputer</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="/assets/img/favicon.ico" rel="icon">
  <link href="/assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  

  <!-- Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <div href="/" class="logo align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
         <img src="assets/img/logo.jpg" alt=""> 
        <h1 class="sitename">FGroupIndonesia</h1>
        <p class="box-motto">Training &amp; Digital Solutions Provider</p> 
      </div>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">Kenapa Kursus</a></li>
          <li><a href="#services">Pelayanan</a></li>
          <li><a href="#features">Keunggulan</a></li>
          <li><a href="#pricing">Pilihan Harga</a></li>
          
          <li><a href="#contact">Kontak Kami</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h2>Portal Kursus Komputer</h2>
            <p>Mempermudah akses data manajemen, jadwal, hingga materi yang bisa diakses kapan saja, dan dimanapun bersama <b>FGroupIndonesia</b>!</p>
            <div class="d-flex">
              <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn-get-started">Login</a>
              <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Fakta Kerja</span></a>
            </div>
            <?php 
              if(!empty($error)){
                echo '<div class="alert alert-danger mt-3" role="alert">'.$message.'</div>';
              }
            ?>
          </div>
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="assets/img/hero-img.png" class="img-fluid" alt="">
          </div>
        </div>
      </div>

      <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
        <div class="container position-relative">
          <div class="row gy-4 mt-5">

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-easel"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Materi Open Access</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-gem"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Konten Terbaik</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-geo-alt"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Akses Tanpa Batas</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-command"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Variasi Pilihan</a></h4>
              </div>
            </div><!--End Icon Box -->

          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p class="who-we-are">Kenapa Harus Kursus Komputer?</p>
            <h3>Strategi Tepat</h3>
            <p class="fst-italic">
              Mengembangkan potensi, mengasah skills, dan juga mendapatkan langsung 
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> 
                <span>Praktek Ilmu Komputer.</span>
              </li>
              <li><i class="bi bi-check-circle"></i> 
                <span>Memperoleh Penjadwalan yang teratur.</span>
              </li>
              <li><i class="bi bi-check-circle"></i> 
                <span>Mendapatkan Kesempatan Berkarir Lebih baik.</span></li>
            </ul>
            <a href="#" data-bs-toggle="modal" data-bs-target="#KursusKomputerModal" class="read-more"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">
               <div class="col-lg-6">
                <div class="row gy-4">
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-1.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-4.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="row gy-4">
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-2.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-12">
                    <img src="assets/img/about-company-3.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pelayanan</h2>
        <div><span>Kebutuhan Anda ialah </span> <span class="description-title">Kami Solusinya!</span> </div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-activity"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Dukungan Aktif</h3>
              </a>
              <p>Semua yang ingin bekerja tentu harus mempersiapkan dan mendapatkan dukungan untuk tetap siap siaga!</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-watch"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Flexibilitas Waktu</h3>
              </a>
              <p>Untuk yang mengingkan jadwal flexibile dan bisa diatur tentu memilih bagian ini.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-easel"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Akses Materi</h3>
              </a>
              <p>Ingin mengakses materi secara continue tanpa batasan tempat? Boleh koq disini 24/7 jam!</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-bounding-box-circles"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Kursus &amp; Sertifikat</h3>
              </a>
              <p>Cocok karena kamu ingin segera melamar pekerjaan yang membutuhkan pemberkasan sertifikat komputer!</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-calendar4-week"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Harga Terjangkau</h3>
              </a>
              <p>Mencari tempat yang harga yang terjangkau plus dapat keuntungan banyak memang sulit! Beruntung kami ada untuk anda.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-chat-square-text"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Ruang Konsultasi</h3>
              </a>
              <p>Ingin sewaktu-waktu dapat bertanya sekaligus mengasah pengetahuan ketika mendapat kasus lapangan. Ya ini dia tempatnya!</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Features Section -->
    <section id="features" class="features section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Keunggulan</h2>
        <div><span>Sesuai dengan Yang</span> <span class="description-title">Anda Cari?</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5 justify-content-between">

          <div class="col-xl-5" id="picture-feature" data-aos="zoom-out" data-aos-delay="100">
            
          </div>

          <div class="col-xl-6 d-flex">
            <div class="row align-self-center gy-4">

              <div data-feature="1" class="featured col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Bisa Tanya &amp; Jawab</h3>
                </div>
              </div><!-- End Feature Item -->

              <div data-feature="4" class="featured col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Pelayanan Cepat &amp; Ramah</h3>
                </div>
              </div><!-- End Feature Item -->

              <div data-feature="2" class="featured col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Harga Bersahabat</h3>
                </div>
              </div><!-- End Feature Item -->

              <div data-feature="3" class="featured col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Materi Sesuai Yang Diinginkan</h3>
                </div>
              </div><!-- End Feature Item -->

              <div data-feature="6" class="featured col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Bawa Temen Dapat Diskon!</h3>
                </div>
              </div><!-- End Feature Item -->

              <div data-feature="5" class="featured col-md-6" data-aos="fade-up" data-aos-delay="700">
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Diskusi Terbuka</h3>
                </div>
              </div><!-- End Feature Item -->

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Features Section -->

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Harga</h2>
        <div><span>Pilih Akses</span> <span class="description-title">Sesuai Kebutuhanmu!</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="pricing-tem">
              <h3 style="color: #20c997;">Gratis</h3>
              <div class="price"><sup>Rp.</sup>0<span> / bln</span></div>
              <div class="icon">
                <i class="bi bi-box" style="color: #20c997;"></i>
              </div>
              <ul>
                <li>Dapat Materi Gratis</li>
                <li>Akses 24/7 jam</li>
                <li>Informasi Loker Uptodate</li>
                <li class="na">Diskusi Whatsapp</li>
                <li class="na">Sertifikat Komputer</li>
              </ul>
              <a href="#" data-jenis="Gratis" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn-buy">Daftar Sekarang!</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-tem">
              <span class="featured">Premium</span>
              <h3 style="color: #0dcaf0;">Pelajar</h3>
              <div class="price"><sup>Rp.</sup>15rb<span> / 1x</span></div>
              <div class="icon">
                <i class="bi bi-send" style="color: #0dcaf0;"></i>
              </div>
              <ul>
                <li>Dapat Materi Khusus</li>
                <li>Akses 24/7 jam</li>
                <li>Informasi Loker Uptodate</li>
                <li>Diskusi Whatsapp</li>
                <li>Sertifikat Komputer*</li>
               
              </ul>
              <a href="#" data-jenis="Pelajar" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn-buy">Daftar Sekarang!</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="pricing-tem">
              <h3 style="color: #fd7e14;">Pengajar Reguler</h3>
              <div class="price"><sup>Rp.</sup>25rb<span> / bln</span></div>
              <div class="icon">
                <i class="bi bi-airplane" style="color: #fd7e14;"></i>
              </div>
              <ul>
                <li>Fokus 4 peserta / materi</li>
                <li>Akses Asset Digital</li>
                <li>Email Notification</li>
                <li>Diskusi Whatsapp</li>
                
              </ul>
              <a href="#" data-jenis="Pengajar Reguler" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn-buy">Daftar Sekarang!</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="pricing-tem">
              <h3 style="color: #0d6efd;">Pengajar Master</h3>
              <div class="price"><sup>Rp.</sup>300rb<span> / semester</span></div>
              <div class="icon">
                <i class="bi bi-rocket" style="color: #0d6efd;"></i>
              </div>
              <ul>
                <li>Unlimited peserta / materi</li>
                <li>Akses Asset Digital</li>
                <li>Email Notification</li>
                <li>Diskusi Whatsapp</li>
                
              </ul>
              <a href="#" data-jenis="Pengajar Master" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn-buy">Daftar Sekarang!</a>
            </div>
          </div><!-- End Pricing Item -->

        </div><!-- End pricing row -->

      </div>

    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section light-background">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="content px-xl-5">
              <h3><span>Pertanyaan yg Sering </span><strong>Diajukan</strong></h3>
              <p>
                Baru kenalan dengan kursus komputer online di portal ini? Yuk kita simak baik-baik mana saja pertanyaan yang sering diungkapkan dari sini:
              </p>
            </div>
          </div>

          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

            <div class="faq-container">
              <div class="faq-item faq-active">
                <h3><span class="num">1.</span> <span>Saya ingin akses masuk. Dimanakah mendapatkannya?</span></h3>
                <div class="faq-content">
                  <p>Untuk memperoleh akses login di portal ini, kamu wajib mendaftarkan via <a href="https://fgroupindonesia.com/pendaftaran"><b>Formulir</b></a> yang sudah disediakan. Hanya peserta yang berhasil terdaftar yang akan mendapatkan akses login di dalam portal ini.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">2.</span> 
                  <span>Apakah ada expired date untuk tiap aksesnya?</span></h3>
                <div class="faq-content">
                  <p>Tidak ada limit expired date bagi tiap peserta yang telah memperoleh akses digital disini!</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">3.</span> 
                  <span>Dimana saya bisa mendapatkan informasi paket pilihan materi yang ingin dipelajari?</span></h3>
                <div class="faq-content">
                  <p>Klik Langsung pada katalog yang disediakan dari <a href="https://wa.me/c/6285795569337">Link Official Whatsapp ini</a>.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">4.</span> <span>Saya tertarik untuk mendapatkan Sertifikat Komputer saja, apakah boleh?</span></h3>
                <div class="faq-content">
                  <p>Ya, jalur cepat khusus mendapatkan sertifikat bisa diperoleh langsung, sesuai grade yang anda inginkan.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">5.</span> <span>Apabila saya kursus online saja apakah tetap mendapatkan serfitikat seperti peserta reguler (offline)?</span></h3>
                <div class="faq-content">
                  <p>Tentu saja! Setiap peserta kursus baik online maupun offline keduanya pasti memperoleh sertifikat komputer yang dapat diunduh (PDF) maupun di secara fisik (berkas dokumen).</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div>
        </div>

      </div>

    </section><!-- /Faq Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Kontak</h2>
        <div><span>Perlu Bantuan?</span> <span class="description-title">Kontak Kami!</span></div>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Office:</h3>
                <p>Jl. Parahyangan no.18, Komp. Panghegar Permai I,<br> ujung berung, Bandung 40614.</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Whatsapp Official:</h3>
                <p>+62857-9556-9337</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Corporate:</h3>
                <p>training@fgroupindonesia.com</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Custom Request:</h3>
                <p>request@fgroupindonesia.com</p>
              </div>
            </div><!-- End Info Item -->

          </div>

          <div class="col-lg-8">
            <form id="kirim-pesan-email" action="" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="customer_name" class="form-control" placeholder="Namamu" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Emailmu" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="title" placeholder="Judul" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="descriptions" rows="6" placeholder="Tulis Pertanyaanmu" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Pesan Anda telah dikirim. Thank you!</div>

                  <button type="submit">Kirimkan</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer light-background">

    <div class="container">
      <div class="copyright text-center ">
              
      </div>
      <div class="social-links d-flex justify-content-center">
        <a href="https://twitter.com/fgroupindonesia"><i class="bi bi-twitter-x"></i></a>
        <a href="https://facebook.com/fgroupindonesia"><i class="bi bi-facebook"></i></a>
        <a href="https://instagram.com/fgroup.indonesia"><i class="bi bi-instagram"></i></a>
        
      </div>
      <div class="credits">
      <p>© <?= date('Y'); ?> <span>Copyright</span> <strong class="px-1 sitename">FGroupIndonesia</strong> <span>All Rights Reserved</span></p>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <?php include('modal_login.php'); ?>
  <?php include('modal_registrasi.php'); ?>
  <?php include('modal_kenapa_kursus.php'); ?>

  <!-- Vendor JS Files -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/jquery371.min.js<?=$random;?>"></script>
   

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/animated.js<?=$random;?>"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js<?=$random;?>"></script>

</body>

</html>