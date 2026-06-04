$(document).ready(function () {
    $('#add-to-compare').on('click', function () {
        let spinner = $('#add-to-compare-spinner');
        let slug = $(this).data('slug')

        $('#add-to-compare').hide()
        spinner.show()
        addToCompare(slug, res => {
            spinner.hide()
            $('#add-to-compare').show()
            if (!res.error) {
                toast.success(res.message)
            } else {
                toast.error(res.message)
            }
        })

    })

    $('.remove-from-compare').on('click', function () {
        let slug = $(this).data('slug')
        let removeCompareDiv = $(this)
        removeCompareDiv.hide()
        removeItemFromCompare(slug, res => {
            removeCompareDiv.show()
            if (!res.error) {
                location.reload()
                toast.success(res.message)
            } else {
                toast.error(res.message)
            }
        })
    })
})


