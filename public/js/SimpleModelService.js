$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function simpleModelService(urlLink) 
{
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

    function generateEditButtonsEvent(editBtnClassName, confirmEditButtonId, modalId ,...inputElementsIdArr) {
        var elementsArr = []
        for(let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        let editButtons = document.getElementsByClassName(editBtnClassName);
        let id;
        for (let i = 0; i < editButtons.length; i++) {
            editButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
				$("#" + modalId).modal("show");
                let idArr = this.id.split('-');
                let parentRow = this.closest('tr');
                id = idArr[idArr.length - 1];
                for(let i = 0; i < elementsArr.length; i++) {
                    let inputType = elementsArr[i].getAttribute('type');
                    if(inputType == "text") {
                        elementsArr[i].value = parentRow.children[i + 1].innerHTML;
                    } else if (inputType == "checkbox") {
                        elementsArr[i].checked = parentRow.children[i + 1].querySelector("input").checked;
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
                data[inputName] = inputType == "checkbox" ? elementsArr[i].checked : elementsArr[i].value;  
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: URLLINK + "/" + id,
                data: {
					...data,
					"_method": 'PUT'
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
        for(let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        let submitButtonForCreate = document.getElementById(submitButtonForCreateId);
        submitButtonForCreate.addEventListener('click', function(e) {
            e.preventDefault();
            let data = {};
            for (let i = 0; i < elementsArr.length; i++) {
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                data[inputName] = inputType == "checkbox" ? elementsArr[i].checked : elementsArr[i].value;  
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
				$("#" + modalId).modal("show");
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
							"_method" : 'DELETE'
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
    return {
        setCheckedItems,
        generateCheckboxesEvent,
        generateEditButtonsEvent,
        generateSubmitButtonForCreateEvent,
        generateUpAndDownButtonsEvents,
        generateDeleteButtonsEvents
    }
}