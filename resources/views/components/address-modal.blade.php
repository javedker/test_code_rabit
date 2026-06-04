<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content track-area tptrack__product">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Add New Delivery Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="post" id="store-address">
                <div class="modal-body tptrack__content grey-bg-3">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control tptrack__custom_input" name="name"
                            placeholder="Enter name" id="fullName" required
                            oninput="validateField(this,'string','errFullName',50,true)">
                        <small id="errFullName" class="form-text"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alternative Number</label>
                        <input type="number" class="form-control tptrack__custom_input"
                            oninput="validateMobileNumber(this)" placeholder="Enter alternative number"
                            name="alternative_number" id="alternativeNumber">
                        <small id="errAlternativeNumber" class="form-text"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control tptrack__custom_input" name="address"
                            placeholder="Enter address" id="address" required
                            oninput="validateField(this,'all','errAddress',100,true)">
                        <small id="errAddress" class="form-text"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Street <span class="text-danger">*</span></label>
                        <input type="text" class="form-control tptrack__custom_input" name="street"
                            placeholder="Enter street" id="street" required
                            oninput="validateField(this,'all','errStreet',100,true)">
                        <small id="errStreet" class="form-text"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                        <input type="number" class="form-control tptrack__custom_input" name="postal_code"
                            placeholder="Enter postal code" id="postalCode" required
                            oninput="validateField(this,'numeric','errPostalCode',10,false)">
                        <small id="errPostalCode" class="form-text"></small>
                    </div>

                    <div class="mb-3 country-select">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <select class="" id="citySelect" required name="city_id"
                            oninput="validateField(this,'all','errCity',50,true)">
                            <option value="">Select city</option>
                        </select>
                        <small id="errCity" class="form-text"></small>
                    </div>
                </div>


                <div class="">
                    <button type="submit" class="btn tptrack__submition_address">Save
                        Address
                        <i class="fal fa-long-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>