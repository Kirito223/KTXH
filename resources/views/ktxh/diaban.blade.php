@extends('master')
@section('title','Địa bàn')
@section('content')
<section class="app-content">
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="loaisolieu" id="loaisolieuForm" style="margin-bottom : 10px;">
                <div class="widget">
                    <header class="widget-header">
                        @if(!empty(Session::get('success')))
                        <div class="alert alert-success">
                            <p class="text-success">{{ Session::get('success') }}</p>
                        </div>
                        @endif
                        @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            <p class="text-danger">{{ $error }}</p>
                        </div>
                        @endforeach
                        @endif
                        @if(!empty(Session::get('error')))
                        <div class="alert alert-danger">
                            <p class='text-danger'>{{ Session::get('error') }}</p>
                        </div>
                        @endif
                        <h4 class="widget-title">Danh sách địa bàn</h4>
                    </header>
                    <hr class="widget-separator">
                    <div class="widget-body">
                        <div class="modal fade in" id="modalForCreateDiaban" tabindex="-1" role="dialog"
                            aria-labelledby="modelForCreateDiabanLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForCreateDiabanLabel">Tạo Mới Địa Bàn
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="createDiabanForm">
                                            <div class="form-group">
                                                <label for="create-input-tendiaban">Tên Địa Bàn</label>
                                                <input type="text" name="tendiaban" class="form-control"
                                                    id="create-input-tendiaban" placeholder="Nhập tên địa bàn">
                                            </div>
                                            <div class="form-group hidden-item">
                                                <label for="create-input-loaidiaban">Loại Địa Bàn</label>
                                                <input type="text" name="loaidiaban" class="form-control" value="tinh"
                                                    id="create-input-loaidiaban" placeholder="Nhập loại địa bàn">
                                            </div>
                                            <div class="form-group hidden-item">
                                                <label for="create-input-diabantructhuocid">Địa bàn trực thuộc
                                                    Id</label>
                                                <input type="text" name="diabantructhuocid" class="form-control"
                                                    id="create-input-diabantructhuocid"
                                                    placeholder="Nhập địa bàn trực thuộc Id">
                                            </div>
                                            <div class="form-group hidden-item">
                                                <label for="create-input-diabantructhuoc">Địa bàn trực thuộc</label>
                                                <input type="text" name="diabantructhuoc" class="form-control"
                                                    id="create-input-diabantructhuoc"
                                                    placeholder="Nhập địa bàn trực thuộc" readonly>
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-create" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="submit-button-for-create">Tạo
                                            mới</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                            bỏ</button>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="modal fade in" id="modelForEditDiaban" tabindex="-1" role="dialog"
                            aria-labelledby="editDiabanLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header row" style="padding: 5px">
                                        <h2 class="modal-title" id="modelForEditDiabanLabel">Chỉnh Sửa Địa Bàn
                                        </h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @csrf
                                    <div class="modal-body">
                                        <form id="editDiabanForm">
                                            <input type="hidden" name="id" id="EditInputId">
                                            <div class="form-group">
                                                <label for="edit-input-tendiaban">Tên Địa Bàn</label>
                                                <input type="text" name="tendiaban" class="form-control"
                                                    id="edit-input-tendiaban" placeholder="Nhập tên địa bàn">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit-input-diabantructhuoc">Địa Bàn Trực Thuộc</label>
                                                <input type="text" name="diabantructhuoc" class="form-control"
                                                    id="edit-input-diabantructhuoc" placeholder="Không có" readonly>
                                            </div>
                                            <div class="form-group hidden-item">
                                                <label for="edit-input-loaidiaban">Loại Địa Bàn</label>
                                                <input type="text" name="loaidiaban" class="form-control"
                                                    id="edit-input-loaidiaban" placeholder="Nhập mô tả">
                                            </div>
                                        </form>
                                        <div class="alert alert-danger print-error-msg-on-edit" style="display:none">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success mw-md" id="confirm-edit-btn">Chỉnh sửa</button>
                                        <button type="button" class="btn btn-danger mw-md" data-dismiss="modal">Hủy
                                            bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Xóa Địa Bàn</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn xóa địa bàn này?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            id="button-confirm-delete">Xóa</button>
                                        <button type="button" class="btn" data-dismiss="modal">Hủy Bỏ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="row" style="display:flex; justify-content: flex-start">
                                <div style="margin-left: auto">
                                    <button type="button" class="btn mw-md btn-success m-xs" id="show-create-modal">
                                        Tạo Mới</button>
                                </div>
                            </div>
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">STT</th>
                                        <th style="width: 70%">Tên Địa Bàn</th>
                                        <th class="hidden-item">Địa Bàn Trực Thuộc</th>
                                        <th style="width: 20%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <form method="POST" action="loaisolieu" id="loaisolieuForm"
                                        style="margin-bottom : 10px;">
                                        @csrf
                                        {{ method_field('PUT') }}


                                        <?php function TraverseNodesTree($diabanNode, $nodeLevel) {
                                            if($diabanNode == null || $diabanNode->isDelete == true) {
                                                return;
                                            }
                                            $selfIdSuffix = '';
                                            $childIdSuffix = '';
                                            $cloneNode = $diabanNode->replicate();
                                            $cloneNode->id = $diabanNode->id;
                                            while($cloneNode != null) {
                                                $selfIdSuffix =  '-' . $cloneNode->id . '' . $selfIdSuffix;
                                                $cloneNode = $cloneNode->diabancha;
                                            }

                                            $cloneNode = $diabanNode->replicate();
                                            $cloneNode->id = $diabanNode->id;
                                            while($cloneNode->diabancha != null) {
                                                $childIdSuffix =  '-' . $cloneNode->diabancha->id . '' . $childIdSuffix;
                                                $cloneNode = $cloneNode->diabancha;
                                            }
                                            
                                            $isEmpty = true;
                                            if($diabanNode->diabancon != null){
                                                foreach($diabanNode->diabancon as $diabancon) {
                                                    if($diabancon->isDelete == false){
                                                        $isEmpty = false;
                                                        break;    
                                                    }
                                                }
                                            } 
                                            echo 
                                            '<tr id="diaban-self-row'.$selfIdSuffix.'" class="'.($diabanNode->diabancha != null ? 'hidden-item diaban-children-row'.$childIdSuffix : '').' ">
                                            <td style="vertical-align: middle">'. (($diabanNode->diabancha == null) ? $diabanNode->id : '<p class="hidden-item">'. $diabanNode->id .'</p>') .'</td>
                                            <td style="vertical-align: middle;"><div style="display:flex">'.addSpanElement($nodeLevel).''.(($diabanNode->diabancon != null && count($diabanNode->diabancon) > 0 && !$isEmpty) ? '<div><i id="diaban-right-caret'.$selfIdSuffix.'" class="zmdi zmdi-hc-2x zmdi-caret-right diaban-right-caret"></i><i id="diaban-down-caret'.$selfIdSuffix.'" class="zmdi zmdi-hc-2x zmdi-caret-down hidden-item diaban-down-caret"></i></div>' : '').'<div style="padding: 4px; margin-left : 2px" class="content-diaban">'.$diabanNode->_name.'</div></div></td>
                                            <td class="hidden-item">'. ($diabanNode->diabancha != null ? $diabanNode->diabancha->_name : '') .'</td>
                                            <td style="vertical-align: middle">'. ($nodeLevel < 2 ? '<button type="button" class="btn btn-success btn-xs detail-create-btn"
                                            style="margin: 2px">
                                            <i class="fa fa-plus"></i>
                                        </button>' : '') .'
                                                <button type="button" class="btn btn-primary btn-xs edit-btn"
                                                    style="margin: 2px">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs button-delete"
                                                    style="margin: 2px" id="delete-button-'.$diabanNode->id.'">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </td>
                                            </tr>';
                                            $nodeLevel++;
                                            if($diabanNode->diabancon != null) {
                                                for($i=0; $i<count($diabanNode->diabancon); $i++){
                                                    TraverseNodesTree($diabanNode->diabancon[$i], $nodeLevel );
                                                }
                                            }
                                            return;           
                                        }
                                        function addSpanElement($number) {
                                            $spans = '';
                                            if($number > 0) 
                                            {
                                                for($i = 0; $i< $number; $i++) {
                                                    $spans .= '<span style="color:red;margin-right:1.25em;">&nbsp;</span>';
                                                }
                                            }
                                            return $spans;
                                        }
                                         
                                        for($j = 0; $j < count($tinhs); $j++) {
                                            TraverseNodesTree($tinhs[$j], 0);
                                        }
                                        ?>
                                    </form>
                                </tbody>
                            </table>
                            {{ $tinhs->links() }}
                        </div>

                    </div>
                </div>
        </div>
</section>

<script>
$(document).ready(function() {
    $('#show-create-modal').click(function(e) {
        e.preventDefault();
        $('#modalForCreateDiaban').modal('show');
    });

    TreeViewServices('diaban');
    let modalServices = modelService();
    modalServices.generateSubmitButtonForCreateEvent('submit-button-for-create', 'create-input-tendiaban',
        'create-input-loaidiaban', 'create-input-diabantructhuocid');
    modalServices.generateDetailsCreateButtonEvent('detail-create-btn', 'create-input-loaidiaban',
        'create-input-diabantructhuocid', 'create-input-diabantructhuoc');
    modalServices.generateEditButtonsEvent('edit-btn', 'confirm-edit-btn', 'edit-input-tendiaban',
        'edit-input-diabantructhuoc', 'edit-input-loaidiaban');
    modalServices.generateDeleteButtonsEvents('button-delete', 'button-confirm-delete');
});




function TreeViewServices(prefix) {
    let rightCarets = document.getElementsByClassName(prefix + "-right-caret");
    let downCarets = document.getElementsByClassName(prefix + "-down-caret");
    for (let i = 0; i < rightCarets.length; i++) {
        rightCarets[i].addEventListener('click', function() {
            let idArr = this.id.split('-');
            let id = idArr.slice(3).join('-');
            let downCaretsWithSameId = document.getElementById(prefix + "-down-caret-" + id);
            downCaretsWithSameId.classList.toggle('hidden-item');
            openChildsNode(id)
            this.classList.toggle('hidden-item');
        });
    }

    for (let i = 0; i < downCarets.length; i++) {
        downCarets[i].addEventListener('click', function() {
            let idArr = this.id.split('-');
            let id = idArr.slice(3).join('-');
            let rightCaretsWithSameId = document.getElementById(prefix + "-right-caret-" + id);
            rightCaretsWithSameId.classList.toggle('hidden-item');
            closeAllChildsNode(id);
            this.classList.toggle('hidden-item');
        });
    }

    function openChildsNode(id) {
        let childsNode = document.getElementsByClassName(prefix + "-children-row-" + id);
        for (let i = 0; i < childsNode.length; i++) {
            childsNode[i].classList.remove('hidden-item');
        }
    }

    function closeAllChildsNode(id) {
        let childNodesIdArr = getAllChildsNodeId(id);
        for (let i = 0; i < childNodesIdArr.length; i++) {
            let childsNode = document.getElementById(prefix + "-self-row-" + childNodesIdArr[i]);
            childsNode.classList.add('hidden-item');

            let downCaret = document.getElementById(prefix + "-down-caret-" + childNodesIdArr[i]);
            if (downCaret != null) {
                downCaret.classList.add('hidden-item');
            }
            let rightCaret = document.getElementById(prefix + "-right-caret-" + childNodesIdArr[i]);
            if (rightCaret != null) {
                rightCaret.classList.remove('hidden-item');
            }
        }
    }

    function getAllChildsNodeId(...nodesId) {
        if (nodesId.length == 0) {
            return [];
        }
        let currentChildsNodeIdArr = []
        for (let i = 0; i < nodesId.length; i++) {
            let childsNode = document.getElementsByClassName(prefix + "-children-row-" + nodesId[i]);
            childsNode = [...childsNode];
            currentChildsNodeIdArr = [...currentChildsNodeIdArr, ...childsNode.map(function(item) {
                return item.id.split("-").slice(3).join('-');
            })];
        }
        return [...currentChildsNodeIdArr, ...getAllChildsNodeId(...currentChildsNodeIdArr)];
    }
}

function modelService() {

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
                if (elementsArr[i].nodeName == "SELECT") {
                    data[elementsArr[i].getAttribute('name')] = elementsArr[i].value;
                    continue;
                }
                let inputType = elementsArr[i].getAttribute('type');
                let inputName = elementsArr[i].getAttribute('name');
                switch (inputType) {
                    case "checkbox":
                        data[inputName] = elementsArr[i].checked
                        break;
                    case "text":
                        data[inputName] = elementsArr[i].value
                        break;
                    case "radio":
                        if (elementsArr[i].checked) {
                            data[inputName] = elementsArr[i].value
                        }
                        break;
                }
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '/diaban',
                data,
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-create")
                    } else {
                        console.log(data.success);
                        window.location.href = '/diaban';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    }

    function generateDetailsCreateButtonEvent(detailCreateButtonClassName, createInputLoaidiabanId,
        createInputDiabanchaId, createInputDiabancha) {
        let loaidiabanInput = document.getElementById(createInputLoaidiabanId);
        let diabanchaIdInput = document.getElementById(createInputDiabanchaId);
        let diabanchaInput = document.getElementById(createInputDiabancha);
        let createButtons = document.getElementsByClassName(detailCreateButtonClassName);
        for (let i = 0; i < createButtons.length; i++) {
            createButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                let parentRow = this.closest('tr');
                let arrRowId = parentRow.id.split('-').slice(3);
                if (arrRowId.length == 1) {
                    loaidiabanInput.value = "quanhuyen";
                    diabanchaIdInput.value = arrRowId[0];
                }
                if (arrRowId.length == 2) {
                    loaidiabanInput.value = "xaphuong";
                    diabanchaIdInput.value = arrRowId[1];
                }
                diabanchaInput.value = parentRow.querySelector('.content-diaban').innerHTML;
                diabanchaInput.closest(".form-group").classList.remove('hidden-item');
                $('#modalForCreateDiaban').modal('show');
            });
        }
    }

    function printErrorMsg(msg, printErrorMessageClassName) {
        $("." + printErrorMessageClassName).find("ul").html('');
        $("." + printErrorMessageClassName).css('display', 'block');
        $.each(msg, function(key, value) {
            $("." + printErrorMessageClassName).find("ul").append('<li>' + value + '</li>');
        });
    }


    function generateEditButtonsEvent(editBtnClassName, confirmEditButtonId, ...inputElementsIdArr) {
        var elementsArr = []
        for (let i = 0; i < inputElementsIdArr.length; i++) {
            elementsArr.push(document.getElementById(inputElementsIdArr[i]))
        }
        let editButtons = document.getElementsByClassName(editBtnClassName);
        let id;
        for (let i = 0; i < editButtons.length; i++) {
            editButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $("#modelForEditDiaban").modal('show');
                let parentRow = this.closest('tr');
                let arrRowId = parentRow.id.split('-').slice(3);
                let loaidiaban;
                switch (arrRowId.length) {
                    case 1:
                        loaidiaban = 'tinh';
                        break;
                    case 2:
                        loaidiaban = 'quanhuyen';
                        break;
                    case 3:
                        loaidiaban = 'xaphuong';
                        break;
                }
                id = arrRowId[arrRowId.length - 1];
                console.log(id);
                for (let i = 0; i < elementsArr.length; i++) {
                    let inputType = elementsArr[i].getAttribute('type');
                    let inputName = elementsArr[i].getAttribute('name');
                    switch (inputName) {
                        case 'loaidiaban':
                            elementsArr[i].value = loaidiaban;
                            break;
                        case 'tendiaban':
                            elementsArr[i].value = parentRow.querySelector('.content-diaban').innerHTML;
                            break;
                        case 'diabantructhuoc':
                            elementsArr[i].value = parentRow.children[i + 1].innerHTML;
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
                data[inputName] = elementsArr[i].value;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "/diaban/" + id,
                data : {
					...data,
					"_method" : 'PUT'
				},
                success: function(data) {
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error, "print-error-msg-on-edit");
                    } else {
                        console.log(data.success);
                        window.location.href = '/diaban';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    }

    function generateDeleteButtonsEvents(btnDeleteClassName, btnConfirmDeleteId) {
        var deleteButtons = document.getElementsByClassName(btnDeleteClassName);

        for (let i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener('click', function(e) {
                e.preventDefault();
                $("#deleteModal").modal('show');
                let parentRow = this.closest('tr');
                let arrRowId = parentRow.id.split('-').slice(3);
                let id = arrRowId[arrRowId.length - 1];
                let loaidiaban;
                switch(arrRowId.length) {
                    case 1:
                        loaidiaban = 'tinh';
                        break;
                    case 2:
                        loaidiaban = 'quanhuyen';
                        break;
                    case 3:
                        loaidiaban = 'xaphuong';
                        break;
                } 
                var confirmDeleteButton = document.getElementById(btnConfirmDeleteId);
                confirmDeleteButton.addEventListener('click', function sendDeleteInfoAjax(e) {
                    e.preventDefault();
                    console.log();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "/diaban/" + loaidiaban + "/" + id,
						data: {
							"_method": 'DELETE'
						},
                        success: function(data) {
                            if (!$.isEmptyObject(data.error)) {
                                alert(data.error);
                            } else {
                                window.location.href = '/diaban';
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
        generateSubmitButtonForCreateEvent,
        generateDetailsCreateButtonEvent,
        generateEditButtonsEvent,
        generateDeleteButtonsEvents
    }
}
</script>
@endsection