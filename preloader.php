<style>  #preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #fff; /* background white */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 99999; /* sab upar */
}

.loader {
  border: 8px solid #f3f3f3;
  border-top: 8px solid rgb(149, 31, 51);
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Extra CSS */
#preloader.fade-out {
  opacity: 0;
  transition: opacity 0.6s ease;
  pointer-events: none;
}</style>
<body>
    <!-- Preloader -->
<div id="preloader">
  <div class="loader"></div>
</div>
    
</body>
<script>
  window.addEventListener("load", function () {
    let preloader = document.getElementById("preloader");
    // Fade-out class add karo
    preloader.classList.add("fade-out");
    // Aur 600ms baad completely hide karo
    setTimeout(() => {
      preloader.style.display = "none";
    }, 900);
  });
</script>
