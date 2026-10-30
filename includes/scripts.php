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

<!-- Dark Mode Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('darkModeToggle');
  if (!toggleBtn) return;

  // Apply saved preference
  const prefersDark = localStorage.getItem('theme') === 'dark';
  if (prefersDark) {
    document.body.classList.add('dark-mode');
    toggleBtn.innerHTML = '☀️';
  }

  toggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    toggleBtn.innerHTML = isDark ? '☀️' : '🌓';
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  });
});
</script>
