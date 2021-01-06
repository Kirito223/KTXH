var listLocation,
    tblChitietbieumau,
    tblsolieutheobieu,
    tbl_chitietsolieutheobieu,
    donvicha;

export async function process(
    location,
    year,
    bieumau,
    mau,
    loaisolieu,
    namelocation,
    diaban, data
) {
    let tree = data.tree;
    listLocation = data.listxahuyen;
    tblChitietbieumau = data.tblChitietbieumau;
    tblsolieutheobieu = data.tblsolieutheobieu;
    tbl_chitietsolieutheobieu = data.tbl_chitietsolieutheobieu;
    donvicha = donvicha;
    let lowBound = 0;
    let upBound = tree.length - 1;
    let mid = Math.round(lowBound + (upBound - lowBound) / 3);

    let endMidTwo = mid + mid;
    let endTreeThree = endMidTwo + mid-1;
    let arr = await Promise.all([
        splitAarray(tree, 0, mid),
        splitAarray(tree, mid, endMidTwo),
        splitAarray(tree, endMidTwo, endTreeThree),
    ]).then((values) => {
        return values;
    });
    let results = await Promise.all([
        synthetic(arr[0], year, mau, loaisolieu),
        synthetic(arr[1], year, mau, loaisolieu),
        synthetic(arr[2], year, mau, loaisolieu),
    ]).then((value) => {
        return value;
    });

    let rs = results[0].result.concat(
        results[1].result.concat(results[2].result)
    );
    let thongtin = {
        diaban: namelocation,
        nam: year,
        tyle1:
            results[0].tong_tyle1 +
            results[1].tong_tyle1 +
            results[2].tong_tyle1,
        tyle2:
            results[0].tong_tyle2 +
            results[1].tong_tyle2 +
            results[2].tong_tyle2,
        tyle3:
            results[0].tong_tyle3 +
            results[1].tong_tyle3 +
            results[2].tong_tyle3,
        tyle4:
            results[0].tong_tyle4 +
            results[1].tong_tyle4 +
            results[2].tong_tyle4,
        tyle5:
            results[0].tong_tyle5 +
            results[1].tong_tyle5 +
            results[2].tong_tyle5,
        tyle6:
            results[0].tong_tyle6 +
            results[1].tong_tyle6 +
            results[2].tong_tyle6,
        bieumau: mau,
        solieu: data.tenloaisolieu,
    };
    let dulieu = {
        nutcha: data.datacha,
        nutcon: data.data,
        chitiet: rs,
        chitiet1: rs,
        chitiet2: rs,
        chitiet3: rs,
        chitiet4: rs,
        thongtin: thongtin,
    };
    Swal.close();
    return dulieu;
}


function splitAarray(tree, endMid, endTree) {
    let result = [];
    for (let indexTree = endMid; indexTree < endTree; indexTree++) {
        result.push(tree[indexTree]);
    }
    return result;
}

function synthetic(tree, year, bieumau, loaisolieu) {
    let Result = [];
    let tong_tu1 = 0;
    let tong_mau1 = 0;

    let tong_tyle1 = 0;
    let tong_tyle2 = 0;
    let tong_tyle3 = 0;
    let tong_tyle4 = 0;
    let tong_tyle5 = 0;
    let tong_tyle6 = 0;
    tree.forEach((item) => {
        if (item.id == 2194) {
            debugger;
        }
        let itemResult = {};
        itemResult.id = item.id;
        itemResult.chitieu = item.ten;
        itemResult.idcha = item.idcha;
        itemResult.strid = item.id.toString();
        itemResult.donvi = item.donvi;

        let TotalofTHnam1 = DataOfyearTH(year - 5, item.id, bieumau, 8);
        let TotalofTHnam2 = DataOfyearTH(year - 4, item.id, bieumau, 8);
        let TotalofTHnam3 = DataOfyearTH(year - 3, item.id, bieumau, 8);
        let TotalofTHnam4 = DataOfyearTH(year - 2, item.id, bieumau, 8);
        let TotalofTHnam5 = DataOfyearTH(year - 1, item.id, bieumau, 8);

        let TotalofTHnam = DataOfyearTH(year, item.id, bieumau, loaisolieu);
        let TotalofKHnam = DataOfyearTH(year, item.id, bieumau, loaisolieu);
        let TotalofTHnam6 = DataOfyearTH(year + 1, item.id, bieumau, 9);
        let TotalofTHnam7 = DataOfyearTH(year + 2, item.id, bieumau, 9);
        let TotalofTHnam8 = DataOfyearTH(year + 3, item.id, bieumau, 9);
        let TotalofTHnam9 = DataOfyearTH(year + 4, item.id, bieumau, 9);
        let TotalofTHnam10 = DataOfyearTH(year + 5, item.id, bieumau, 9);

        let ghichu1,
            ghichu2,
            ghichu3,
            ghichu4,
            ghichu5,
            ghichu6,
            ghichu7,
            ghichu8,
            ghichu9,
            ghichu10,
            ghichu;

        listLocation.forEach((location) => {
            ghichu1 = ghichuDataOfyear(
                year - 5,
                location.id,
                item.id,
                bieumau,
                8
            );
            ghichu2 = ghichuDataOfyear(
                year - 4,
                location.id,
                item.id,
                bieumau,
                8
            );
            ghichu3 = ghichuDataOfyear(
                year - 3,
                location.id,
                item.id,
                bieumau,
                8
            );
            ghichu4 = ghichuDataOfyear(
                year - 2,
                location.id,
                item.id,
                bieumau,
                8
            );
            ghichu5 = ghichuDataOfyear(
                year - 1,
                location.id,
                item.id,
                bieumau,
                8
            );
            ghichu = ghichuDataOfyear(
                year,
                location.id,
                item.id,
                bieumau,
                loaisolieu
            );

            ghichu6 = ghichuDataOfyear(
                year + 1,
                location.id,
                item.id,
                bieumau,
                9
            );
            ghichu7 = ghichuDataOfyear(
                year + 2,
                location.id,
                item.id,
                bieumau,
                9
            );
            ghichu8 = ghichuDataOfyear(
                year + 3,
                location.id,
                item.id,
                bieumau,
                9
            );
            ghichu9 = ghichuDataOfyear(
                year + 4,
                location.id,
                item.id,
                bieumau,
                9
            );
            ghichu10 = ghichuDataOfyear(
                year + 5,
                location.id,
                item.id,
                bieumau,
                9
            );
        });

        itemResult.ghichu1 = ghichu1;
        itemResult.ghichu2 = ghichu2;
        itemResult.ghichu3 = ghichu3;
        itemResult.ghichu4 = ghichu4;
        itemResult.ghichu5 = ghichu5;
        itemResult.ghichu = ghichu;
        itemResult.ghichu6 = ghichu6;
        itemResult.ghichu7 = ghichu7;
        itemResult.ghichu8 = ghichu8;
        itemResult.ghichu9 = ghichu9;
        itemResult.ghichu10 = ghichu10;
        //lay so lieu SS nam -10
        let GiaSS2010 = SumdataXaTH(year - 10, donvicha, item.id, bieumau, 33);
        //lay so lieu SS nam hien tại
        let GiaSS2020 = SumdataXaTH(year, donvicha, item.id, bieumau, 33);
        //lay so lieu TT nam 2019
        let GiaTH1 = SumdataXaTH(year - 5, donvicha, item.id, bieumau, 34);
        let GiaTH2 = SumdataXaTH(year - 4, donvicha, item.id, bieumau, 34);
        let GiaTH3 = SumdataXaTH(year - 3, donvicha, item.id, bieumau, 34);
        let GiaTH4 = SumdataXaTH(year - 2, donvicha, item.id, bieumau, 34);
        let GiaTH5 = SumdataXaTH(year - 1, donvicha, item.id, bieumau, 34);
        let GiaTH = SumdataXaTH(year, donvicha, item.id, bieumau, 34);
        let GiaTH6 = SumdataXaTH(year + 1, donvicha, item.id, bieumau, 34);
        let GiaTH7 = SumdataXaTH(year + 2, donvicha, item.id, bieumau, 34);
        let GiaTH8 = SumdataXaTH(year + 3, donvicha, item.id, bieumau, 34);
        let GiaTH9 = SumdataXaTH(year + 4, donvicha, item.id, bieumau, 34);
        let GiaTH10 = SumdataXaTH(year + 5, donvicha, item.id, bieumau, 34);
        itemResult.THnam1 = TotalofTHnam1;
        itemResult.THnam2 = TotalofTHnam2;
        itemResult.THnam3 = TotalofTHnam3;
        itemResult.THnam4 = TotalofTHnam4;
        itemResult.THnam5 = TotalofTHnam5;
        itemResult.THnam = TotalofKHnam;
        itemResult.Khnam = TotalofKHnam;
        itemResult.Khnam1 = TotalofTHnam6;
        itemResult.Khnam2 = TotalofTHnam7;
        itemResult.Khnam3 = TotalofTHnam8;
        itemResult.Khnam4 = TotalofTHnam9;
        itemResult.Khnam5 = TotalofTHnam10;
        //gia ss
        itemResult.GiaSS2010 = GiaSS2010;
        itemResult.GiaSS2020 = GiaSS2020;
        //gia tt let GiaTH1
        itemResult.GiaTH1 = GiaTH1;
        itemResult.GiaTH2 = GiaTH2;
        itemResult.GiaTH3 = GiaTH3;
        itemResult.GiaTH4 = GiaTH4;
        itemResult.GiaTH5 = GiaTH5;
        itemResult.GiaTH = GiaTH;
        itemResult.GiaTH6 = GiaTH6;
        itemResult.GiaTH7 = GiaTH7;
        itemResult.GiaTH8 = GiaTH8;
        itemResult.GiaTH9 = GiaTH9;
        itemResult.GiaTH10 = GiaTH10;
        itemResult.dubao = 0;
        if (TotalofTHnam > 0) {
            itemResult.tyle = (TotalofTHnam1 / TotalofTHnam) ** (1 / 5);
        }
        if (TotalofTHnam > 0 && itemResult.tyle > 0) {
            itemResult.dubao =
                (TotalofTHnam5 / (TotalofTHnam * itemResult.tyle ** 5)) **
                (1 / 5);
        }
        itemResult.tyle1 = 0;
        if ((TotalofTHnam1 * GiaSS2010) / 1000000000 > 0)
            itemResult.tyle1 = (TotalofTHnam / TotalofTHnam1) ** (1 / 5) * 100;
        tong_tu1 += TotalofTHnam;
        tong_mau1 += TotalofTHnam1;

        itemResult.tyle2 = 0;
        if ((TotalofTHnam * GiaSS2010) / 1000000000 > 0) {
            itemResult.tyle2 = (TotalofTHnam10 / TotalofTHnam) ** (1 / 5) * 100;
        }

        itemResult.tyle3 = 0;
        if (TotalofTHnam1 * GiaTH1 > 0) {
            itemResult.tyle3 =
                (((TotalofTHnam * GiaTH) / TotalofTHnam1) * GiaTH1) ** (1 / 5) *
                100;
        }

        itemResult.tyle4 = 0;
        if (GiaTH * TotalofTHnam > 0) {
            itemResult.tyle4 =
                (((TotalofTHnam10 * GiaTH10) / GiaTH) * TotalofTHnam) **
                    (1 / 5) *
                100;
        }

        //tyle 5, 6
        itemResult.tyle5 = 0;
        if (TotalofTHnam1 > 0)
            itemResult.tyle5 = (TotalofTHnam / TotalofTHnam1) ** (1 / 5) * 100;
        itemResult.tyle6 = 0;
        if (TotalofTHnam > 0) {
            itemResult.tyle6 = (TotalofTHnam10 / TotalofTHnam) ** (1 / 5) * 100;
        }
        tong_tyle1 += itemResult.tyle1;
        tong_tyle2 += itemResult.tyle2;
        tong_tyle3 += itemResult.tyle3;
        tong_tyle4 += itemResult.tyle4;
        tong_tyle5 += itemResult.tyle5;
        tong_tyle6 += itemResult.tyle6;
        let DetailXa = [];
        let sttxa = 1;

        listLocation.forEach((location) => {
            let itemXa = {};
            let THnam1 = SumdataXaTH(
                year - 5,
                location.id,
                item.id,
                bieumau,
                8
            );
            let THnam2 = SumdataXaTH(
                year - 4,
                location.id,
                item.id,
                bieumau,
                8
            );
            let THnam3 = SumdataXaTH(
                year - 3,
                location.id,
                item.id,
                bieumau,
                8
            );
            let THnam4 = SumdataXaTH(
                year - 2,
                location.id,
                item.id,
                bieumau,
                8
            );
            let THnam5 = SumdataXaTH(
                year - 1,
                location.id,
                item.id,
                bieumau,
                8
            );
            let THnam = SumdataXaTH(year, location.id, item.id, bieumau, 8);
            itemXa.id = item.id;
            itemXa.tenxa = location.tendonvi;
            itemXa.THnam1 = THnam1;
            itemXa.THnam2 = THnam2;
            itemXa.THnam3 = THnam3;
            itemXa.THnam4 = THnam4;
            itemXa.THnam5 = THnam5;
            itemXa.THnam = THnam;

            let tenxa = "Xa_" + sttxa;
            itemXa.idxa = tenxa;
            sttxa++;
            itemResult[location.tendonvi] = itemXa;
            itemResult[tenxa] = itemXa;
        });
        itemResult.Detail = DetailXa;
        if (tong_mau1 > 0) {
            tong_tyle1 = (tong_tu1 / tong_mau1) ** (1 / 5) * 100;
            tong_tyle2 = tong_tyle1 ** (1 / 5) * 100;
            tong_tyle3 = tong_tyle1 ** (1 / 5) * 100;
            tong_tyle4 = tong_tyle1 ** (1 / 5) * 100;
            tong_tyle5 = tong_tyle1 ** (1 / 5) * 100;
            tong_tyle6 = tong_tyle1 ** (1 / 5) * 100;
        }
        Result.push(itemResult);
    });
    return {
        result: Result,
        tong_tyle1: tong_tyle1,
        tong_tyle2: tong_tyle2,
        tong_tyle3: tong_tyle3,
        tong_tyle4: tong_tyle4,
        tong_tyle5: tong_tyle5,
        tong_tyle6: tong_tyle6,
    };
}

function SumdataXaTH(year, xa, chitieu, bieumau, loaisolieu) {
    let total = 0;
    listLocation.forEach((location) => {
        let listBieumau = findBieumau(xa, bieumau, year, loaisolieu);
        if (listBieumau.length > 0) {
            total += maxtotalDeltailBieumau(chitieu, listBieumau, year);
        }
    });

    return total;
}
function ghichuDataOfyear(year, xa, chitieu, bieumau, loaisolieu) {
    let ghichu = "";
    let listBieumau = tblsolieutheobieu.filter(
        (x) =>
            x.donvinhap == xa &&
            x.bieumau == bieumau &&
            x.namnhap == year &&
            x.loaisolieu == loaisolieu
    );
    listBieumau.forEach((item) => {
        let listGhichu = tbl_chitietsolieutheobieu.filter(
            (x) => x.mabieusolieu == item.id && item.chitieu == chitieu
        );
        listGhichu.forEach((gc) => {
            ghichu += gc.ghichu;
        });
    });

    return ghichu;
}
function DataOfyearTH(year, chitieu, bieumau, loaisolieu) {
    let total = 0;
    listLocation.forEach((location) => {
        let listBieumau = findBieumau(location.id, bieumau, year, loaisolieu);
        if (listBieumau.length > 0) {
            total += maxtotalDeltailBieumau(chitieu, listBieumau, year);
        }
    });

    return total;
}

function maxtotalDeltailBieumau(chitieu, listBieumau, year) {
    // loc bang chi tiet so lieu theo bieu
    let total = 0;
    listBieumau.forEach((itemBieumau) => {
        let arr = tbl_chitietsolieutheobieu.filter(
            (x) => x.mabieusolieu == itemBieumau.id && x.chitieu == chitieu
        );
        arr.forEach((item) => {
            total += Number(item.sanluong);
        });
    });

    return total;
}

function findBieumau(idXa, bieumau, year, loaisolieu) {
    let result = tblsolieutheobieu.filter(
        (x) =>
            x.donvinhap == idXa &&
            x.bieumau == bieumau &&
            x.namnhap == year &&
            x.loaisolieu == loaisolieu
    );
    return result;
}
