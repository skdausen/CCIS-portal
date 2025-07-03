<!-- footer.php    -->

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
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

    <script src="<?= base_url("rsc/bootstrap-5.3.7/js/bootstrap.bundle.min.js") ?>"></script>
    <script src="<?php echo base_url("rsc/custom_js/preventBackHistory.js"); ?>"></script>

</body>
</html>