var listLocation,
    tblChitietbieumau,
    tblsolieutheobieu,
    tbl_chitietsolieutheobieu,
    donvicha;

export async function processReport(
    location,
    year,
    bieumau,
    mau,
    loaisolieu,
    namelocation,
    diaban,
    tenbieumau,
    data
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
    let endTreeThree = endMidTwo + mid - 1;
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
        bieumau: tenbieumau,
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
        chitiet5: rs,
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
    let perviousYear = year - 1;
    let Result = [];

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

        let TotalofPerviousYear = DataOfyearTH(
            perviousYear,
            item.id,
            bieumau,
            9
        );
        let TotalofTHPerviousYear = DataOfyearTH(
            perviousYear,
            item.id,
            bieumau,
            loaisolieu
        );

        let TotalofTHYear = DataOfyearTH(year, item.id, bieumau, loaisolieu);
        let TotalofCurrentYear = DataOfyearTH(year, item.id, bieumau, 9);
        let KHnew = DataOfyearTH(year + 1, item.id, bieumau, 9);
        itemResult.thYear = TotalofTHYear;
        itemResult.KHpreYear = TotalofPerviousYear;
        itemResult.KHcurrentYear = TotalofCurrentYear;
        itemResult.estimate = TotalofTHPerviousYear;
        itemResult.KHnamsau = KHnew;
        let ghichu;

        listLocation.forEach((location) => {
            ghichu = ghichuDataOfyear(year, location.id, item.id, bieumau, 9);
        });

        itemResult.ghichu = ghichu;
        itemResult.tyle = 0;
        if (TotalofPerviousYear > 0) {
            itemResult.tyle = TotalofTHPerviousYear / TotalofPerviousYear;
        }

        let DetailXa = [];
        let sttxa = 1;

        listLocation.forEach((location) => {
            let itemXa = {};
            let KH = SumdataXaTH(
                perviousYear,
                location.id,
                item.id,
                bieumau,
                9
            );
            let TH = SumdataXaTH(
                perviousYear,
                location.id,
                item.id,
                bieumau,
                9
            );
            let KHYear = SumdataXaTH(year, location.id, item.id, bieumau, 9);
            let THYear = SumdataXaTH(
                year,
                location.id,
                item.id,
                bieumau,
                loaisolieu
            );
            itemResult.thYear = TotalofTHYear;
            itemXa.id = item.id;
            itemXa.tenxa = location.tendonvi;
            itemXa.KH = KH;
            itemXa.TH = TH;
            if (KHYear == null) KHYear = 0;
            itemXa.KHYear = KHYear;
            itemXa.THYear = THYear;
            itemXa.tyle1 = 0;
            itemXa.tyle2 = 0;

            if (TH > 0) itemXa.tyle1 = THYear / TH;
            if (KHYear > 0) itemXa.tyle2 = THYear / KHYear;

            let tenxa = "Xa_" + sttxa;
            itemXa.idxa = tenxa;
            sttxa++;
            itemResult[location.tendonvi] = itemXa;
            itemResult[tenxa] = itemXa;
        });
        itemResult.Detail = DetailXa;
        Result.push(itemResult);
    });
    return {
        result: Result,
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
