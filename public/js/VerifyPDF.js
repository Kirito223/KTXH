var urlFile;
$(document).ready(function () {
  intEvent();
});

function intEvent() {
  let btnUpload = document.getElementById("btnUpload");
  btnUpload.addEventListener("click", function () {
    var data = new FormData();
    let file = document.getElementById("btnImport").files[0];
    let fileName = file.name;
    data.append("file", file);
    data.append("fileName", fileName);
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      url: "uploadFile",
      type: "POST",
      data: data,
      contentType: false,
      processData: false,
      success: function (response) {
        urlFile = "http://" + window.location.host + "/upload/" + response;
        $("#viewFile").attr("src", urlFile);
        console.log(response);
      },
    });
  });

  let btnVerify = document.getElementById("btnVerify");
  btnVerify.addEventListener("click", function () {
    // if (urlFile == undefined || urlFile == "" || urlFile == null) {
    //   Swal.fire("Chưa tải file lên", "Cảnh báo", "warning");
    // } else {

    // }
    var form = new FormData();
    form.append("method", "GetVerifyPDF");
    form.append(
      "url",
      "https://dichvucong.binhdinh.gov.vn/tttl/52/vanbanphapluat/2020/05/So%20Lao%20dong%20%20TBXH%20%20Quyet%20dinh%20so%201790QDUBND%20ngay%201352020%20%20Danh%20muc%20TTHC_1589501869.pdf"
    );

    var settings = {
      url: "https://api.lihanet.com",
      method: "POST",
      timeout: 0,
      processData: false,
      mimeType: "multipart/form-data",
      contentType: false,
      data: form,
    };

    $.ajax(settings).done(function (response) {
      console.log(response);
    });
  });
}
