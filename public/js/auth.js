let signInSpinner = $('#sign-in-spinner');
let signInSubmit = $('#sign-in-submit');
let signUpSpinner = $('#sign-up-spinner');
let signUpSubmit = $('#sign-up-submit');


$('#sign-in-form').on('submit', function (e) {
    e.preventDefault();
    signInSubmit.hide();
    signInSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('sign-in'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            // signInSpinner.hide();
            // signInSubmit.show();
            if (!response.error) {
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            signInSpinner.hide();
            signInSubmit.show();

            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});

$('#mobile-form').on('submit', function (e) {
    e.preventDefault();
    signUpSubmit.hide();
    signUpSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('send/otp'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            // signUpSpinner.hide();
            // signUpSubmit.show();
            if (!response.error) {
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            signUpSpinner.hide();
            signUpSubmit.show();

            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});
$('#verify-form').on('submit', function (e) {
    e.preventDefault();
    signUpSubmit.hide();
    signUpSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('verify'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            // signUpSpinner.hide();
            // signUpSubmit.show();
            if (!response.error) {
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            signUpSpinner.hide();
            signUpSubmit.show();

            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});
$('#sign-up-form').on('submit', function (e) {
    e.preventDefault();
    signUpSubmit.hide();
    signUpSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('sign-up'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            if (!response.error) {
                // signUpSpinner.hide();
                // signUpSubmit.show();
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                signUpSpinner.hide();
                signUpSubmit.show();
                toast.error(response.message);
            }
        },
        error(xhr) {
            signUpSpinner.hide();
            signUpSubmit.show();
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});

$('#forgot-password-form').on('submit', function (e) {
    e.preventDefault();
    signUpSubmit.hide();
    signUpSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('forgot-password'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            signUpSpinner.hide();
            signUpSubmit.show();
            if (!response.error) {
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            signUpSpinner.hide();
            signUpSubmit.show();

            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});
$('#new-password-form').on('submit', function (e) {
    e.preventDefault();
    signUpSubmit.hide();
    signUpSpinner.show();

    let formdata = new FormData(this);
    $.ajax({
        url: route('new-password'),
        method: 'POST',
        headers: headers(),
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        success(response) {
            // restore UI
            signUpSpinner.hide();
            signUpSubmit.show();
            if (!response.error) {
                toast.success(response.message)
                setTimeout(() => {
                    if (response.data.reload) {
                        location.reload()
                    }
                    if (response.data.redirect) {
                        location.href = response.data.redirect
                    }
                }, 2000)

            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            signUpSpinner.hide();
            signUpSubmit.show();

            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }

            toast.error(err.message || 'An error occurred');
        }
    });
});


$('#resend-otp').on('click', function (e) {
    e.preventDefault()
    $('#resend-otp').hide();
    $('#resend-otp-spinner').show()
    $.ajax({
        url: route('send/otp'),
        method: 'POST',
        headers: headers(),
        data: {
            mobile: $(this).data('mobile')
        },
        success(response) {
            $('#resend-otp').show();
            $('#resend-otp-spinner').hide()
            if (!response.error) {
                toast.success(response.message)
            } else {
                toast.error(response.message);
            }
        },
        error(xhr) {
            $('#resend-otp').show();
            $('#resend-otp-spinner').hide()
            let err = {};
            try { err = JSON.parse(xhr.responseText); }
            catch (e) { err.message = 'Unexpected error'; }
            toast.error(err.message || 'An error occurred');
        }
    });
})