export function renderTable(data) {
    data.forEach((item) => {
        if (item.idcha == null) {
            $("#TreeGridContainer").append(`<tr data-tt-id="${item.id}">
            <td><input class="chk-child" data-parent="${item.idcha}" value ="${
                item.id
            }" type="checkbox"/></td>
            <td>${item.tenchitieu}</td>
            <td>${item.tendonvi == null ? "" : item.tendonvi}</td>
            </tr>`);
        } else {
            $("#TreeGridContainer").append(`<tr data-tt-id="${
                item.id
            }" data-tt-parent-id="${item.idcha}">
                <td><input data-parent="${
                    item.idcha
                }" class="chk-child" value ="${item.id}" type="checkbox"/></td>
            <td>${item.tenchitieu}</td>
            <td>${item.tendonvi == null ? "" : item.tendonvi}</td>
            </tr>`);
        }
    });

    let chks = document.getElementsByClassName("chk-child");
    for (let index = 0; index < chks.length; index++) {
        let item = chks[index];
        item.onclick = function () {
            selectNode(item.value);
            selectedParent(item.dataset.parent);
            checkallSelect();
        };
    }
}

// Hàm kiểm tra xem có còn con nào thuộc cha được chọn hay không
export function selectedParent(idparent) {
    let child = document.querySelectorAll(
        'input[data-parent="' + idparent + '"]'
    );
    let check = false;
    for (let index = 0; index < child.length; index++) {
        // Kiểm tra xem có con nút con nào được chọn không nếu còn thì sẽ trả về false còn nếu hết thì trả về true
        let item = child[index];
        if (item.checked == true) {
            check = false;
            break;
        } else {
            check = true;
        }
    }
    let parent = document.querySelector('input[value="' + idparent + '"]');
    // Nếu không còn nút con nào nữa thì đặt nút cha là false còn không sẽ đặt là true
    if (parent != null) {
        if (check == true) {
            parent.checked = false;
        } else {
            parent.checked = true;
        }
    }
    if (parent != null) {
        selectedParent(parent.dataset.parent);
    }
}

//Hàm được khời chạy khi chọn các node con
export function selectNode(id) {
    let parent = document.querySelector('input[value="' + id + '"]');
    // Lấy danh sách các node con thuộc node được cha được chọn
    let child = document.querySelectorAll('input[data-parent="' + id + '"]');
    // Lặp và set các giá trị của các node con
    for (let index = 0; index < child.length; index++) {
        let item = child[index];
        item.checked = parent.checked;
        selectNode(item.value);
    }
}

export function checkallSelect() {
    let chk = document.getElementsByClassName("chk-child");
    let check = false;
    for (let index = 0; index < chk.length; index++) {
        let item = chk[index];
        if (item.checked == true) {
            check = true;
            break;
        }
    }
    if (check == false) {
        document.getElementById("allSelect").checked = false;
    } else {
        document.getElementById("allSelect").checked = true;
    }
}
