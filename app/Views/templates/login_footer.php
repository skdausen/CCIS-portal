<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url("rsc/bootstrap-5.3.7/js/bootstrap.bundle.min.js") ?>"></script>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>


<!-- Your Custom JS -->
<script src="<?= base_url('rsc/custom_js/login_spinner.js'); ?>"></script>
<script src="<?= base_url('rsc/custom_js/preventBackHistory.js'); ?>"></script>
<script src="<?= base_url('rsc/custom_js/calendar.js'); ?>"></script>

<!-- 🔑 Your Select2 Dropdown Script Goes Here (AFTER the scripts above) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('#addSubjectSelect', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: "Search or select subject..."
    });
});
</script>

    </div>
</body>
</html>
