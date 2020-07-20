$(document).ready(function() {
    $('#show-reset-modal').click(function() {
        $('#resetPasswordModal').modal('show');
    });

    $('#show-change-modal').click(function() {
        $('#changeInfoModal').modal('show');
    });

    generateSubmitButtonForResetPasswordEvent('button-confirm-reset', 'reset-input-id' ,'reset-input-password', 'confirm-input-password');
    generateSubmitButtonForChangeInfoEvent('button-confirm-changeinfo', 'change-input-id', 'change-input-tentaikhoan', 'change-input-email', 'change-input-ho', 'change-input-ten');
})

function printErrorMsg(msg, printErrorMessageClassName) {

$("." + printErrorMessageClassName).find("ul").html('');
$("." + printErrorMessageClassName).css('display', 'block');
$.each(msg, function(key, value) {
    $("." + printErrorMessageClassName).find("ul").append('<li>' + value + '</li>');
});
}

function generateSubmitButtonForResetPasswordEvent(submitButtonForResetId, resetInputIdId ,resetPasswordInputId, confirmResetPasswordInputId) {
        let submitButtonForReset = document.getElementById(submitButtonForResetId);
        let resetInputId = document.getElementById(resetInputIdId);
        let resetPasswordInput = document.getElementById(resetPasswordInputId);
        let confirmResetPasswordInput = document.getElementById(confirmResetPasswordInputId);
        let id = resetInputId.value;
        submitButtonForReset.addEventListener('click', function(e) {
            e.preventDefault();
            if(resetPasswordInput.value != confirmResetPasswordInput.value) {
                printErrorMsg(["Mật khẩu xác nhận không khớp"], 'print-error-msg-on-reset-password');
            } else {
                let inputName = resetPasswordInput.getAttribute('name');
                let data = {};
                data[inputName] = resetPasswordInput.value;
                $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/thaydoimatkhau/' + id ,
                data,
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-reset-password")
                    } else {
                        console.log(data.success);
                        window.location.href = '/quanlytaikhoan';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        });
    }

    function generateSubmitButtonForChangeInfoEvent(submitButtonForChangeId, changeInputIdId ,...inputElementsIdArr) {
        let submitButtonForChange = document.getElementById(submitButtonForChangeId);
        let changeInputId = document.getElementById(changeInputIdId);
        let id = changeInputId.value;
        var elementsArr = [];
        for (let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        submitButtonForChange.addEventListener('click', function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                data[inputName] = elementsArr[i].value;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/thaydoithongtintaikhoan/' + id ,
                data,
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-change-info")
                    } else {
                        console.log(data.success);
                        window.location.href = '/quanlytaikhoan';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
        });
    }