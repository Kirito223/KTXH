$(document).ready(function() {
    $('#show-create-modal').click(function() {
        $('#modelForCreateTaikhoan').modal('show');
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let createInputNhomquyenElements = document.getElementsByClassName('create-input-nhomquyen');
    createInputNhomquyenElements = [...createInputNhomquyenElements];
    let createInputNhomquyenIds = createInputNhomquyenElements.map(function(item) {
        return item.id;
    });
    let editInputNhomquyenElements = document.getElementsByClassName('edit-input-nhomquyen');
    editInputNhomquyenElements = [...editInputNhomquyenElements];
    let editInputNhomquyenIds = editInputNhomquyenElements.map(function(item) {
        return item.id;
    })

    let modelServices = simpleModelService('/taikhoan');
    modelServices.generateSubmitButtonForCreateEvent('submit-button-for-create', 'create-input-tendangnhap',
    'create-input-email', 'create-input-matkhau', 'create-input-tentaikhoan', 'create-input-ho', 'create-input-ten',
        'create-input-kichhoat', ...createInputNhomquyenIds);
    modelServices.generateEditButtonsEvent('edit-btn', 'confirm-edit-btn', 'modelForEditTaikhoan',
        'edit-input-tendangnhap','edit-input-email' ,'edit-input-matkhau', 'edit-input-tentaikhoan', 'edit-input-ho',
        'edit-input-ten', ...editInputNhomquyenIds, 'edit-input-kichhoat');
    modelServices.generateDeleteButtonsEvents('delete-btn', 'button-confirm-delete', 'deleteModal');
    modelServices.generateCheckboxesEvent('taikhoan-checkbox');
});




function simpleModelService(urlLink) {
    var URLLINK = urlLink;

    function setOrdersArray(rowsClassName) {
        var rows = document.getElementsByClassName(rowsClassName);
        var nameCount = rowsClassName.split('-')[1].length;
        var ordersArr = [];
        for (let i = 0; i < rows.length; i++) {
            ordersArr.push(rows[i].id.substr(nameCount));
        }
        var ordersArrayInput = document.getElementById('ordersArray');
        ordersArrayInput.value = JSON.stringify(ordersArr);
    }

    function setCheckedItems(checkboxesKichhoatClassName) {
        let checkboxTaikhoans = document.getElementsByClassName(checkboxesKichhoatClassName)
        let checkedArr = [];
        for (let i = 0; i < checkboxTaikhoans.length; i++) {
            if (checkboxTaikhoans[i].checked) {
                checkedArr.push(checkboxTaikhoans[i].value);
            }
        }
        let kichhoatInput = document.getElementById('kichhoatArray');
        kichhoatInput.value = checkedArr;
    }

    function generateCheckboxesEvent(checkboxesKichhoatClassName) {
        let checkboxTaikhoans = document.getElementsByClassName(checkboxesKichhoatClassName);
        for (let i = 0; i < checkboxTaikhoans.length; i++) {
            checkboxTaikhoans[i].addEventListener('change', function(e) {
                e.preventDefault();
                setCheckedItems(checkboxesKichhoatClassName);
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
                id = idArr[idArr.length - 1];
                let parentRow = this.closest('tr');

                let arrIdOfNhomquyen = [...parentRow.children[5].querySelectorAll('li')].map(function(item) {
                    return item.innerHTML;
                });
                let ignoreVal = 0;
                let hasNhomquyenInputBefore = false;
                for (let i = 0; i < elementsArr.length; i++) {
                    let inputType = elementsArr[i].getAttribute('type');
                    let inputName = elementsArr[i].getAttribute('name');
                    switch (inputName) {
                        case 'nhomquyen':
                            if(hasNhomquyenInputBefore) ignoreVal++;
                            elementsArr[i].checked = arrIdOfNhomquyen.includes(elementsArr[i].value);
                            hasNhomquyenInputBefore = true;
                            break;
                        case 'kichhoat':
                            elementsArr[i].checked = parentRow.children[i + 1 - ignoreVal].querySelector('input').checked;
                            break;
                        case 'matkhau': 
                            ignoreVal++;
                            break;
                        default:
                            elementsArr[i].value = parentRow.children[i - ignoreVal].innerHTML;
                            break;
                    }
                }
            })
        }
        let confirmEditButton = document.getElementById(confirmEditButtonId);
        confirmEditButton.addEventListener("click", function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                switch (inputName) {
                    case 'kichhoat':
                        data[inputName] = elementsArr[i].checked;
                        break;
                    case 'nhomquyen':
                        if (elementsArr[i].checked) {
                            data[inputName] = inputName in data ? [...data[inputName], elementsArr[i].value] : [
                                elementsArr[i].value
                            ];
                        }
                        break;
                    default:
                        data[inputName] = elementsArr[i].value;
                }
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK + "/" + id,
                data : {
					...data,
					"_method" : 'PUT'
				},
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
        let submitButtonForCreate = document.getElementById(submitButtonForCreateId);
        submitButtonForCreate.addEventListener('click', function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                switch (inputName) {
                    case 'kichhoat':
                        data[inputName] = elementsArr[i].checked;
                        break;
                    case 'nhomquyen':
                        if (elementsArr[i].checked) {
                            data[inputName] = inputName in data ? [...data[inputName], elementsArr[i].value] : [
                                elementsArr[i].value
                            ];
                        }
                        break;
                    default:
                        data[inputName] = elementsArr[i].value;
                }
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK,
                data,
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
                let idArr = this.id.split('-');
                let id = idArr[idArr.length - 1];
                $('#' + modalId).modal('show');
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
							"_method" : 'DELETE'
						},
                        success: function(data) {
                            if (!$.isEmptyObject(data.error)) {
                                alert(data.error);
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

    return {
        setCheckedItems,
        generateCheckboxesEvent,
        generateEditButtonsEvent,
        generateSubmitButtonForCreateEvent,
        generateUpAndDownButtonsEvents,
        generateDeleteButtonsEvents
    }
}