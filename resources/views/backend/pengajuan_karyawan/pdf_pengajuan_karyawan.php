<table style="border-collapse: collapse;">
<tr>
	<td colspan="1"><?php 
						if($tkaryawan[0]->m_lokasi_id==3){
							$logo = 'Logo%20Rea%20Arta%20Mulia.png';
						}else if($$tkaryawan[0]->m_lokasi_id==4){
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
							$type = pathinfo(url('dist/img/logo/logo/'.$logo), PATHINFO_EXTENSION);
							$data = file_get_contents(url('dist/img/logo/logo/'.$logo));
							$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						//echo '<img style="width: 70px; height: 50px" src="'.url('dist/img/logo/logo/'.$logo) ."'>";
	?><img style="width: 70px; height: 50px" src="<?=$base64?>">
										</td>
	<td  colspan="5">FORM PERMOHONAN KARYAWAN</td>
</tr>

<tr>
	<td colspan="6"> <span style="padding: 10px">&#160;</span> </td>

</tr>


<tr>
	<td style="border:3px solid black">Nama Pemohon</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"><?=$tkaryawan[0]->nmpemohon?></td>
	
	<td style="border:3px solid black">Tanggal permintaan</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"><?=date('d m Y',strtotime($tkaryawan[0]->create_date));?></td>
</tr>


<tr>
	<td style="border:3px solid black">Jabatan yang dibutuhkan</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"><?=$tkaryawan[0]->namaposisi?></td>
	
	<td style="border:3px solid black">Tanggal Diperlukan</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"><?=date('d m Y',strtotime($tkaryawan[0]->tgl_diperlukan));?></td>
</tr>
<tr>
	<td style="border:3px solid black">Departemen/Bagian</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->nmdept?></td>
	
	
</tr>
<tr>
	<td style="border:3px solid black">Level</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->nmlevel?></td>
	
	
</tr>
<tr>
	<td style="border:3px solid black">Jumlah Kebutuhan</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->jumlah_dibutuhkan?> Orang</td>
	
	
</tr>

<tr>
	<td style="border:3px solid black">Lokasi</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->lokasi?> </td>
	
	
</tr>
<tr>
	<td colspan="6"> <span style="padding: 10px">&#160;</span> </td>
	
	
	
</tr>
<tr>
	<td colspan="6">Alasan Permintaan (beri tanda ceklis (v) pada kolom yang sesuai):  </td>
	
	
	
</tr>
<tr>
	<td  style="border:3px solid black">Penggantian karyawan atas nama :</td>
	<td  style="border:3px solid black">:</td>
	<td  style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->karyawan_pengganti	?> Orang</td>
	
	
</tr>
<tr>
	<td  style="border:3px solid black">Penambahan Karyawan, alasan </td>
	<td  style="border:3px solid black" >:</td>
	<td  style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->penambahan_karyawan?> </td>
	
	
</tr><tr>
	<td colspan="6"> <span style="padding: 10px">&#160;</span> </td>
	
	
	
</tr>
<tr>
	<td style="border:3px solid black">Gambarkan Posisi dalam Struktur Organisasi*</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->penambahan_karyawan?> </td>
	
	
</tr>

<tr>
	<td style="border:3px solid black">Uraian Pekerjaan*</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->penambahan_karyawan?> </td>
	
	
</tr>
<tr>
	<td colspan="6"> <span style="padding: 10px">&#160;</span> </td>
	
	
	
</tr>
<tr  >
	<td colspan="6">Kualifikasi yang dibutuhkan (isi atau beri tanda ceklis (v) pada kolom yang sesuai)</td>
	
	
	
</tr>
<tr>
	<td style="border:3px solid black">1. Usia</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_usia_dari?> s/d <?=$tkaryawan[0]->kualifikasi_usai_sampai?></td>

</tr>
<tr>
	<td style="border:3px solid black">2. Jenis Kelamin</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_jenis_kelamin?></td>

</tr>
<tr>
	<td style="border:3px solid black">3. Pendidikan Minimal/Jurusan</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_pendidikan?></td>
</tr>

<tr>
	<td style="border:3px solid black">4. Keterampilan/Keahlian Khusus</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_keahlian?></td>
</tr>
<tr>
	<td style="border:3px solid black">5. Pengalaman Kerja minimal</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_pengalaman?></td>
</tr>

<tr>
	<td style="border:3px solid black">6. Kompetensi lainnya</td>
	<td style="border:3px solid black">:</td>
	<td style="border:3px solid black"  colspan="4"><?=$tkaryawan[0]->kualifikasi_kompetisi?></td>

</tr>

</table>
