$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#show-create-modal').click(function() {
        $('#modelForCreateNhomquyen').modal('show');
    });

    let createInputQuyenElements = document.getElementsByClassName('create-input-quyen');
    createInputQuyenElements = [...createInputQuyenElements];
    let createInputQuyenIds = createInputQuyenElements.map(function(item) {
        return item.id;
    });

    let editInputQuyenElements = document.getElementsByClassName('edit-input-quyen');
    editInputQuyenElements = [...editInputQuyenElements];
    let editInputQuyenIds = editInputQuyenElements.map(function(item) {
        return item.id;
    });

    let modelServices = simpleModelService('/nhomquyen');
    modelServices.generateSubmitButtonForCreateEvent('submit-button-for-create', 'create-input-tennhomquyen',
        'create-input-mota', 'create-input-kichhoat' ,...createInputQuyenIds);
    modelServices.generateCheckboxesEvent('nhomquyen-checkbox');
    modelServices.generateEditButtonsEvent('edit-btn', 'confirm-edit-btn', 'modelForEditNhomquyen', 'edit-input-tennhomquyen', 'edit-input-mota', ...editInputQuyenIds, 'edit-input-kichhoat');
    modelServices.generateDeleteButtonsEvents('delete-btn', 'button-confirm-delete', 'deleteModal');
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

    function setCheckedItems(checkboxesNhomquyenClassName) {
        let checkboxNhomquyens = document.getElementsByClassName(checkboxesNhomquyenClassName)
        let checkedArr = [];
        for (let i = 0; i < checkboxNhomquyens.length; i++) {
            if (checkboxNhomquyens[i].checked) {
                checkedArr.push(checkboxNhomquyens[i].value);
            }
        }
        let kichhoatInput = document.getElementById('kichhoatArray');
        kichhoatInput.value = checkedArr;
    }

    function generateCheckboxesEvent(checkboxesNhomquyenClassName) {
        let checkboxNhomquyens = document.getElementsByClassName(checkboxesNhomquyenClassName);
        for (let i = 0; i < checkboxNhomquyens.length; i++) {
            checkboxNhomquyens[i].addEventListener('change', function(e) {
                e.preventDefault();
                setCheckedItems(checkboxesNhomquyenClassName);
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
                let arrIdOfQuyen = [...parentRow.children[2].querySelectorAll('li')].map(function(item) {
                    return item.innerHTML;
                });
                let ignoreVal = 0;
                let hasQuyenInputBefore = false;
                for (let i = 0; i < elementsArr.length; i++) {
                    let inputType = elementsArr[i].getAttribute('type');
                    let inputName = elementsArr[i].getAttribute('name');
                    switch (inputName) {
                        case 'quyen':
                            if(hasQuyenInputBefore) ignoreVal++;
                            elementsArr[i].checked = arrIdOfQuyen.includes(elementsArr[i].value);
                            hasQuyenInputBefore = true;
                            break;
                        case 'kichhoat':
                            elementsArr[i].checked = parentRow.children[i + 1 - ignoreVal].querySelector('input').checked;
                            break;
                        default:
                            elementsArr[i].value = parentRow.children[i].innerHTML;
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
                    case 'quyen':
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
            console.log(data);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK + "/" + id,
                data: {
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
                    case 'quyen':
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
                        printErrorMsg(data.error, "print-error-msg-on-create");
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
							"_method": 'DELETE'
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