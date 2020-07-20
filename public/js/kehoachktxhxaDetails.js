function kehoachktxhxaDetailsServices() {
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".datepicker").datepicker({
            language: 'vi'
        });

    });

    let optionsArr = [];
    let thonNewItemCounter = 1;
    let hoatdongNewItemCounter = 1;
    let thanhvienNewItemCounter = 1;
    let dexuatNewItemCounter = 1;

    


    let addThanhvienBtn = document.getElementById('add-thanhvien-btn');
    let thanhvienList = document.getElementById('thanhvien-list-row');
    addThanhvienBtn.addEventListener('click', function(e) {
        let thanhvienNewItem = document.getElementById('thanhvien-item-row-pattern').cloneNode(true);
        thanhvienNewItem.classList.remove('hidden-item');
        thanhvienNewItem.id = '';
        let inputs = [...thanhvienNewItem.querySelectorAll('[name]')];
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.add('ii1-thanhvien-new-' + thanhvienNewItemCounter);
        }
        thanhvienList.appendChild(thanhvienNewItem);
        thanhvienNewItemCounter++;
        generateOptionsForThanhvienSelect(optionsArr);
        generateDeleteBtnEvent();
    });

    let addThonBtn = document.getElementById('add-thon-btn');
    let thonList = document.getElementById('thon-list-row');
    addThonBtn.addEventListener('click', function(e) {
        let thonNewItem = document.getElementById('thon-item-row-pattern').cloneNode(true);
        thonNewItem.classList.remove('hidden-item');
        thonNewItem.classList.add('thon-item-row');
        thonNewItem.id = '';
        let inputs = [...thonNewItem.querySelectorAll('input[name]')];
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.add('ii1-thon-new-' + thonNewItemCounter);
            if (inputs[i].name == 'id') {
                inputs[i].value = 'new-' + thonNewItemCounter;
            }
            if (inputs[i].name == 'tenthon') {
                inputs[i].id = 'ii1-tenthon-input-new-' + thonNewItemCounter;
            }
        }
        thonList.appendChild(thonNewItem);
        thonNewItemCounter++;
        addOnChangeEventHandlerForThonInput();
        generateDeleteBtnEvent();
    });

    let addHoatdongBtn = document.getElementById('add-hoatdong-btn');
    let hoatdongList = document.getElementById('hoatdong-list-row');
    addHoatdongBtn.addEventListener('click', function(e) {
        let hoatdongNewItem = document.getElementById('hoatdong-item-row-pattern').cloneNode(true);
        hoatdongNewItem.classList.remove('hidden-item');
        let inputs = [...hoatdongNewItem.querySelectorAll('input[name]')];
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.add('ii2-hoatdong-new-' + hoatdongNewItemCounter);
        }
        hoatdongList.appendChild(hoatdongNewItem);
        hoatdongNewItemCounter++;
        generateDeleteBtnEvent();
        $(".datepicker").datepicker({
            language: 'vi'
        });
    });

    let addDexuatBtn = document.getElementById('add-dexuat-btn');
    let dexuatList = document.getElementById('dexuat-list-row');
    addDexuatBtn.addEventListener('click', function(e) {
        let dexuatNewItem = document.getElementById('dexuat-item-row-pattern').cloneNode(true);
        dexuatNewItem.classList.remove('hidden-item');
        dexuatNewItem.id = '';
        dexuatNewItem.querySelector('button').id = "new-collapse-btn-" + dexuatNewItemCounter;
        dexuatNewItem.querySelector('div.collapse').id = 'new-hoatdong-' + dexuatNewItemCounter;
        let inputs = [...dexuatNewItem.querySelectorAll('input[name]')];
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.add('ii4b-dexuat-new-' + dexuatNewItemCounter);
        }
        dexuatList.appendChild(dexuatNewItem);
        dexuatNewItemCounter++;
        addOnChangeEventHandlerForTendexuatInput();
        generateDeleteBtnEvent();
        generateDexuatCollapseEvent();
    });


    let tabPaneElements = document.getElementsByClassName('tab-pane');
    tabPaneElements = [...tabPaneElements];
    let tabButtonElements = document.getElementsByClassName('tab-button');
    tabButtonElements = [...tabButtonElements];
    tabButtonElements.forEach(e => generateTabButtonEvent(e.id));



    function generateTabButtonEvent(id) {
        document.getElementById(id).addEventListener('click', function(e) {
            e.preventDefault();
            let tabContentId = this.id.split('-')[0];
            this.classList.add('active');
            tabPaneElements.forEach(e => e.classList.remove('show', 'active'));
            document.getElementById(tabContentId).classList.add('show', 'active');
        })
    }

    function generateOptionsForThanhvienSelect(optionsArr) {
        let str = "";
        let selectElements = document.getElementsByClassName('thuoc-thanhvien-select');
        selectElements = [...selectElements];
        for (let i = 0; i < selectElements.length; i++) {
            str += "<option value='none'>Đơn vị</option>";
            for (let j = 0; j < optionsArr.length; j++) {
                str += "<option value=" + optionsArr[j].val + " " + (optionsArr[j].name == selectElements[i].options[
                        selectElements[i].selectedIndex].text ? 'selected' : '') + ">" + optionsArr[j].name +
                    "</option>";
            }
            selectElements[i].innerHTML = str;
            str = "";
        }
    }

    function addOnChangeEventHandlerForThonInput() {
        let thonItemInputs = thonList.querySelectorAll('.thon-item-input');
        thonItemInputs = [...thonItemInputs];
        for (let i = 0; i < thonItemInputs.length; i++) {
            thonItemInputs[i].removeEventListener('change', changeOptions);
            thonItemInputs[i].addEventListener('change', changeOptions);
        }
    }


    function changeOptions() {
        let thonItemInputs = thonList.querySelectorAll('.thon-item-input');
        thonItemInputs = [...thonItemInputs];
        optionsArr = [];
        for (let i = 0; i < thonItemInputs.length; i++) {
            let val = thonItemInputs[i].id.length > 0 ? thonItemInputs[i].id.split('-').slice(3).join("-") : '';
            let name = thonItemInputs[i].value;
            optionsArr.push({
                val,
                name
            });
        }
        generateOptionsForThanhvienSelect(optionsArr);
    }



    function generateDeleteBtnEvent() {
        deleteBtns = document.getElementsByClassName('delete-btn');
        deleteBtns = [...deleteBtns];
        for (let i = 0; i < deleteBtns.length; i++) {
            deleteBtns[i].removeEventListener('click', removeParentRow);
            deleteBtns[i].addEventListener('click', removeParentRow);
        }
    }

    function removeParentRow() {
        let closestRow = this.closest('.row');
        if (closestRow.parentElement.nodeName == "LI") {
            closestRow = this.closest('li');
        }
        closestRow.remove();
        changeOptions();
    }



    function addOnChangeEventHandlerForTendexuatInput() {
        let tendexuatItemInputs = document.getElementsByClassName('tendexuat-item-input');
        tendexuatItemInputs = [...tendexuatItemInputs];
        for (let i = 0; i < tendexuatItemInputs.length; i++) {
            tendexuatItemInputs[i].removeEventListener('change', setNewTitleForDexuat)
            tendexuatItemInputs[i].addEventListener('change', setNewTitleForDexuat)
        }
    }

    function setNewTitleForDexuat() {
        this.closest('li').querySelector('.btn').innerHTML = this.value;
    }


    function sendData() {
        let id = document.getElementById('id-kehoachxhktxa-input').value;
        let bieuii1InputElements = document.querySelectorAll('[class^="ii1"], [class*=" ii1"]');
        let bieuii2InputElements = document.querySelectorAll('[class^="ii2"], [class*=" ii2"]');
        let bieuii4bInputElements = document.querySelectorAll('[class^="ii4b"], [class*=" ii4b"]');
        let data = {
            maubieuii1: handleElementsBelongToMaubieu(...bieuii1InputElements),
            maubieuii2: handleElementsBelongToMaubieu(...bieuii2InputElements),
            maubieuii4b: handleElementsBelongToMaubieu(...bieuii4bInputElements)
        }
        var jsonData = JSON.stringify(data);
        console.log(jsonData);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/kehoachktxhxa/' + id + '/maubieu',
            data: {
                data: jsonData,
                "_method": 'PUT'
            },
            success: function(data) {
                if (!$.isEmptyObject(data.error)) {
                    alert(data.error);
                } else {
                    console.log(data.success);
                    window.location.href = '/kehoachktxhxa/' + id + '/details';
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function handleElementsBelongToMaubieu(...elements) {
        let data = {};
        let keysFrequency = getKeysFrequency(elements);
        let keys = Object.keys(keysFrequency);
        for (let i = 0; i < keys.length; i++) {
            data[keys[i]] = handleKeyFrequencyObject(elements, keys[i], keysFrequency[keys[i]])
        }
        return data;
    }

    function getKeysFrequency(elements) {
        let keyFrequency = {};
        let attributeNames = [];
        for (let i = 0; i < elements.length; i++) {
            let className = getProperClassName(elements[i]);
            attributeNames.push(returnAttributeName(className));
        }
        for (let i = 0; i < attributeNames.length; i++) {
            if (!keyFrequency.hasOwnProperty(attributeNames[i])) {
                keyFrequency[attributeNames[i]] = 1;
            } else {
                keyFrequency[attributeNames[i]]++;
            }
        }
        return keyFrequency;
    }

    function returnAttributeName(className) {
        let classNameArr = className.split('-');
        return classNameArr[1];
    }

    function returnId(className) {
        let classNameArr = className.split('-');
        if (classNameArr.length > 2) {
            return classNameArr.slice(2).join('-');
        }
        return null;
    }

    function handleKeyFrequencyObject(elements, key, val) {
        if (val == 1) {
            for (let i = 0; i < elements.length; i++) {
                let className = getProperClassName(elements[i]);
                if (key == returnAttributeName(className)) {
                    return elements[i].value;
                }
            }
        } else {
            let obj = {};
            for (let i = 0; i < elements.length; i++) {
                let className = getProperClassName(elements[i]);
                let elementNameAttribute = elements[i].name;
                if (key == returnAttributeName(className)) {
                    let id = returnId(className);
                    if (!obj.hasOwnProperty(id)) {
                        obj[id] = {};
                        obj[id][elementNameAttribute] = elements[i].value;
                    } else {
                        obj[id][elementNameAttribute] = elements[i].value;
                    }
                }
            }
            return Object.values(obj);
        }
    }

    function getProperClassName(element) {
        let classNameArr = element.className.split(" ");
        let className = "";
        for (let j = 0; j < classNameArr.length; j++) {
            if (classNameArr[j].includes('ii')) {
                className = classNameArr[j];
                return className;
            }
        }
    }

    function generateDexuatCollapseEvent(){
        let collapseBtns = [...document.getElementsByClassName('collapse-btn')];
        for(let i=0; i< collapseBtns.length; i++) {
            collapseBtns[i].addEventListener('click', function(e) {
                e.preventDefault();
                let idArr = this.id.split('-');
                let itemId = idArr[idArr.length - 1];
                if(idArr[0] == "new") {
                $("#new-hoatdong-"+ itemId).collapse("toggle");
                } else {
                $("#dexuat-"+ itemId).collapse("toggle");
                }
                
            })
        }
    }

    document.getElementById("save-changes-btn").addEventListener('click', function(e) {
        e.preventDefault();
        sendData();
    });

    


    changeOptions();
    addOnChangeEventHandlerForThonInput();
    generateDeleteBtnEvent();
    addOnChangeEventHandlerForTendexuatInput();
    generateDexuatCollapseEvent('collapse-btn', 'dexuat-');
}