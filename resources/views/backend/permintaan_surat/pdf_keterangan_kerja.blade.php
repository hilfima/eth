<style>

	header.1 {
                position: absolute;
                top: 0px;
                left: 0cm;
                right:0cm;
                width: 100%;
                height: 100%
            }header.2 {
                position: fixed;
                top: -60px;
               
                left: 500px;
            }
			@page { margin: 2cm 2.5cm 2.75cm 2.5cm; }
    </style>
   
           

<br><br><br><br><br><center><b><u>SURAT KETERANGAN KERJA</u></b><br>
No: ……………………………………………..</center>
<br>
<br>
Bismillaahirrohmaanirrohiim <br><br>
Yang bertanda tangan di bawah ini:<br /><br />
<table>
	<tr>
		<td>Nama</td>
		<td>:</td>
		<td><?=$direktur[0]->nama_direktur;?></td>
	</tr>
	<tr>
		<td>NIK</td>
		<td>:</td>
		<td><?=$direktur[0]->nik;?></td>
	</tr>
	<tr>
		<td>Jabatan</td>
		<td>:</td>
		<td><?=$direktur[0]->nama_jabatan_direktur;?></td>
	</tr>
</table><br />
Dengan ini menerangkan bahwa:
<br />
<br />
<table>
	<tr>
		<td>Nama</td>
		<td>:</td>
		<td><?=$surat[0]->nama_lengkap;?></td>
	</tr>
	<tr>
		<td>NIK</td>
		<td>:</td>
		<td><?=$surat[0]->nik;?></td>
	</tr>
	<tr>
		<td>Jabatan</td>
		<td>:</td>
		<td><?=$surat[0]->nama_jabatan;?></td>
	</tr>
</table><br />
<div style="text-align: justify;">
	
Adalah benar merupakan karyawan <?=$surat[0]->lokasi;?>. Yang bersangkutan sudah bekerja di perusahaan kami sejak  <?=$help->tgl_indo($surat[0]->tgl_bergabung);?> hingga saat ini yang bersangkutan telah bekerja dengan baik. 
Demikian surat keterangan ini dibuat, untuk dipergunakan sebagaimana mestinya.
<br><br>  
</div>

<div style="text-align: left; padding-left: 70%">
Bandung, <?=$help->tgl_indo(date('Y-m-d'));?>
<br>
<br>
<br>
<br>
<br>



<?=$direktur[0]->nama_direktur;?><br>
        Direktur
</div>


