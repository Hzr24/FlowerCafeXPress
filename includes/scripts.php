<!-- JS FILES -->
<script>
  window.BASE_PATH = '<?= $base_path ?? "" ?>';
</script>
<script src="<?= $base_path ?>js/jquery-3.2.1.min.js"></script>
<script src="<?= $base_path ?>js/popper.min.js"></script>
<script src="<?= $base_path ?>js/bootstrap.min.js"></script>

<!-- PLUGINS -->
<script src="<?= $base_path ?>js/jquery.superslides.min.js"></script>
<script src="<?= $base_path ?>js/images-loded.min.js"></script>
<script src="<?= $base_path ?>js/isotope.min.js"></script>
<script src="<?= $base_path ?>js/baguetteBox.min.js"></script>
<script src="<?= $base_path ?>js/form-validator.min.js"></script>
<script src="<?= $base_path ?>js/contact-form-script.js"></script>
<script src="<?= $base_path ?>js/custom.js"></script>

<!-- Auth logic -->
<script src="<?= $base_path ?>js/auth.js"></script>

<!-- Global Theme & UI Enhancements -->
<script>
(function() {
  // 1. Theme initialization & toggle
  const prefersDark = localStorage.getItem('theme') === 'dark';
  const toggleBtn = document.getElementById('darkModeToggle');
  const iconSpan = document.getElementById('darkModeIcon');

  function updateThemeUI(isDark) {
    if (isDark) {
      document.body.classList.add('dark-mode');
      if (iconSpan) iconSpan.textContent = '☀️';
      if (toggleBtn) toggleBtn.setAttribute('title', 'Switch to Light Mode');
    } else {
      document.body.classList.remove('dark-mode');
      if (iconSpan) iconSpan.textContent = '🌙';
      if (toggleBtn) toggleBtn.setAttribute('title', 'Switch to Dark Mode');
    }
  }

  if (prefersDark) {
    updateThemeUI(true);
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const isDark = document.body.classList.toggle('dark-mode');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      updateThemeUI(isDark);
    });
  }

  // 2. Navbar glassmorphic scroll effect
  const navbar = document.querySelector('.top-navbar .navbar');
  const topNavContainer = document.querySelector('.top-navbar');
  if (navbar) {
    const handleScroll = function() {
      if (window.scrollY > 30) {
        navbar.classList.add('scrolled');
        if (topNavContainer) topNavContainer.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
        if (topNavContainer) topNavContainer.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  // 3. Initialize Bootstrap Tooltips if available
  if (window.jQuery && jQuery().tooltip) {
    jQuery('[data-toggle="tooltip"]').tooltip();
  }
})();
</script>
