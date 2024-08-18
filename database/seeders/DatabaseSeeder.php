<?php

namespace Database\Seeders;

use DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        // User::factory(10)->create();
        $data =[
                [
                    "symbol"=> "KLB",
                    "bin"=> "970452",
                    "short_name"=> "KienLongBank",
                    "name"=> "Ngân hàng TMCP Kiên Long",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/KLB.png"
                ],[
                    "symbol"=> "STB",
                    "bin"=> "970403",
                    "short_name"=> "Sacombank",
                    "name"=> "Ngân hàng TMCP Sài Gòn Thương Tín",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/STB.png"
                ],[
                    "symbol"=> "IBKHN",
                    "bin"=> "970455",
                    "short_name"=> "IBKHN",
                    "name"=> "Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh Hà Nội",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_ibk_bank.png"
                ],[
                    "symbol"=> "BIDV",
                    "bin"=> "970418",
                    "short_name"=> "BIDV",
                    "name"=> "Ngân hàng TMCP Đầu tư và Phát triển Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/BIDV.png"
                ],[
                    "symbol"=> "VRB",
                    "bin"=> "970421",
                    "short_name"=> "VRB",
                    "name"=> "Ngân hàng Liên doanh Việt - Nga",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VRB.png"
                ],[
                    "symbol"=> "KEBHANAHCM",
                    "bin"=> "970466",
                    "short_name"=> "KebHanaHCM",
                    "name"=> "Ngân hàng KEB Hana – Chi nhánh Thành phố Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/KEBHANAHCM.png"
                ],[
                    "symbol"=> "SHB",
                    "bin"=> "970443",
                    "short_name"=> "SHB",
                    "name"=> "Ngân hàng TMCP Sài Gòn - Hà Nội",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/SHB.png"
                ],[
                    "symbol"=> "PBVN",
                    "bin"=> "970439",
                    "short_name"=> "PublicBank",
                    "name"=> "Ngân hàng TNHH MTV Public Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/PBVN.png"
                ],[
                    "symbol"=> "DBS",
                    "bin"=> "796500",
                    "short_name"=> "DBSBank",
                    "name"=> "DBS Bank Ltd - Chi nhánh Thành phố Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_dbs.png"
                ],[
                    "symbol"=> "VARB",
                    "bin"=> "970405",
                    "short_name"=> "Agribank",
                    "name"=> "Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VARB.png"
                ],[
                    "symbol"=> "CFC",
                    "bin"=> "970460",
                    "short_name"=> "VietCredit",
                    "name"=> "Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/CFC.png"
                ],[
                    "symbol"=> "MB",
                    "bin"=> "970422",
                    "short_name"=> "MBBank",
                    "name"=> "Ngân hàng TMCP Quân đội",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/MB.png"
                ],[
                    "symbol"=> "DAB",
                    "bin"=> "970406",
                    "short_name"=> "DongABank",
                    "name"=> "Ngân hàng TMCP Đông Á",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/DAB.png"
                ],[
                    "symbol"=> "VB",
                    "bin"=> "970433",
                    "short_name"=> "VietBank",
                    "name"=> "Ngân hàng TMCP Việt Nam Thương Tín",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VB.png"
                ],[
                    "symbol"=> "EIB",
                    "bin"=> "970431",
                    "short_name"=> "Eximbank",
                    "name"=> "Ngân hàng TMCP Xuất Nhập khẩu Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/EIB.png"
                ],[
                    "symbol"=> "VNPTMONEY",
                    "bin"=> "971011",
                    "short_name"=> "VNPTMoney",
                    "name"=> "VNPT Money",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/VNPTMONEY.png"
                ],[
                    "symbol"=> "SGB",
                    "bin"=> "970400",
                    "short_name"=> "SaigonBank",
                    "name"=> "Ngân hàng TMCP Sài Gòn Công Thương",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/SGB.png"
                ],[
                    "symbol"=> "CAKE",
                    "bin"=> "546034",
                    "short_name"=> "CAKE",
                    "name"=> "TMCP Việt Nam Thịnh Vượng - Ngân hàng số CAKE by VPBank",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_cake.png"
                ],[
                    "symbol"=> "PGB",
                    "bin"=> "970430",
                    "short_name"=> "PGBank",
                    "name"=> "Ngân hàng TMCP Xăng dầu Petrolimex",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/PGB.png"
                ],[
                    "symbol"=> "NVB",
                    "bin"=> "970419",
                    "short_name"=> "NCB",
                    "name"=> "Ngân hàng TMCP Quốc Dân",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/NVB.png"
                ],[
                    "symbol"=> "HSBC",
                    "bin"=> "458761",
                    "short_name"=> "HSBC",
                    "name"=> "Ngân hàng TNHH MTV HSBC (Việt Nam)",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_hsbc.png"
                ],[
                    "symbol"=> "STANDARD",
                    "bin"=> "970410",
                    "short_name"=> "StandardChartered",
                    "name"=> "Ngân hàng TNHH MTV Standard Chartered Bank Việt Nam",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_standard_chartered.png"
                ],[
                    "symbol"=> "TCB",
                    "bin"=> "970407",
                    "short_name"=> "Techcombank",
                    "name"=> "Ngân hàng TMCP Kỹ thương Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/TCB.png"
                ],[
                    "symbol"=> "IVB",
                    "bin"=> "970434",
                    "short_name"=> "IndovinaBank",
                    "name"=> "Ngân hàng TNHH Indovina",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/IVB.png"
                ],[
                    "symbol"=> "VCB",
                    "bin"=> "970436",
                    "short_name"=> "VietcomBank",
                    "name"=> "Ngân hàng TMCP Ngoại Thương Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VCB.png"
                ],[
                    "symbol"=> "KEBHANAHN",
                    "bin"=> "970467",
                    "short_name"=> "KebHanaHN",
                    "name"=> "Ngân hàng KEB Hana – Chi nhánh Hà Nội",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/KEBHANAHCM.png"
                ],[
                    "symbol"=> "SVB",
                    "bin"=> "970424",
                    "short_name"=> "ShinhanBank",
                    "name"=> "Ngân hàng TNHH MTV Shinhan Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/SVB.png"
                ],[
                    "symbol"=> "KBHN",
                    "bin"=> "970462",
                    "short_name"=> "KookminHN",
                    "name"=> "Ngân hàng Kookmin - Chi nhánh Hà Nội",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_kookmin_hn.png"
                ],[
                    "symbol"=> "LPB",
                    "bin"=> "970449",
                    "short_name"=> "LienVietPostBank",
                    "name"=> "Ngân hàng TMCP Bưu Điện Liên Việt",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/LPB.png"
                ],[
                    "symbol"=> "PVCB",
                    "bin"=> "970412",
                    "short_name"=> "PVcomBank",
                    "name"=> "Ngân hàng TMCP Đại Chúng Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/PVCB.png"
                ],[
                    "symbol"=> "ABB",
                    "bin"=> "970425",
                    "short_name"=> "ABBANK",
                    "name"=> "Ngân hàng TMCP An Bình",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/ABB.png"
                ],[
                    "symbol"=> "CBB",
                    "bin"=> "970444",
                    "short_name"=> "CBBank",
                    "name"=> "Ngân hàng Thương mại TNHH MTV Xây dựng Việt Nam",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_cbbank.png"
                ],[
                    "symbol"=> "KBHCM",
                    "bin"=> "970463",
                    "short_name"=> "KookminHCM",
                    "name"=> "Ngân hàng Kookmin - Chi nhánh Thành phố Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_kookmin_hcm.png"
                ],[
                    "symbol"=> "HDB",
                    "bin"=> "970437, 970420",
                    "short_name"=> "HDBank",
                    "name"=> "Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/HDB.png"
                ],[
                    "symbol"=> "TPB",
                    "bin"=> "970423",
                    "short_name"=> "TPBank",
                    "name"=> "Ngân hàng TMCP Tiên Phong",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/TPB.png"
                ],[
                    "symbol"=> "VPB",
                    "bin"=> "970432",
                    "short_name"=> "VPBank",
                    "name"=> "Ngân hàng TMCP Việt Nam Thịnh Vượng",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VPB.png"
                ],[
                    "symbol"=> "Ubank",
                    "bin"=> "546035",
                    "short_name"=> "Ubank",
                    "name"=> "TMCP Việt Nam Thịnh Vượng - Ngân hàng số Ubank by VPBank",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_ubank.png"
                ],[
                    "symbol"=> "WOO",
                    "bin"=> "970457",
                    "short_name"=> "Woori",
                    "name"=> "Ngân hàng TNHH MTV Woori Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/WOO.png"
                ],[
                    "symbol"=> "OJB",
                    "bin"=> "970414",
                    "short_name"=> "Oceanbank",
                    "name"=> "Ngân hàng Thương mại TNHH MTV Đại Dương",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/OJB.png"
                ],[
                    "symbol"=> "VTLMONEY",
                    "bin"=> "971005",
                    "short_name"=> "ViettelMoney",
                    "name"=> "Viettel Money",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/VIETTELMONEY.png"
                ],[
                    "symbol"=> "SEAB",
                    "bin"=> "970440",
                    "short_name"=> "SeABank",
                    "name"=> "Ngân hàng TMCP Đông Nam Á",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/Seab.png"
                ],[
                    "symbol"=> "IBKHCM",
                    "bin"=> "970456",
                    "short_name"=> "IBKHCM",
                    "name"=> "Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh TP. Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/IBK.png"
                ],[
                    "symbol"=> "COB",
                    "bin"=> "970446",
                    "short_name"=> "COOPBANK",
                    "name"=> "Ngân hàng Hợp tác xã Việt Nam",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_coop_bank.png"
                ],[
                    "symbol"=> "MSB",
                    "bin"=> "970426",
                    "short_name"=> "MSB",
                    "name"=> "Ngân hàng TMCP Hàng Hải",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/MSB.png"
                ],[
                    "symbol"=> "ACB",
                    "bin"=> "970416",
                    "short_name"=> "ACB",
                    "name"=> "Ngân hàng TMCP Á Châu",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/ACB.png"
                ],[
                    "symbol"=> "NASB",
                    "bin"=> "970409",
                    "short_name"=> "BacABank",
                    "name"=> "Ngân hàng TMCP Bắc Á",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/NASB.png"
                ],[
                    "symbol"=> "CIMB",
                    "bin"=> "422589",
                    "short_name"=> "CIMB",
                    "name"=> "Ngân hàng TNHH MTV CIMB Việt Nam",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_cimb.png"
                ],[
                    "symbol"=> "VCCB",
                    "bin"=> "970454",
                    "short_name"=> "VietCapitalBank",
                    "name"=> "Ngân hàng TMCP Bản Việt",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VCCB.png"
                ],[
                    "symbol"=> "KBankHCM",
                    "bin"=> "668888",
                    "short_name"=> "KBank",
                    "name"=> "Ngân hàng Đại chúng TNHH Kasikornbank",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_kbank.png"
                ],[
                    "symbol"=> "CTG",
                    "bin"=> "970415",
                    "short_name"=> "VietinBank",
                    "name"=> "Ngân hàng TMCP Công thương Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/CTG.png"
                ],[
                    "symbol"=> "UOB",
                    "bin"=> "970458",
                    "short_name"=> "UnitedOverseas",
                    "name"=> "Ngân hàng United Overseas - Chi nhánh TP. Hồ Chí Minh",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/UOB.png"
                ],[
                    "symbol"=> "HLB",
                    "bin"=> "970442",
                    "short_name"=> "HongLeong",
                    "name"=> "Ngân hàng TNHH MTV Hong Leong Việt Nam",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_hong_leon_bank.png"
                ],[
                    "symbol"=> "NAB",
                    "bin"=> "970428",
                    "short_name"=> "NamABank",
                    "name"=> "Ngân hàng TMCP Nam Á",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/NAB.png"
                ],[
                    "symbol"=> "VIB",
                    "bin"=> "970441",
                    "short_name"=> "VIB",
                    "name"=> "Ngân hàng TMCP Quốc tế Việt Nam",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VIB.png"
                ],[
                    "symbol"=> "BVB",
                    "bin"=> "970438",
                    "short_name"=> "BaoVietBank",
                    "name"=> "Ngân hàng TMCP Bảo Việt",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/BVB.png"
                ],[
                    "symbol"=> "OCB",
                    "bin"=> "970448",
                    "short_name"=> "OCB",
                    "name"=> "Ngân hàng TMCP Phương Đông",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/OCB.png"
                ],[
                    "symbol"=> "TIMO",
                    "bin"=> "963388",
                    "short_name"=> "Timo",
                    "name"=> "Ngân hàng số Timo by Ban Viet Bank (Timo by Ban Viet Bank)",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/TIMO.png"
                ],[
                    "symbol"=> "NonghyupBankHN",
                    "bin"=> "801011",
                    "short_name"=> "Nonghyup",
                    "name"=> "Ngân hàng Nonghyup - Chi nhánh Hà Nội",
                    "logo_url"=> "https://img.mservice.io/momo_app_v2/new_version/All_team_/new_logo_bank/ic_nonghyu.png"
                ],[
                    "symbol"=> "MAFC",
                    "bin"=> "970468",
                    "short_name"=> "MiraeAsset",
                    "name"=> "Công ty Tài chính TNHH MTV Mirae Asset (Việt Nam)",
                    "logo_url"=> "https://img.mservice.com.vn/app/img/payment/MAFC.png"
                ],[
                    "symbol"=> "SCB",
                    "bin"=> "970429",
                    "short_name"=> "SCB",
                    "name"=> "Ngân hàng TMCP Sài Gòn",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/SCB.png"
                ],[
                    "symbol"=> "VAB",
                    "bin"=> "970427",
                    "short_name"=> "VietABank",
                    "name"=> "Ngân hàng TMCP Việt Á",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/VAB.png"
                ],[
                    "symbol"=> "GPB",
                    "bin"=> "970408",
                    "short_name"=> "GPBank",
                    "name"=> "Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu",
                    "logo_url"=> "https://img.mservice.com.vn/momo_app_v2/img/GPB.png"
                ]

        ];
        DB::table('banks')->insert($data);
    }
}
