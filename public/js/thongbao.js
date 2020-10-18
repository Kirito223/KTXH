let modelServices = simpleModelService('/thongbao');

modelServices.setCheckedItems("kichhoat-checkbox");

modelServices.generateCheckboxesEvent("kichhoat-checkbox");

modelServices.generateDeleteButtonsEvents("button-delete", "button-confirm-delete", "deleteModal");

modelServices.generateEditButtonsEvent("edit-btn", "confirm-edit-btn", "modelForEditThongbao",
    "edit-input-tieude", "edit-input-ngaybatdau", "edit-input-ngayketthuc", "edit-input-noidung",
    "edit-input-kichhoat", "edit-input-taptin");

modelServices.generateSubmitButtonForCreateEvent("submit-button-for-create", "create-input-tieude",
    "create-input-ngaybatdau", "create-input-ngayketthuc", "create-input-noidung", "create-input-taptin",
    "create-input-kichhoat");

modelServices.generateDetailsButtonsEvent('details-btn', 'modelForDetailsThongbao', 'details-title', 'details-start-day', 'details-end-day', 'details-content', 'details-download-doc', 'details-non-donwnload-doc');
modelServices.generateSendButtonsEvents('send-btn', 'confirm-send-btn', 'sendModal', 'donvinhan-input');

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#show-create-modal").click(function(e) {
        e.preventDefault();
        $("#modelForCreateThongbao").modal('show');
    });

    $(".datepicker").datepicker({
		format: 'dd/mm/yyyy',
		language: 'vi'
	});

    
});



function simpleModelService(urlLink) {
    var URLLINK = urlLink;
	
	var editLabelForTaptin = document.getElementById('edit-label-taptin');
	var editInputForTaptin = document.getElementById('edit-input-taptin');
    editInputForTaptin.addEventListener('change', function(e) {
        e.preventDefault();
        this.style.width = '400px';
        editLabelForTaptin.classList.add('hidden-item');
    })
	
	//  Xem chi tiết thông báo đã gửi

    var sentDetailsBtns = [...document.getElementsByClassName('sent-details-btn')];
    var sentDetailsModal = document.getElementById('sent-details-modal');
    
    for(let i = 0; i < sentDetailsBtns.length; i++) {
        sentDetailsBtns[i].addEventListener('click', function(e) {
            e.preventDefault();
            let id = this.id.split('-')[this.id.split('-').length - 1];
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: URLLINK + "/" + id + '/getThongbaoSentDetails',
                success: function(resData) {
                    if (!$.isEmptyObject(resData.error)) {

                    } else {
                        let data = JSON.parse(resData.success);
                        generateSentDetailsModalContent(data);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr
                        .responseText);
                }
            });
        })
    }
    function generateSentDetailsModalContent(data) {
        let sentDetailsModalBody = sentDetailsModal.querySelector('.modal-body');
        sentDetailsModalBody.innerHTML = "";
        let keys = Object.keys(data);
        keys = keys.filter(i => i.length > 0);
        if(keys.length > 0) {
            sentDetailsModalBody.appendChild(createList(keys));
        } else {
            let h4Element = document.createElement('h4');
            h4Element.innerText = "Không có thông tin đơn vị"
            sentDetailsModalBody.appendChild(h4Element);
        }
        $('#sent-details-modal').modal('show');

        function createList(itemArray) {
            let ulElement = document.createElement('ul');
            ulElement.classList.add('list-group');
            itemArray.forEach(i => {
                let liElement = document.createElement('li');
                liElement.classList.add('list-group-item');
                let h4Element = document.createElement('h4');
                h4Element.innerText = "Đơn vị: " + i
                liElement.appendChild(h4Element);
                if(data[i] !== null && data[i] !== undefined && data[i].length > 0) {
                    liElement.appendChild(createInnerList(data[i]));
                } 
                ulElement.appendChild(liElement);
            })
            return ulElement;
     }

     function createInnerList(itemArr) {
        let ulElement = document.createElement('ul');
        ulElement.classList.add('list-group');
        itemArr.forEach(i => {
            let { tendangnhap, pivot } = i;
            let { isSeen } = pivot;
            let spanElement = document.createElement('span');
            if(isSeen) {
                spanElement.classList.add('badge', 'badge-success');
                spanElement.innerText = 'Đã xem';
            } else {
                spanElement.classList.add('badge', 'badge-info');
                spanElement.innerText = 'Đã gửi';
            }
            let liElement = document.createElement('li');
            liElement.classList.add('list-group-item');
            liElement.innerText = tendangnhap;
            liElement.appendChild(spanElement);
            ulElement.appendChild(liElement);
        });
        return ulElement;
     }
}    
//end chi tiết thông báo đã gửi

    function setOrdersArray(rowsClassName) {
        var rows = document.getElementsByClassName(rowsClassName);
        var nameCount = rowsClassName.split('-')[1].length;
        var ordersArr = [];
        for (let i = 0; i < rows.length; i++) {
            ordersArr.push(rows[i].id.substr(nameCount));
        }
        var ordersArrayInput = document.getElementById('ordersArray');
        ordersArrayInput.value = JSON.stringify(ordersArr);
        console.log(ordersArrayInput.value);
    }

    function setCheckedItems(checkboxesApdungClassName) {
        let checkboxLoaisolieus = document.getElementsByClassName(checkboxesApdungClassName)
        let checkedArr = [];
        for (let i = 0; i < checkboxLoaisolieus.length; i++) {
            if (checkboxLoaisolieus[i].checked) {
                checkedArr.push(checkboxLoaisolieus[i].value);
            }
        }
        let apdungInput = document.getElementById('apdungArray');
        apdungInput.value = checkedArr;
    }

    function generateCheckboxesEvent(checkboxesApdungClassName) {
        let checkboxLoaisolieus = document.getElementsByClassName(checkboxesApdungClassName);
        for (let i = 0; i < checkboxLoaisolieus.length; i++) {
            checkboxLoaisolieus[i].addEventListener('change', function(e) {
                e.preventDefault();
                setCheckedItems(checkboxesApdungClassName);
            });
        }
    }

    function generateDetailsButtonsEvent(detailsBtnClassName, modalId, titleId, startDayId, endDayId, contentId, downloadBtnId, nonDownloadId ) {
        let titleElement = document.getElementById(titleId);
        let startDayElement = document.getElementById(startDayId);
        let endDayElement = document.getElementById(endDayId);
        let contentElement = document.getElementById(contentId);
        let downloadBtnElement = document.getElementById(downloadBtnId);
        let nonDownloadElement = document.getElementById(nonDownloadId);
        let detailsButtons = document.getElementsByClassName(detailsBtnClassName);
        let id;
        for (let i = 0; i < detailsButtons.length; i++) {
            detailsButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $('#' + modalId).modal('show');
                let idArr = this.id.split('-');
                let parentRow = this.closest('tr');
                id = idArr[idArr.length - 1];
                titleElement.innerHTML = parentRow.children[1].innerHTML;
                startDayElement.innerHTML = parentRow.children[2].innerHTML;
                endDayElement.innerHTML = parentRow.children[3].innerHTML;
                contentElement.innerHTML = parentRow.children[4].innerHTML;
                if(parentRow.children[6].innerHTML.length > 0) {
                    let filename = parentRow.children[6].innerHTML.split('-')[1];
                    downloadBtnElement.innerHTML = filename;
                    downloadBtnElement.classList.remove('hidden-item');
                    nonDownloadElement.classList.add('hidden-item');
                    downloadBtnElement.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location = '/downloadtaptinthongbao/' + id;
                    });
                } else {
                    downloadBtnElement.classList.add('hidden-item');
                    nonDownloadElement.classList.remove('hidden-item');
                } 
        });
    }
}
      

    function generateEditButtonsEvent(editBtnClassName, confirmEditButtonId, modalId, ...inputElementsIdArr) {
        var elementsArr = []
        for (let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        let editButtons = document.getElementsByClassName(editBtnClassName);
        let id;
        for (let i = 0; i < editButtons.length; i++) {
            editButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $('#' + modalId).modal('show');
                let idArr = this.id.split('-');
                let parentRow = this.closest('tr');
                id = idArr[idArr.length - 1];
                for (let i = 0; i < elementsArr.length; i++) {
                    let inputType = elementsArr[i].getAttribute('type');
                    if (i == 0) {
                        elementsArr[i].value = parentRow.children[i + 1].children[0].innerHTML;
                        continue;
                    }
                    if (inputType == "text" || elementsArr[i].nodeName == "TEXTAREA") {
                        elementsArr[i].value = parentRow.children[i + 1].innerHTML;
                    } else if (inputType == "checkbox") {
                        elementsArr[i].checked = parentRow.children[i + 1].querySelector("input").checked;
                    } else if (inputType == "file") {
                        editLabelForTaptin.innerText = parentRow.children[i + 1].innerText.length > 0 ? parentRow.children[i + 1].innerText : 'No file chosen';
						editLabelForTaptin.classList.remove('hidden-item');
						editInputForTaptin.style.width = '85px';
                    }
                }
            })
        }
        let confirmEditButton = document.getElementById(confirmEditButtonId);
        var fd = new FormData();
        confirmEditButton.addEventListener("click", function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                if (elementsArr[i].nodeName == "TEXTAREA") {
                    fd.append(inputName, elementsArr[i].value);
                }
                switch (inputType) {
                    case "checkbox":
                        fd.append(inputName, elementsArr[i].checked);
                        break;
                    case "text":
                        fd.append(inputName, elementsArr[i].value);
                        break;
                    case "file":
                        fd.append(inputName, elementsArr[i].files[0]);
                        break;
                }
            }
            for (var pair of fd.entries()) {
                console.log(pair[0]+ ', ' + pair[1]); 
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK + "/" + id,
                data : fd,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-edit");
                    } else {
                        console.log(data.success);
                        window.location.href = URLLINK;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    }

    function generateSubmitButtonForCreateEvent(submitButtonForCreateId, ...inputElementsIdArr) {
        var elementsArr = []
        for (let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        var fd = new FormData();
        console.log(elementsArr);
        let submitButtonForCreate = document.getElementById(submitButtonForCreateId);
        submitButtonForCreate.addEventListener('click', function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                if (elementsArr[i].nodeName == "TEXTAREA") {
                    fd.append(inputName, elementsArr[i].value);
                }
                switch (inputType) {
                    case "checkbox":
                        fd.append(inputName, elementsArr[i].checked);
                        break;
                    case "text":
                        fd.append(inputName, elementsArr[i].value);
                        break;
                    case "file":
                        fd.append(inputName, elementsArr[i].files[0]);
                        break;
                }
            }
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK,
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-create")
                    } else {
                        console.log(data.success);
                        window.location.href = URLLINK;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    }

    function printErrorMsg(msg, printErrorMessageClassName) {

        $("." + printErrorMessageClassName).find("ul").html('');
        $("." + printErrorMessageClassName).css('display', 'block');
        $.each(msg, function(key, value) {
            $("." + printErrorMessageClassName).find("ul").append('<li>' + value + '</li>');
        });
    }



    function generateUpAndDownButtonsEvents(btnMoveUpClassName, btnMoveDownClassName, tbodyId, rowsClassName) {
        var upButtons = document.getElementsByClassName(btnMoveUpClassName);
        let tbodyNode = document.getElementById(tbodyId);
        for (let i = 0; i < upButtons.length; i++) {
            upButtons[i].addEventListener('click', function() {
                let trNode = this.parentElement.parentElement;

                let previousTrNode = trNode.previousElementSibling;
                if (previousTrNode != null) {
                    tbodyNode.insertBefore(trNode, previousTrNode);
                    setOrdersArray(rowsClassName);
                }
            });
        }
        var downButtons = document.getElementsByClassName(btnMoveDownClassName);
        for (let i = 0; i < downButtons.length; i++) {
            downButtons[i].addEventListener('click', function() {
                let trNode = this.parentElement.parentElement;
                let nextTrNode = trNode.nextElementSibling;
                if (nextTrNode != null) {
                    tbodyNode.insertBefore(nextTrNode, trNode);
                    setOrdersArray(rowsClassName);
                }
            });
        }
    }

    function generateDeleteButtonsEvents(btnDeleteClassName, btnConfirmDeleteId, modalId) {
        var deleteButtons = document.getElementsByClassName(btnDeleteClassName);

        for (let i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $('#' + modalId).modal('show');
                let idArr = this.id.split('-');
                let id = idArr[idArr.length - 1];
                var confirmDeleteButton = document.getElementById(btnConfirmDeleteId);

                confirmDeleteButton.addEventListener('click', function sendDeleteInfoAjax(e) {
                    e.preventDefault();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: URLLINK + "/" + id,
						data: {
							"_method": 'DELETE'
						},
                        success: function(data) {
                            if (!$.isEmptyObject(data.error)) {

                            } else {
                                window.location.href = URLLINK;
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr
                                .responseText);
                        }
                    });
                    confirmDeleteButton.removeEventListener("click", sendDeleteInfoAjax);
                })
            })
        }
    }

    function generateSendButtonsEvents(sendBtnClassName, confirmSendBtnId, modalId, donvinhanInputClassName) {
        let sendBtns = [...document.getElementsByClassName(sendBtnClassName)];
        let inputs = [...document.getElementsByClassName(donvinhanInputClassName)];
        for(let i = 0; i< sendBtns.length; i++) {
        sendBtns[i].addEventListener('click', function(e) {
            e.preventDefault();
            $('#' + modalId).modal('show');
            let idArr = this.id.split('-');
            let thongbaoId = idArr[idArr.length - 1];
            let confirmSendBtn = document.getElementById(confirmSendBtnId);
            confirmSendBtn.addEventListener('click', function sendDonvinhanInfoAjax(e) {
                let data = {};
                e.preventDefault();
                for(let i = 0; i < inputs.length; i++) {
                    inputName = inputs[i].getAttribute('name');
                    if(inputs[i].checked) {
                        data[inputName] = inputName in data ? [...data[inputName], inputs[i].value ] : [inputs[i].value];  
                    }
                }
                console.log(data);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: URLLINK + "/" + thongbaoId + "/sendthongbao",
                    data,
                    success: function(data) {
                        if (!$.isEmptyObject(data.error)) {
                            printErrorMsg(data.error, "print-error-msg-on-send")
                        } else {
                            console.log(data.success);
                            window.location.href = URLLINK;
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
                this.removeEventListener("click", sendDonvinhanInfoAjax);
            });
        });
        }
    }

    return {
        setCheckedItems,
        generateCheckboxesEvent,
        generateEditButtonsEvent,
        generateSubmitButtonForCreateEvent,
        generateUpAndDownButtonsEvents,
        generateDeleteButtonsEvents,
        generateDetailsButtonsEvent,
        generateSendButtonsEvents
    }
}