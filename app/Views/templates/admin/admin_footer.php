<!-- admin_footer.php -->

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Logout</h5>
      </div>
      <div class="modal-body">
        Are you sure you want to log out?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-danger">Confirm Logout</a>
      </div>
    </div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<!-- Success Modal -->  
<div class="modal fade" id="successModal" tabindex="-1" data-success-message="<?= esc(session()->getFlashdata('success')) ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<!-- Bootstrap JS -->
<script src="<?= base_url("rsc/bootstrap-5.3.7/js/bootstrap.bundle.min.js") ?>"></script>

<!-- Prevent back history script -->
<script src="<?= base_url("rsc/custom_js/preventBackHistory.js") ?>"></script>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<!-- Your calendar logic -->
<script src="<?= base_url('rsc/custom_js/calendar.js') ?>"></script>

<!-- Event Modal JS -->
<script src="<?= base_url('rsc/custom_js/eventModal.js') ?>"></script>

<!--View, Search, & Filter Users JS -->
<script src="<?= base_url('rsc/custom_js/users.js') ?>"></script>

<!-- Success Modal JS -->
<script src="<?= base_url('rsc/custom_js/successModal.js') ?>"></script>

</body>
</html>
