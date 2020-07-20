$(document).ready(() => {
    if ($("#marketing").length) {
        initEvent();
    }
});
function initEvent() {
    $("#btnSend").on("click", () => {
        axios
            .post("SendMail", {
                name: $("#name").val(),
                email: $("#email").val(),
                content: $("#content").val()
            })
            .then(res => {
                let success = res.data;
                if (success["code"] == "true") {
                    Swal.fire("Đã gửi email", "Email đã được gửi", "warning");
                } else {
                    Swal.fire(
                        "Không gửi được email",
                        "Không gửi được email vui lòng kiểm tra lại",
                        "error"
                    );
                }
            })
            .catch(err => {
                console.log(err);
            });
    });

    $("#btnSendSMS").on("click", () => {
        axios
            .post("SendSMS", {
                msg: $("#msg").val()
            })
            .then(res => {
                alert("ok");
            })
            .catch(err => {
                console.log(err);
            });
    });
}
