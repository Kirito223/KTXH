//cap nhat ngay 20-07-2020
var topheadNotifyService = topHeadNotifyService();
topheadNotifyService.generatetopHeadNotifyEvent();


function topHeadNotifyService() {
    let titleElement = document.getElementById('tophead-details-title');
    let startDayElement = document.getElementById('tophead-details-start-day');
    let endDayElement = document.getElementById('tophead-details-end-day');
    let contentElement = document.getElementById('tophead-details-content');
    let downloadBtnElement = document.getElementById('tophead-details-download-doc');
    let nonDownloadElement = document.getElementById('tophead-details-non-donwnload-doc');
    let unseenNotifyCounter = document.getElementById('tophead-unseen-notify-couter');
    let notifyBtn = document.getElementById('notify-button');
    let seenIdArr = [];

    function generatetopHeadNotifyEvent() {
        notifyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('li').classList.toggle('open');
        });
        let notifyItems = [...document.getElementsByClassName('notify-item')];
        for (let i = 0; i < notifyItems.length; i++) {
            notifyItems[i].addEventListener('click', function(e) {
                e.preventDefault();
                let idArr = this.id.split('-');
                id = idArr[idArr.length - 1];
                let notifyItem = this;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: 'thongbao/' + id + '/getThongbaoInfo',
                    success: function(data) {
                        if (!$.isEmptyObject(data.error)) {
                            // printErrorMsg(data.error, "print-error-msg-on-edit");
                        } else {
                            console.log(data.success);
                            let thongbao = data.success;
                            titleElement.innerHTML = thongbao.tieude;
                            startDayElement.innerHTML = thongbao.ngaybatdau;
                            endDayElement.innerHTML = thongbao.ngayketthuc;
                            contentElement.innerHTML = thongbao.noidung;
                            if (thongbao.taptin !== null) {
                                let filename = thongbao.taptin.split('-')[1];
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
                            $('#model-for-thongbao-details').modal('show');
                            changeNotifyItemStatus(id);
                            // window.location.href = URLLINK;
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            })
    }
}

    function changeNotifyItemStatus(id) {
        if (!seenIdArr.includes(id)) {
            let notifyItem = document.getElementById('notify-item-' + id);
            let mediaRightElement = notifyItem.querySelector('.media-right');
            mediaRightElement.innerHTML = '';
            mediaRightElement.appendChild(getSeenLabelElement());
            seenIdArr.push(id);
            unseenNotifyCounter.innerText--;
            if(document.getElementById('row-thongbao-' + id) !== null) {
                let statusTdElement = document.getElementById('row-thongbao-' + id).lastElementChild;
                statusTdElement.innerHTML = '';
                statusTdElement.appendChild(getSeenLabelElement());
            };
        }
    }

    function getSeenLabelElement() {
        let seenLabelElement = document.createElement('span');
        seenLabelElement.innerText = 'Đã xem';
        seenLabelElement.classList.add('label', 'label-success');
        return seenLabelElement;
    }

    return {
        generatetopHeadNotifyEvent,
        changeNotifyItemStatus
    }
}