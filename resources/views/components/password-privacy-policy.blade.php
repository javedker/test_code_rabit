<!-- Password Policy Modal -->
<div class="modal fade" id="passwordPolicyModal" tabindex="-1" aria-labelledby="passwordPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content password-policy-modal shadow-lg border-0 rounded-4">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <h5 class="modal-title fw-semibold text-dark d-flex align-items-center" id="passwordPolicyModalLabel">
                    <i class="fas fa-shield-alt text-primary me-2 fs-4"></i>
                    Password Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                <p class="text-muted mb-3">Please ensure your password meets the following requirements:</p>
                <ul class="list-group password-policy-list">
                    <li class="list-group-item d-flex align-items-center border-0 bg-transparent ps-0">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <span>Minimum 8 characters long</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 bg-transparent ps-0">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <span>Must include uppercase and lowercase letters</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 bg-transparent ps-0">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <span>Must include at least one number</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 bg-transparent ps-0">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <span>Must include at least one special character (!@#$%^&amp;*…)</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-0 bg-transparent ps-0">
                        <i class="fas fa-times-circle text-danger me-3 fs-5"></i>
                        <span>No spaces allowed</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
