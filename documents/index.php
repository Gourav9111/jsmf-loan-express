
<?php
require_once 'config.php';

$page_title = "Home";
$page_description = "Jay Shree Mahakal Finance Services - Your trusted finance partner for Personal Loans, Home Loans, Education Loans, Car Loans and more in Bhopal, Madhya Pradesh";

// Fetch loan categories from database
try {
    $stmt = $pdo->query("SELECT * FROM loan_categories WHERE is_active = 1 ORDER BY sort_order, name");
    $loan_categories = $stmt->fetchAll();
    
    // Separate featured and other loans
    $featured_loans = array_filter($loan_categories, function($loan) {
        return $loan['is_featured'] == 1;
    });
    
    $other_loans = array_filter($loan_categories, function($loan) {
        return $loan['is_featured'] == 0;
    });
    
} catch(PDOException $e) {
    // Fallback data if database fails
    $featured_loans = [
        ['name' => 'Personal Loan', 'icon' => 'fas fa-user', 'description' => 'Quick personal loans for immediate needs', 'key_point_1' => 'Instant approval', 'key_point_2' => 'No collateral', 'key_point_3' => 'Flexible tenure', 'min_amount' => 10000, 'max_amount' => 1000000, 'image_url' => 'https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=400&h=250&fit=crop'],
        ['name' => 'Home Loan', 'icon' => 'fas fa-home', 'description' => 'Affordable home loans with competitive rates', 'key_point_1' => '90% funding', 'key_point_2' => 'Tax benefits', 'key_point_3' => 'Up to 30 years', 'min_amount' => 500000, 'max_amount' => 50000000, 'image_url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=250&fit=crop'],
        ['name' => 'Education Loan', 'icon' => 'fas fa-graduation-cap', 'description' => 'Fund your education dreams', 'key_point_1' => 'Full course fees', 'key_point_2' => 'Moratorium period', 'key_point_3' => 'No processing fees', 'min_amount' => 50000, 'max_amount' => 2000000, 'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=250&fit=crop'],
        ['name' => 'Car Loan', 'icon' => 'fas fa-car', 'description' => 'Drive your dream car today', 'key_point_1' => '90% on-road price', 'key_point_2' => '2 hour approval', 'key_point_3' => 'Zero down payment', 'min_amount' => 100000, 'max_amount' => 2000000, 'image_url' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=250&fit=crop']
    ];
    $other_loans = [];
}

include 'includes/header.php';
?>

<!-- Hero Carousel Section -->
<section class="hero-carousel-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.9), rgba(114, 28, 36, 0.8)), url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&h=800&fit=crop') center/cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 col-md-10 mx-auto text-center text-white">
                                <h1 class="hero-title display-3 fw-bold mb-3 animate__animated animate__fadeInUp">Make Your Dream Home a Reality</h1>
                                <p class="hero-subtitle fs-4 mb-4 animate__animated animate__fadeInUp animate__delay-1s">Get instant home loan approval with competitive interest rates starting from 7% per annum</p>
                                <div class="hero-buttons animate__animated animate__fadeInUp animate__delay-2s">
                                    <a href="apply-loan.php?type=Home Loan" class="btn btn-light btn-lg me-3 px-4 py-3 shadow-lg hover-scale">
                                        <i class="fas fa-home me-2"></i>Apply for Home Loan
                                    </a>
                                    <a href="#emi-calculator" class="btn btn-outline-light btn-lg px-4 py-3 hover-scale">
                                        <i class="fas fa-calculator me-2"></i>Calculate EMI
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.9), rgba(30, 126, 52, 0.8)), url('https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=1920&h=800&fit=crop') center/cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 col-md-10 mx-auto text-center text-white">
                                <h1 class="hero-title display-3 fw-bold mb-3">Personal Loans Made Simple</h1>
                                <p class="hero-subtitle fs-4 mb-4">Quick approval for immediate needs with minimal documentation</p>
                                <div class="hero-buttons">
                                    <a href="apply-loan.php?type=Personal Loan" class="btn btn-light btn-lg me-3 px-4 py-3 shadow-lg hover-scale">
                                        <i class="fas fa-user me-2"></i>Apply for Personal Loan
                                    </a>
                                    <a href="check-status.php" class="btn btn-outline-light btn-lg px-4 py-3 hover-scale">
                                        <i class="fas fa-search me-2"></i>Check Status
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide" style="background: linear-gradient(135deg, rgba(0, 123, 255, 0.9), rgba(0, 86, 179, 0.8)), url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1920&h=800&fit=crop') center/cover;">
                    <div class="container h-100">
                        <div class="row h-100 align-items-center">
                            <div class="col-lg-8 col-md-10 mx-auto text-center text-white">
                                <h1 class="hero-title display-3 fw-bold mb-3">Grow Your Business with Us</h1>
                                <p class="hero-subtitle fs-4 mb-4">Business loans up to ₹50 lakhs with flexible repayment options</p>
                                <div class="hero-buttons">
                                    <a href="apply-loan.php?type=Business Loan" class="btn btn-light btn-lg me-3 px-4 py-3 shadow-lg hover-scale">
                                        <i class="fas fa-briefcase me-2"></i>Apply for Business Loan
                                    </a>
                                    <a href="contact-us.php" class="btn btn-outline-light btn-lg px-4 py-3 hover-scale">
                                        <i class="fas fa-phone me-2"></i>Contact Expert
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Trust Indicators -->
<section class="trust-section py-4 bg-gradient-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-6">
                <div class="trust-item hover-lift">
                    <div class="trust-icon-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=60&h=60&fit=crop&crop=center" alt="RBI Approved" class="trust-icon rounded-circle shadow">
                    </div>
                    <h6 class="fw-bold mb-1">RBI Approved</h6>
                    <small class="text-muted">Licensed NBFC</small>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="trust-item hover-lift">
                    <div class="trust-icon-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=60&h=60&fit=crop&crop=center" alt="Quick Processing" class="trust-icon rounded-circle shadow">
                    </div>
                    <h6 class="fw-bold mb-1">24 Hour Processing</h6>
                    <small class="text-muted">Fast Approval</small>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="trust-item hover-lift">
                    <div class="trust-icon-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=60&h=60&fit=crop&crop=center" alt="Best Rates" class="trust-icon rounded-circle shadow">
                    </div>
                    <h6 class="fw-bold mb-1">Best Interest Rates</h6>
                    <small class="text-muted">Starting 7% p.a.</small>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="trust-item hover-lift">
                    <div class="trust-icon-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=60&h=60&fit=crop&crop=center" alt="Digital Process" class="trust-icon rounded-circle shadow">
                    </div>
                    <h6 class="fw-bold mb-1">100% Digital</h6>
                    <small class="text-muted">Paperless Process</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Products Section -->
<section class="products-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-danger mb-3 fade-in-up">Our Loan Products</h2>
            <p class="lead text-muted">Choose from our comprehensive range of financial products designed to meet your every need</p>
        </div>
        
        <!-- Featured Loans -->
        <div class="row g-4 mb-5">
            <?php foreach($featured_loans as $index => $loan): ?>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="product-card-3d h-100" data-aos="zoom-in" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="product-image-container">
                        <img src="<?php echo $loan['image_url']; ?>" alt="<?php echo $loan['name']; ?>" class="product-image">
                        <div class="product-overlay">
                            <div class="product-icon">
                                <i class="<?php echo $loan['icon']; ?> fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="product-content">
                        <h4 class="product-title fw-bold text-danger mb-2"><?php echo $loan['name']; ?></h4>
                        <p class="product-description text-muted mb-3"><?php echo $loan['description']; ?></p>
                        
                        <!-- Key Points -->
                        <div class="key-points mb-3">
                            <?php if(!empty($loan['key_point_1'])): ?>
                            <div class="key-point">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small><?php echo $loan['key_point_1']; ?></small>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($loan['key_point_2'])): ?>
                            <div class="key-point">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small><?php echo $loan['key_point_2']; ?></small>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($loan['key_point_3'])): ?>
                            <div class="key-point">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small><?php echo $loan['key_point_3']; ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="loan-amount-range mb-3">
                            <span class="badge bg-light text-dark">
                                ₹<?php echo number_format($loan['min_amount']); ?> - ₹<?php echo number_format($loan['max_amount']); ?>
                            </span>
                        </div>
                        
                        <div class="product-actions">
                            <a href="apply-loan.php?type=<?php echo urlencode($loan['name']); ?>" class="btn btn-danger btn-sm px-4 pulse-on-hover">
                                <i class="fas fa-file-alt me-1"></i>Apply Now
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm ms-2">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Products -->
        <?php if(!empty($other_loans)): ?>
        <div class="text-center mb-4">
            <h4 class="fw-bold text-danger">Other Loan Products</h4>
        </div>
        <div class="row g-3">
            <?php foreach($other_loans as $index => $loan): ?>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="mini-product-card hover-lift" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <div class="mini-image-container">
                        <img src="<?php echo $loan['image_url']; ?>" alt="<?php echo $loan['name']; ?>" class="mini-image">
                        <div class="mini-overlay">
                            <i class="<?php echo $loan['icon']; ?> fa-lg text-white"></i>
                        </div>
                    </div>
                    <div class="mini-content p-3">
                        <h6 class="fw-bold mb-2"><?php echo $loan['name']; ?></h6>
                        <p class="text-muted small mb-2"><?php echo substr($loan['description'], 0, 50); ?>...</p>
                        <a href="apply-loan.php?type=<?php echo urlencode($loan['name']); ?>" class="btn btn-outline-danger btn-sm btn-block">
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section py-5 bg-gradient-red text-white position-relative overflow-hidden">
    <div class="stats-bg-animation"></div>
    <div class="container position-relative">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-6">
                <div class="stat-item" data-aos="flip-up" data-aos-delay="0">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-rupee-sign fa-2x"></i>
                    </div>
                    <div class="stat-number display-6 fw-bold counter" data-target="26624">0</div>
                    <div class="stat-unit">Cr+</div>
                    <div class="stat-label">Loans Disbursed</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-item" data-aos="flip-up" data-aos-delay="100">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="stat-number display-6 fw-bold counter" data-target="30000">0</div>
                    <div class="stat-unit">+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-item" data-aos="flip-up" data-aos-delay="200">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <div class="stat-number display-6 fw-bold counter" data-target="8000">0</div>
                    <div class="stat-unit"></div>
                    <div class="stat-label">Expert Team</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-item" data-aos="flip-up" data-aos-delay="300">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <div class="stat-number display-6 fw-bold counter" data-target="600">0</div>
                    <div class="stat-unit">+</div>
                    <div class="stat-label">Branches Across India</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- EMI Calculator Section -->
<section id="emi-calculator" class="calculator-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-danger mb-3" data-aos="fade-up">EMI Calculator</h2>
            <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Calculate your monthly EMI instantly and plan your loan</p>
        </div>
        
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="calculator-preview-card p-4 bg-white rounded-3 shadow-lg">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loan Amount</label>
                            <input type="range" class="form-range" id="loanAmount" min="100000" max="10000000" value="1000000" oninput="calculateEMI()">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>₹1L</span>
                                <span id="loanAmountValue" class="fw-bold text-danger">₹10L</span>
                                <span>₹1Cr</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Interest Rate (%)</label>
                            <input type="range" class="form-range" id="interestRate" min="7" max="20" value="10" step="0.1" oninput="calculateEMI()">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>7%</span>
                                <span id="interestValue" class="fw-bold text-danger">10%</span>
                                <span>20%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loan Tenure</label>
                            <input type="range" class="form-range" id="loanTenure" min="1" max="30" value="10" oninput="calculateEMI()">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>1Y</span>
                                <span id="tenureValue" class="fw-bold text-danger">10Y</span>
                                <span>30Y</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="emi-result p-3 bg-danger bg-opacity-10 rounded text-center">
                                <h6 class="text-danger mb-1">Monthly EMI</h6>
                                <div class="display-6 fw-bold text-danger" id="emiAmount">₹13,215</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="emi/calculator.php" class="btn btn-danger px-4">
                            <i class="fas fa-calculator me-2"></i>Advanced Calculator
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="calculator-benefits">
                    <h4 class="fw-bold text-danger mb-4">Why Use Our EMI Calculator?</h4>
                    <div class="benefit-list">
                        <div class="benefit-item d-flex align-items-start mb-3">
                            <div class="benefit-icon me-3">
                                <i class="fas fa-bolt text-danger"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Instant Results</h6>
                                <p class="text-muted mb-0 small">Get your EMI calculation instantly without any delays</p>
                            </div>
                        </div>
                        <div class="benefit-item d-flex align-items-start mb-3">
                            <div class="benefit-icon me-3">
                                <i class="fas fa-chart-pie text-danger"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Visual Breakdown</h6>
                                <p class="text-muted mb-0 small">See detailed breakdown of principal and interest</p>
                            </div>
                        </div>
                        <div class="benefit-item d-flex align-items-start mb-3">
                            <div class="benefit-icon me-3">
                                <i class="fas fa-mobile-alt text-danger"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Mobile Optimized</h6>
                                <p class="text-muted mb-0 small">Works perfectly on all devices and screen sizes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Steps Section -->
<section class="steps-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-danger mb-3" data-aos="fade-up">Get Your Loan in 4 Simple Steps</h2>
            <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Simple and transparent loan process designed for your convenience</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="step-image-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=120&h=120&fit=crop&crop=center" alt="Assessment" class="step-image">
                        <div class="step-number">1</div>
                    </div>
                    <h6 class="fw-bold text-danger mb-2">Application</h6>
                    <p class="text-muted small">Fill our simple online application form in just 2 minutes</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-image-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=120&h=120&fit=crop&crop=center" alt="Verification" class="step-image">
                        <div class="step-number">2</div>
                    </div>
                    <h6 class="fw-bold text-danger mb-2">Verification</h6>
                    <p class="text-muted small">Upload documents for quick digital verification</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-image-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1554224154-26032fbc4d72?w=120&h=120&fit=crop&crop=center" alt="Approval" class="step-image">
                        <div class="step-number">3</div>
                    </div>
                    <h6 class="fw-bold text-danger mb-2">Approval</h6>
                    <p class="text-muted small">Get instant approval notification within 24 hours</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="step-card text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-image-wrapper mb-3">
                        <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=120&h=120&fit=crop&crop=center" alt="Disbursement" class="step-image">
                        <div class="step-number">4</div>
                    </div>
                    <h6 class="fw-bold text-danger mb-2">Disbursement</h6>
                    <p class="text-muted small">Receive funds directly in your bank account</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-gradient-red text-white position-relative overflow-hidden">
    <div class="cta-animation"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7" data-aos="fade-right">
                <h3 class="display-6 fw-bold mb-3">Ready to Get Started?</h3>
                <p class="fs-5 mb-0">Apply for your loan today and get instant approval with competitive interest rates. Our expert team is here to guide you through every step.</p>
            </div>
            <div class="col-lg-4 col-md-5" data-aos="fade-left">
                <div class="d-grid gap-2">
                    <a href="apply-loan.php" class="btn btn-light btn-lg px-4 py-3 hover-scale">
                        <i class="fas fa-file-alt me-2"></i>Apply Now
                    </a>
                    <a href="contact-us.php" class="btn btn-outline-light btn-lg px-4 py-3 hover-scale">
                        <i class="fas fa-phone me-2"></i>Call Expert
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// EMI Calculator Logic
function calculateEMI() {
    const principal = document.getElementById('loanAmount').value;
    const rate = document.getElementById('interestRate').value / 12 / 100;
    const tenure = document.getElementById('loanTenure').value * 12;
    
    const emi = (principal * rate * Math.pow(1 + rate, tenure)) / (Math.pow(1 + rate, tenure) - 1);
    
    document.getElementById('loanAmountValue').textContent = '₹' + formatAmount(principal);
    document.getElementById('interestValue').textContent = document.getElementById('interestRate').value + '%';
    document.getElementById('tenureValue').textContent = document.getElementById('loanTenure').value + 'Y';
    document.getElementById('emiAmount').textContent = '₹' + formatAmount(Math.round(emi));
}

function formatAmount(amount) {
    if (amount >= 10000000) return (amount / 10000000).toFixed(1) + 'Cr';
    if (amount >= 100000) return (amount / 100000).toFixed(1) + 'L';
    return new Intl.NumberFormat('en-IN').format(amount);
}

// Counter Animation
function animateCounter() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 20);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateEMI();
    
    // Trigger counter animation when stats section is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter();
                observer.unobserve(entry.target);
            }
        });
    });
    
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
