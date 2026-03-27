
<?php
include 'navbar.php';

include 'header.php';
include 'preloader.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cloth Nova Showcase</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
<style>
/* Global Scrollbar */
::-webkit-scrollbar{
    width: 0.3rem;
}
::-webkit-scrollbar-track{ background-color: #F2F2F2; }
::-webkit-scrollbar-thumb{ background-color: rgb(129,22,40); }

/* Glass Header */

/* Glass Header */
header {
    position:sticky;
 
    width: 100%;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.25); /* thoda transparent */
    backdrop-filter: blur(8px); /* frosted glass effect */
    -webkit-backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1); /* subtle shadow */
}
header {
  position: sticky;
  top: 0;
  z-index: 1030; /* upar rahe sab ke */
 background: linear-gradient(to right, #ffe6f0, #fff0e6);
}
/* Ensure content below header doesn't hide behind it */
body {
    padding-top: 70px; /* adjust according to header height */
  
}

/* Links & Icons remain same */
header a {
    text-decoration: none;
    color: #111;
}



/* Hero Section */
body, html{margin:0; padding:0; font-family:Arial, sans-serif;}
.hero{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #ff0077, #ff3333);
    position: relative;
    overflow:hidden;
    padding-top:80px; /* header offset */
}
.container-hero{
    display:flex;
    justify-content:space-between;
    align-items:center;
    width:90%;
    max-width:1200px;
    position: relative;
}

/* Product Image */
.product{
    width:350px;
    opacity:0;
    animation: slideUp 1.5s ease-out forwards, float 4s ease-in-out infinite 2s;
    transition: transform 0.4s ease;
}
.product:hover{
    transform: scale(1.08) rotate(3deg);
}
@keyframes slideUp{ from{ transform: translateY(150px); opacity:0;} to{ transform: translateY(0); opacity:1;} }
@keyframes float{ 0%,100%{ transform: translateY(0) scale(1);} 50%{ transform: translateY(-12px) scale(1.02);} }

/* Text */
.left-text{ flex:1; font-size:2rem; color:#fff; opacity:0; animation: fadeIn 1.8s ease forwards 1.2s; }
.right-text{ flex:1; font-size:1.1rem; line-height:1.6; color:#f9f9f9; opacity:0; animation: fadeIn 1.8s ease forwards 1.4s; }
@keyframes fadeIn{ from{ opacity:0; transform: translateY(40px);} to{ opacity:1; transform: translateY(0);} }

/* Bounce Text */
.bounce-container{
    position: absolute;
    top: 8%;
    left:50%;
    transform:translateX(-50%);
    display:flex;
    gap:12px;
    font-size:4.5rem;
    font-weight:bold;
    color:gold;
    text-shadow:2px 2px 6px rgba(0,0,0,0.4);
    perspective:800px;
    opacity:0;
    animation: slideBounce 1.5s ease-out forwards;
}
.bounce-container span{
    display:inline-block;
    transform-style: preserve-3d;
    animation: bounce 1s infinite;
}
.bounce-container span:nth-child(1){ animation-delay:0s; }
.bounce-container span:nth-child(2){ animation-delay:0.1s; }
.bounce-container span:nth-child(3){ animation-delay:0.2s; }
.bounce-container span:nth-child(4){ animation-delay:0.3s; }
.bounce-container span:nth-child(5){ animation-delay:0.4s; }
.bounce-container span:nth-child(6){ animation-delay:0.5s; }

@keyframes slideBounce{ from{ transform: translate(-50%,200px); opacity:0; } to{ transform: translate(-50%,0); opacity:1; } }
@keyframes bounce{ 0%,100%{ transform: translateY(0) rotateX(0); } 50%{ transform: translateY(-18px) rotateX(20deg); } }

/* Cracked Line */
.cracked-line{
    position:absolute;
    top:18%;
    left:50%;
    transform:translateX(-50%);
    width:280px;
    height:5px;
    background: repeating-linear-gradient(to right, gold, gold 14px, transparent 14px, transparent 24px);
    box-shadow:0 2px 8px rgba(0,0,0,0.5);
    animation: crackAnim 1.5s ease-out forwards 1.2s;
    opacity:0;
}
@keyframes crackAnim{ from{ transform:translateX(-50%) scaleX(0); opacity:0; } to{ transform:translateX(-50%) scaleX(1); opacity:1; } }

</style>
</head>
<body>
    

<!-- Glass Header -->


<!-- Hero Section -->
<!-- Hero Section -->
<section class="hero">
   <div class="container-hero">
<!-- SALE TAB -->





   <div class="left-text text-center" style="width: 400px;">
       <div class="alert alert-danger mb-3" 
            style="background: transparent; border:none; color: black; font-size: 26px;">
          <span style="color: gold; font-weight:800; ">Flash Sale is Live!</span> <br>
          <small style="font-size: 25px; font-weight: 800;">Sale ends in <span id="timer" ></span></small>
       </div>
       <h2 class="mt-3" style="margin-bottom: 350px;">Amazing 3D Product Showcase</h2>
   </div>

        <img src="three2.png" class="product me-3" 
             style="height: 500px; width: 600px; margin-bottom: 100px;" 
             alt="Product">

        <div class="right-text"  style="margin-bottom: 200px;">
            <h2 style="color: gold; font-weight: 900; font-size: 26px;">FASHION OF YEAR</h2>
            <h5>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                Nulla facilisi. Integer eget eros vel elit tincidunt ullamcorper at vitae risus. 
                Suspendisse potenti.
            </h5>
        </div>

        <div class="bounce-container">
            <span>B</span><span>O</span><span>U</span>
            <span>N</span><span>C</span><span>E</span>
        </div>
        <div class="cracked-line"></div>
    </div>
</section>


</body>
</html>

</style>
<!-- SALE TAB -->
<div id="saleTab">
  <div id="saleHandle">
    <span>upto 50% discount</span>
  </div>
  <div id="saleContent">
    <h2>🔥 Big Sale is Live!</h2>
    <p>Flat 50% OFF on all products.</p>
    <p>Hurry up! Ends in:</p>
    <div id="saleTimer"></div>
  </div>
</div>

<style>
  /* Sidebar */
  #saleTab {
    position: fixed;
    top: 100px;
    right: -250px; /* hidden */
    width: 250px;
    height: 250px;
    background: linear-gradient(135deg, #ff0077, #ff3333);
    color: white;
    border-radius: 12px 0 0 12px;
    box-shadow: -4px 4px 12px rgba(0,0,0,0.3);
    transition: right 0.5s ease;
    z-index: 5000;
  }

  #saleTab.open {
    right: 0;
  }

  /* Handle = prchi */
  #saleHandle {
    position: absolute;
    top: 0;
    left: -40px; /* sirf 40px ki prchi bahar dikh rahi */
    width: 40px;
    height: 100%;
    background: gold;
    color: black;
    font-weight: bold;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    writing-mode: vertical-rl;  /* vertical likhne ke liye */
    text-orientation: mixed;
    cursor: pointer;
    border-radius: 8px 0 0 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  }

  #saleContent {
    padding: 20px;
    margin-top: 10px;
  }
</style>

<script>
  const saleTab = document.getElementById("saleTab");
  const saleHandle = document.getElementById("saleHandle");

  saleHandle.addEventListener("click", () => {
    saleTab.classList.toggle("open");
  });

  // Countdown Timer (20 din)
  const endDate = new Date();
  endDate.setDate(endDate.getDate() + 20);

  setInterval(function () {
    const now = new Date().getTime();
    const distance = endDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("saleTimer").innerHTML =
      `${days}d ${hours}h ${minutes}m ${seconds}s`;
  }, 1000);
</script>
<section class="aid-section">
  <div class="container text-center">
    <h1 class="animated-title">Get Instant Aid & Offers 🎁</h1>
    <p class="animated-subtitle">Download our app and enjoy exclusive benefits!</p>

    <div class="download-buttons">
      <!-- Playstore Button -->
      <a href="#" class="btn-store">
        <div class="btn-icon"><i class="fab fa-google-play"></i></div>
        <div class="btn-text">
          <small>Get it on</small><br>
          <strong>Google Play</strong>
        </div>
      </a>

      <!-- App Store Button -->
      <a href="#" class="btn-store">
        <div class="btn-icon"><i class="fab fa-apple"></i></div>
        <div class="btn-text">
          <small>Download on the</small><br>
          <strong>App Store</strong>
        </div>
      </a>
    </div>
  </div>
  
</section>
<style>.aid-section {
  background: linear-gradient(135deg, #fffbe6, #fff0e6);
  padding: 80px 20px;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  position: relative;
  overflow: hidden;
}

.animated-title {
  font-size: 2.5rem;
  background: linear-gradient(90deg, gold, orange);
  -webkit-background-clip: text;
  color: transparent;
  animation: slideInDown 1s ease-out;
}

.animated-subtitle {
  font-size: 1.2rem;
  color: #444;
  margin-bottom: 30px;
  animation: fadeIn 2s ease-in;
}

.btn-store {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #000;
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 14px;
  transition: transform 0.3s;
}
.btn-store:hover {
  transform: scale(1.05);
}

.btn-icon i {
  font-size: 24px;
}

@keyframes slideInDown {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>