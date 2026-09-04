      </main><!-- /.admin-content-area -->

      <!-- Consistent Admin Footer -->
      <footer class="bg-white border-top py-3 px-3 px-lg-4 mt-auto">
        <div class="d-flex flex-wrap justify-content-between align-items-center text-muted small gap-2">
          <div>
            &copy; <?= date('Y') ?> <strong class="text-navy">Bluoilz Skincare</strong> &bull; Administrative Operations Portal
          </div>
          <div class="d-flex align-items-center gap-3">
            <span class="d-none d-sm-inline text-muted" style="font-size: 0.75rem;">
              <i class="bi bi-shield-check text-gold me-1"></i>Encrypted Session
            </span>
            <a href="logout.php" class="text-decoration-none text-danger fw-semibold" onclick="return confirm('Are you sure you want to sign out?');">
              <i class="bi bi-box-arrow-right me-1"></i>Sign Out
            </a>
          </div>
        </div>
      </footer>

    </div><!-- /.admin-main-wrapper -->
  </div><!-- /.admin-layout -->

  <!-- Bootstrap 5 Bundle JS (includes Popper) via CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
