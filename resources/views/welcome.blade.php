<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --dark-color: #2d3748;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      color: var(--dark-color);
      line-height: 1.6;
    }

    .main-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 15px 0;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .logo { 
      font-size: 1.8rem;
      font-weight: bold; 
      display:flex; 
      align-items:center; 
    }
    .logo i { 
      margin-right:10px; 
      font-size:2rem; 
    }
    .nav-link { 
      color:white!important; 
      font-weight:500; 
      padding:8px 15px!important; 
      margin:0 5px; 
      border-radius:5px; 
      transition:all 0.3s; 
    }
    .nav-link:hover, .nav-link.active { 
      background: rgba(255,255,255,0.2); 
    }
    .auth-buttons .btn { 
      margin-left:10px; 
      border-radius:20px; 
      padding:8px 20px; 
      font-weight:500; 
    }
    .btn-login { 
      background:transparent; 
      border:2px solid white; 
      color:white; 
    }
    .btn-register { 
      background:white; 
      color:var(--primary-color); 
      border:2px solid white; 
    }

    .hero-section {
      background: linear-gradient(rgba(102,126,234,0.8), rgba(118,75,162,0.8)), 
        url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1153&q=80');
      background-size: cover;
      background-position:center;
      color:white;
      padding:100px 0;
      text-align:center;
    }
    .hero-title { 
      font-size:3.5rem; 
      font-weight:700; 
      margin-bottom:20px; 
    }
    .hero-subtitle { 
      font-size:1.5rem; 
      margin-bottom:30px; 
      max-width:700px; 
      margin:auto; 
    }
    .hero-buttons .btn { 
      margin:0 10px; 
      padding:12px 30px; 
      border-radius:30px; 
      font-weight:600; 
      font-size:1.1rem; 
    }
    .btn-primary-custom { 
      background:white; 
      color:var(--primary-color); 
      border:none; 
    }

    .features-section { 
      padding:80px 0; 
      background:white; 
    }
    .section-title { 
      text-align:center; 
      margin-bottom:50px; 
      color:var(--dark-color); 
    }
    .feature-card { 
      background:white; 
      border-radius:15px; 
      padding:30px; 
      text-align:center; 
      box-shadow:0 10px 30px rgba(0,0,0,0.05); 
      transition:0.3s; 
      border:1px solid #f1f3f4; 
      height:100%; 
    }
    .feature-card:hover { 
      transform:translateY(-10px); 
      box-shadow:0 15px 40px rgba(0,0,0,0.1); 
    }
    .feature-icon { 
      width:80px; 
      height:80px; 
      background:linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
      border-radius:50%; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      margin:0 auto 25px; 
      color:white; 
      font-size:2rem; 
    }
    .feature-card h3 { 
      font-size:1.5rem; 
      font-weight:600; 
      margin-bottom:15px; 
    }
    .feature-card p { 
      color:#6c757d; 
    }

    .testimonials-section { 
      padding:80px 0; 
      background:white; 
    }
    .testimonial-card { 
      background:#f8f9fa; 
      border-radius:15px; 
      padding:30px; 
      margin:20px; 
      position:relative; 
    }
    .testimonial-card::before { 
      content:'""'; 
      font-size:4rem; 
      color:var(--primary-color); 
      position:absolute; 
      top:10px; 
      left:20px; 
      opacity:0.2; 
    }
    .testimonial-text { 
      font-style:italic; 
      margin-bottom:20px; 
      position:relative; 
      z-index:1; 
    }
    .testimonial-author { 
      display:flex; 
      align-items:center; 
    }
    .author-avatar { 
      width:50px; 
      height:50px; 
      border-radius:50%; 
      background:var(--primary-color); 
      color:white; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      font-weight:bold; 
      margin-right:15px; 
    }
    .author-info h5 { 
      margin-bottom:0; 
      font-size:1.1rem; 
    }
    .author-info span { 
      color:#6c757d; 
      font-size:0.9rem; 
    }

    .cta-section { 
      padding:80px 0; 
      background:linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
      color:white; 
      text-align:center; 
    }
    .cta-title { 
      font-size:2.5rem; 
      font-weight:700; 
      margin-bottom:20px; 
    }
    .cta-buttons .btn { 
      margin:0 10px; 
      padding:12px 30px; 
      border-radius:30px; 
      font-weight:600; 
      font-size:1.1rem; 
    }
    .btn-light-custom { 
      background:white; 
      color:var(--primary-color); 
      border:none; 
    }
    .btn-outline-light-custom { 
      background:transparent; 
      border:2px solid white; 
      color:white; 
    }

    .main-footer { 
      background: var(--dark-color); 
      color:white; 
      padding:60px 0 20px; 
    }
    .footer-logo { 
      font-size:1.8rem; 
      font-weight:bold; 
      margin-bottom:20px; 
      display:inline-block; 
    }
    .footer-about p, .footer-links a, .footer-contact span { 
      color:#a0aec0; 
    }
    .footer-links h4 { 
      font-size:1.3rem; 
      margin-bottom:20px; 
      position:relative; 
      padding-bottom:10px; 
    }
    .footer-links h4::after { 
      content:''; 
      position:absolute; 
      left:0; 
      bottom:0; 
      width:40px; 
      height:3px; 
      background:var(--primary-color); 
    }
    .footer-links ul { 
      list-style:none; 
      padding:0; 
    }
    .footer-links li { 
      margin-bottom:10px; 
    }
    .footer-links a { 
      text-decoration:none; 
      transition:0.3s; 
    }
    .footer-links a:hover { 
      color:white; 
      padding-left:5px; 
    }
    .footer-contact li { 
      display:flex; 
      align-items:flex-start; 
      margin-bottom:15px; 
    }
    .footer-contact i { 
      margin-right:10px; 
      color:var(--primary-color); 
      margin-top:5px; 
    }
    .social-links { 
      display:flex; 
      margin-top:20px; 
    }
    .social-links a { 
      width:40px; 
      height:40px; 
      background:rgba(255,255,255,0.1); 
      border-radius:50%; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      color:white; 
      margin-right:10px; 
      transition:0.3s; 
      text-decoration:none; 
    }
    .social-links a:hover { 
      background:var(--primary-color); 
      transform:translateY(-3px); 
    }
    .footer-bottom { 
      border-top:1px solid #4a5568; 
      padding-top:20px; 
      margin-top:40px; 
      text-align:center; 
      color:#a0aec0; 
    }

    html { 
      scroll-behavior: smooth; 
    }

    @media (max-width:768px) {
      .hero-title { font-size:2.5rem; }
      .hero-subtitle { font-size:1.2rem; }
      .service-item { flex-direction:column; text-align:center; }
      .service-icon { margin-right:0; margin-bottom:20px; }
      .auth-buttons { margin-top:15px; }
      .cta-title { font-size:2rem; }
    }
  </style>
</head>
<body>

<!-- Header -->
<header class="main-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <div class="logo"><i class="fas fa-hospital-alt"></i> MEDICARE HOSPITAL</div>
      <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
            <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          </ul>
          <div class="auth-buttons">
            <a href="/login" class="btn btn-login">Login</a>
            <a href="/register" class="btn btn-register">Register</a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</header>

<!-- Hero -->
<section class="hero-section" id="home">
  <div class="container">
    <h1 class="hero-title">Hospital Management System</h1>
    <p class="hero-subtitle">Streamlining hospital operations with our comprehensive management solution for better patient care and efficient administration.</p>
    <div class="hero-buttons">
      <a href="/register" class="btn btn-primary-custom">Get Started</a>
      <a href="#features" class="btn btn-outline-light">Learn More</a>
    </div>
  </div>
</section>

<!-- Features -->
<section class="features-section" id="features">
  <div class="container">
    <div class="section-title">
      <h2>Our Features</h2>
      <p>Powerful tools to help you manage hospital operations seamlessly.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-user-injured"></i></div>
          <h3>Patient Management</h3>
          <p>Track and manage all patient records efficiently in one place with comprehensive patient profiles and history.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
          <h3>Appointment Scheduling</h3>
          <p>Schedule, track, and manage appointments effortlessly with automated reminders and real-time updates.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-pills"></i></div>
          <h3>Pharmacy Management</h3>
          <p>Manage inventory, prescriptions, and medicine efficiently with automated stock alerts and billing.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-file-medical"></i></div>
          <h3>Medical Records</h3>
          <p>Digital medical records with secure access, history tracking, and easy retrieval for better patient care.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-bed"></i></div>
          <h3>Room Management</h3>
          <p>Efficient room allocation, bed management, and patient transfer tracking for optimal resource utilization.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
          <h3>Analytics & Reports</h3>
          <p>Comprehensive analytics and reporting tools for data-driven decisions and performance monitoring.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section" id="testimonials">
  <div class="container">
    <div class="section-title">
      <h2>What Our Patients Say</h2>
      <p>Real feedback from our valued patients and healthcare professionals.</p>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "The Hospital Management System has transformed how we handle patient care. Everything is so organized and efficient now."
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">SK</div>
            <div class="author-info">
              <h5>Dr.Srikanth</h5>
              <span>Senior Cardiologist</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "As a patient, I appreciate how easy it is to book appointments and access my medical records. The system is user-friendly."
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">SJ</div>
            <div class="author-info">
              <h5>Jagadeesan</h5>
              <span>Patient</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "The administrative features have reduced our paperwork by 70%. Our staff can focus more on patient care now."
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">MS</div>
            <div class="author-info">
              <h5>Manjunathan</h5>
              <span>Hospital Administrator</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section" id="contact">
  <div class="container">
    <h2 class="cta-title">Ready to Transform Your Healthcare Management?</h2>
    <p class="hero-subtitle">Join thousands of healthcare professionals who trust our system for efficient hospital management.</p>
    <div class="cta-buttons">
      <a href="/register" class="btn btn-light-custom">Start Free Trial</a>
      <a href="#contact" class="btn btn-outline-light-custom">Contact Sales</a>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="main-footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="footer-about">
          <a href="#home" class="footer-logo"><i class="fas fa-hospital-alt"></i> HMS</a>
          <p>Our Hospital Management System provides comprehensive solutions for modern healthcare facilities, ensuring efficient operations and superior patient care.</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <div class="footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="footer-links">
          <h4>Resources</h4>
          <ul>
            <li><a href="#">Documentation</a></li>
            <li><a href="#">Support Center</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="footer-links">
          <h4>Contact Us</h4>
          <ul class="footer-contact">
            <li><i class="fas fa-map-marker-alt"></i>MTH Road, Tidel Park 3, Pattabiram, Chennai, Tamil Nadu, 600072.</li>
            <li><i class="fas fa-phone"></i>+91 8778636729</li>
            <li><i class="fas fa-envelope"></i>manjunathan04@gmail.com</li>
            <li><i class="fas fa-clock"></i>Mon - Fri: 9:30 AM - 6:30 PM</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Hospital Management System. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Add active class to nav links on scroll
  document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');
    
    window.addEventListener('scroll', function() {
      let current = '';
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 100)) {
          current = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
          link.classList.add('active');
        }
      });
    });
  });
</script>
</body>
</html>