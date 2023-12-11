<style>
    p{
        margin:0;
    }
    td {
        font-size:10px;
    }
</style>
<table style="border-collapse: collapse;">
<tr>
	<td colspan="1"><?php 
						if($tkaryawan[0]->m_lokasi_id==3){
							$logo = 'Logo%20Rea%20Arta%20Mulia.png';
						}else if($tkaryawan[0]->m_lokasi_id==4){
							$logo = 'Logo%20EMM_Page12.png';
						}else if($tkaryawan[0]->m_lokasi_id==5){
							$logo = 'cc.png';
						}else if($tkaryawan[0]->m_lokasi_id==2){
							$logo = 'Logo%20SJP%20Guideline.png';
						}else  if($tkaryawan[0]->m_lokasi_id==9){
							$logo = 'Logo%20ASA.png';
						}else if($tkaryawan[0]->m_lokasi_id==13){
							$logo = 'Logo%20Mafaza%20Hires.png';
						}else if($tkaryawan[0]->m_lokasi_id==6){
							$logo = 'JKA%20LOGO.png';
						}else 
							$logo = 'logo.png';
							$type = pathinfo(url('dist/img/logo/'.$logo), PATHINFO_EXTENSION);
							$data = file_get_contents(url('dist/img/logo/'.$logo));
							$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						//echo '<img style="width: 70px; height: 50px" src="'.url('dist/img/logo/logo/'.$logo) ."'>";
	?><img style="width: 70px; height: 50px" src="<?=$base64?>">
										</td>
	<td  colspan="3" style="font-weight:700; text-align:center;font-size:12px">
	    <font size="12px">FORM PERMOHONAN KARYAWAN</font><br>
	   <b> <?=$tkaryawan[0]->nmentitas?></b>
	</td>
</tr>

<tr>
	<td colspan="3"> <span style="padding: 10px">&#160;</span> </td>

</tr>


<tr>
	<td style="">Nama Pemohon</td>
	<td style="text-align:center">:</td>
	<td style=""><?=$tkaryawan[0]->nmpemohon?></td>
</tr>


<tr>	
	<td style="">Tanggal permintaan</td>
	<td style="text-align:center">:</td>
	<td style=""><?=$help->tgl_indo(date('Y-m-d',strtotime($tkaryawan[0]->create_date)));?></td>
</tr>


<tr>
	<td style="">Jabatan yang dibutuhkan</td>
	<td style="text-align:center">:</td>
	<td style=""><?=$tkaryawan[0]->namaposisi?></td>
</tr>


<tr>	
	<td style="">Tanggal Diperlukan</td>
	<td style="text-align:center">:</td>
	<td style=""><?=$help->tgl_indo(date('Y-m-d',strtotime($tkaryawan[0]->tgl_diperlukan)));?></td>
</tr>
<tr>
	<td style="">Departemen/Bagian</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->nmdept?></td>
	
	
</tr>
<tr>
	<td style="">Level</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->nmlevel?></td>
	
	
</tr>
<tr>
	<td style="">Jumlah Kebutuhan</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->jumlah_dibutuhkan?> Orang</td>
	
	
</tr>

<tr>
	<td style="">Lokasi</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->lokasi?> </td>
	
	
</tr>
<!--<tr>-->
<!--	<td colspan="3"> <span style="padding: 10px">&#160;</span> </td>-->
	
	
	
<!--</tr>-->
<tr>
	<td >Alasan Permintaan </td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->alasan?> </td>
	
	
	<?=$tkaryawan[0]->alasan=='Pergantian Karyawan'?'selected':''?>
</tr>
<?php if($tkaryawan[0]->alasan=='Pergantian Karyawan' or $tkaryawan[0]->alasan=='Penambahan & Pergantian Karyawan' ){?>
<tr>
	<td  style="">Penggantian karyawan atas nama :</td>
	<td  style="">:</td>
	<td  style=""  ><?=$tkaryawan[0]->karyawan_pengganti	?> </td>
	
	
</tr>

<?php }?>
<?php if($tkaryawan[0]->alasan=='Penambahan Karyawan' or $tkaryawan[0]->alasan=='Penambahan & Pergantian Karyawan' ){?>

<tr>
	<td  style="">Alasan Penambahan Karyawan </td>
	<td  style="text-align:center" >:</td>
	<td  style=""  >,<?=$tkaryawan[0]->penambahan_karyawan?> </td>
	
	
</tr>

<?php }?>
<!--<tr>-->
<!--	<td colspan="3"> <span style="padding: 10px">&#160;</span> </td>-->
	
	
	
<!--</tr>-->
<tr>
	<td style="">Gambarkan Posisi dalam Struktur Organisasi</td>
	<td style="text-align:center">:</td>
	<td style="" ></td>
	
	
</tr>

<tr>
	<td style="vertical-align:top">Uraian Pekerjaan</td>
	<td  style="vertical-align:top;text-align:center">:</td>
	<td style="vertical-align:top"><?=($tkaryawan[0]->uraian_pekerjaan)?> </td>
	
	
</tr>
<!--<tr>-->
<!--	<td colspan="3"> <span style="padding: 10px">&#160;</span> </td>-->
	
	
	
<!--</tr>-->
<tr  >
	<td colspan="3" style="font-weight:600">Kualifikasi yang dibutuhkan </td>
	
	
	
</tr>
<tr>
	<td style="">1. Usia</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_usia_dari?> s/d <?=$tkaryawan[0]->kualifikasi_usai_sampai?></td>

</tr>
<tr>
	<td style="">2. Jenis Kelamin</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_jenis_kelamin?></td>

</tr>
<tr>
	<td style="">3. Pendidikan Minimal/Jurusan</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_pendidikan?></td>
</tr>

<tr>
	<td style="">4. Keterampilan/Keahlian Khusus</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_keahlian?></td>
</tr>
<tr>
	<td style="">5. Pengalaman Kerja minimal</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_pengalaman?></td>
</tr>

<tr>
	<td style="">6. Kompetensi lainnya</td>
	<td style="text-align:center">:</td>
	<td style=""  ><?=$tkaryawan[0]->kualifikasi_kompetisi?></td>

</tr>

</table>

     <br>
        <br>
        <!--<div  style="text-align:right;float:right">-->
        <!--_____________, ____________________-->
        <!--</div>-->
        <!--<br>-->
<table style="width: 100%">
            <tbody><tr>
                <td style="text-align: center;font-weight: 550;">USER</td>
                <td style="text-align: center;font-weight: 550;">ATASAN</td>
            </tr>
           
            <tr>
                <td>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

                </td>
                <td><br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
           
            <tr>
                <td style="text-align: center;">
                    _____________________________
                </td>
                <td style="text-align: center;">_____________________________
                </td>
                </tr>
            
        </tbody></table>
        <br>
        <br>
        <table style="width: 100%">
            <tbody>
            <tr>
                <td style="text-align: center;font-weight: 550;">DIREKSI</td>
                <td style="text-align: center;font-weight: 550;">HRD</td>
            </tr>
           
            <tr>
                <td><br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
                <td><br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
           
            <tr>
                <td style="text-align: center;">_____________________________
                </td>
                <td style="text-align: center;">_____________________________
                </td>
            </tr>
        </tbody></table>