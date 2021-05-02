export default class xuatgiaidoan {
    solieutheobieu;
    chitietsolieu;
    donvihanhchinh;

    DataOfyearTH($year, $chitieu, $bieumau, $loaisolieu) {
        let sum = 0;
        this.donvihanhchinh.forEach((xa) => {
            let listBieuMau = this.solieutheobieu.filter(
                (x) =>
                    x.bieumau == $bieumau &&
                    x.namnhap == $year &&
                    x.loaisolieu == $loaisolieu &&
                    x.donvinhap == xa.id
            );

            sum += this.maxtotalDeltailBieumau($chitieu, listBieuMau, $year);
        });
        return sum;
    }
    SumdataXaTH($year, $xa, $chitieu, $bieumau, $loaisolieu) {
        let sum = 0;
        let listBieuMau = this.solieutheobieu.filter(
            (x) =>
                x.bieumau == $bieumau &&
                x.namnhap == $year &&
                x.loaisolieu == $loaisolieu &&
                x.donvinhap == $xa.id
        );
        sum = this.maxtotalDeltailBieumau($chitieu, listBieuMau, $year);
        return sum;
    }
    maxtotalDeltailBieumau($Chitieu, $arrBieumau, $Time) {
        let $total = 0;
        $arrBieumau.forEach((mau) => {
            let danhSachChiTiet = this.chitietsolieu.filter(
                (x) => x.mabieusolieu == mau.id && x.chitieu == $Chitieu
            );
            let sum = 0;
            danhSachChiTiet.forEach((item) => {
                sum += Number(item.sanluong);
            });
            if (sum > $total) $total = sum;
        });
        return $total;
    }

    total(year, item, bieumau, donvicha) {
        // Tong hop TH
        let $TotalofTHnam5 = this.DataOfyearTH(year - 5, item, bieumau, 8);

        let $TotalofTHnam4 = this.DataOfyearTH(year - 4, item, bieumau, 8);

        let $TotalofTHnam3 = this.DataOfyearTH(year - 3, item, bieumau, 8);

        let $TotalofTHnam2 = this.DataOfyearTH(year - 2, item, bieumau, 8);

        let $TotalofTHnam1 = this.DataOfyearTH(year - 1, item, bieumau, 8);
        
        let $TotalofTHnam = this.DataOfyearTH(year, item, bieumau, 8);

        let $TotalofKHnam = this.DataOfyearTH(year, item, bieumau, 9);
        let columnTH = [
            $TotalofTHnam5,
            $TotalofTHnam4,
            $TotalofTHnam3,
            $TotalofTHnam2,
            $TotalofTHnam1,
            $TotalofTHnam,
            $TotalofKHnam,
        ];
        // Tong hop KH
        let $TotalofKHnam1 = this.DataOfyearTH(year + 1, item, bieumau, 9);
        let $TotalofKHnam2 = this.DataOfyearTH(year + 2, item, bieumau, 9);
        let $TotalofKHnam3 = this.DataOfyearTH(year + 3, item, bieumau, 9);
        let $TotalofKHnam4 = this.DataOfyearTH(year + 4, item, bieumau, 9);
        let $TotalofKHnam5 = this.DataOfyearTH(year + 5, item, bieumau, 9);

        let columsKH = [
            $TotalofKHnam1,
            $TotalofKHnam2,
            $TotalofKHnam3,
            $TotalofKHnam4,
            $TotalofKHnam5,
        ];

        // So sanh 2010
        let GiaSS2010 = this.SumdataXaTH(
            year - 10,
            donvicha,
            item,
            bieumau,
            33
        );

        // Tong hop ti so gia

        let $GiaTT1 = this.SumdataXaTH(year - 5, donvicha, item, bieumau, 34);

        let $GiaTT2 = this.SumdataXaTH(year - 4, donvicha, item, bieumau, 34);

        let $GiaTT3 = this.SumdataXaTH(year - 3, donvicha, item, bieumau, 34);

        let $GiaTT4 = this.SumdataXaTH(year - 2, donvicha, item, bieumau, 34);

        let $GiaTT5 = this.SumdataXaTH(year - 1, donvicha, item, bieumau, 34);

        let $GiaTT6 = this.SumdataXaTH(year, donvicha, item, bieumau, 34);

        let $GiaTT7 = this.SumdataXaTH(year + 1, donvicha, item, bieumau, 34);

        let $GiaTT8 = this.SumdataXaTH(year + 2, donvicha, item, bieumau, 34);

        let $GiaTT9 = this.SumdataXaTH(year + 3, donvicha, item, bieumau, 34);

        let $GiaTT10 = this.SumdataXaTH(year + 4, donvicha, item, bieumau, 34);

        let $GiaTT11 = this.SumdataXaTH(year + 5, donvicha, item, bieumau, 34);

        let columnsGiaTT = [
            $GiaTT1,
            $GiaTT2,
            $GiaTT3,
            $GiaTT4,
            $GiaTT5,
            $GiaTT6,
            $GiaTT7,
            $GiaTT8,
            $GiaTT9,
            $GiaTT10,
            $GiaTT11,
        ];

        return {
            chitieu: item,
            clolumsTH: columnTH,
            columnTKH: columsKH,
            giaSS2010: GiaSS2010,
            columnsGiaTT: columnsGiaTT,
        };
    }
}
