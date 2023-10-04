<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>EMAIL APPROVAL HRMS</title>
    <style type="text/css">
        body {margin: 0; padding: 0; min-width: 100%!important;}
        .content {width: 100%; max-width: 600px;}

        @media only screen and (min-device-width: 601px) {
            .content {width: 600px !important;}
        }

        .header {padding: 40px 30px 20px 30px;}

        .col425 {width: 425px!important;}

        .subhead {font-size: 15px; color: #153643; font-family: sans-serif; letter-spacing: 10px;}
        .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
        .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}

        .innerpadding {padding: 30px 30px 30px 30px;}
        .borderbottom {border-bottom: 1px solid #f2eeed;}
        .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
        .bodycopy {font-size: 16px; line-height: 22px;}

        .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
        .button a {color: #ffffff; text-decoration: none;}

        @media only screen and (min-device-width: 601px) {
            .content {width: 600px !important;}
            .col425 {width: 425px!important;}
            .col380 {width: 380px!important;}
            .col760 {width: 760px!important;}
        }

        img {height: auto;}

        .footer {padding: 20px 30px 15px 30px;}
        .footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
        .footercopy a {color: #ffffff; text-decoration: underline;}

        @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .buttonwrapper {background-color: transparent!important;}
            body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important; display: block!important;}
        }

        body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}

        body[yahoo] .hide {display: none!important;}
    </style>
</head>
<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="header" bgcolor="#fff8dc">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <!--<table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td height="100" style="padding: 0 20px 20px 0;">
                                        <img src="http://203.210.84.185:90/dist/img/logo/ra.png" width="70" height="70" border="0" alt="" >
                                    </td>
                                </tr>
                            </table>-->
                            <tr>
                                <td class="subhead" style="padding: 5px 0 0 0;">
                                    Approval Email Pengajuan Izin
                                </td>
                            </tr>
                            <tr>
                                <td class="h1" style="padding: 5px 0 0 0;">
                                    Human Resources Management System
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="innerpadding borderbottom">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <table style="width: 100%;">
                                <tr>
                                    <td align="left" valign="top">No. Pengajuan</td>
                                    <td align="center" valign="top">:</td>
                                    <td align="left">{!! $data[0]->kode !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">NIK</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->nik !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">Nama</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->nama_lengkap !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">Jabatan</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->jabatan !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Departemen</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->departemen !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Tanggal Pengajuan</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! date('d-m-Y',strtotime($data[0]->create_date)) !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Jenis Cuti</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->nama_ijin !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Tanggal Awal</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! date('d-m-Y',strtotime($data[0]->tgl_awal)) !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Tanggal Akhir</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! date('d-m-Y',strtotime($data[0]->tgl_akhir)) !!}</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" style="width: 100px">Alasan</td>
                                    <td align="center" valign="top" style="width: 5px">:</td>
                                    <td align="left">{!! $data[0]->keterangan !!}</td>
                            </table>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="innerpadding borderbottom">
                        <table class="col760" align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 760px;">
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td style="padding: 20px 0 0 0;">
                                                <table class="buttonwrapper" bgcolor="#e05443" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="button" height="45">
                                                            <a href="{!! route('approve_izin',$data[0]->kode) !!}" target="_blank">Setuju</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td style="padding: 20px 0 0 0;">
                                                <table class="buttonwrapper" bgcolor="#e05443" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="button" height="45">
                                                            <a href="{!! route('reject_izin',$data[0]->kode) !!}" target="_blank">Tolak</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="footer" bgcolor="#44525f">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" class="footercopy">
                                    <strong>Copyright &copy; 2020 - <?php echo date('Y') ?> <a href="">ES-iOS HRMS</a>.</strong> All rights reserved.
                                    <!--<span class="hide">from this newsletter instantly</span>-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>