<aside class="sidebar sidebar-user">
 <style>
			        .profile-pic {
	 color: transparent;
	 transition: all 0.3s ease;
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 position: relative;
	 transition: all 0.3s ease;
}
 .profile-pic input {
	 display: none;
}
 .profile-pic img {
	 position: absolute;
	 object-fit: cover;
	 width: 130px;
	 height: 130px;
	 border-radius: 100%;
	 z-index: 0;
}
 .profile-pic .-label {
	 cursor: pointer;
	 height: 130px;
	 width: 130px;
}
 .profile-pic:hover .-label {
	 display: flex;
	 justify-content: center;
	 align-items: center;
	 background-color: rgba(0, 0, 0, .8);
	 z-index: 10000;
	 color: #fafafa;
	 transition: background-color 0.2s ease-in-out;
	 border-radius: 100px;
	 margin-bottom: 0;
}
 .profile-pic span {
	 display: inline-flex;
	 padding: 0.2em;
	 height: 2em;
}
			    </style>
			    <script>
    var loadFile = function (event) {
  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);
  $('#profileButton').show();
};

</script>							
	<?php $iduser=Auth::user()->id;
	$sqluser="SELECT p_recruitment.foto,role,m_lokasi.nama as nmlokasi,p_karyawan.nama,nik FROM users
	left join p_karyawan on p_karyawan.user_id=users.id
	left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
	left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
	left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
	where users.id=$iduser";
	$user=DB::connection()->select($sqluser);?>
	<div class="user-card card shadow-sm bg-white text-center ctm-border-radius mb-1">
		<div class="user-info card-body">
			<div class="user-avatar mb-4">
			   
<?php

					use App\Helper_function;
					$help = new Helper_function();
					
					$sqlidkar="select *,p_karyawan.nama,h.nama as nmjabatan,i.nama as nmlokasi from p_karyawan 
		            	LEFT JOIN p_recruitment b on b.p_recruitment_id=p_karyawan.p_recruitment_id
					    left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
					    LEFT JOIN m_jabatan h on h.m_jabatan_id=p_karyawan_pekerjaan.m_jabatan_id
              LEFT JOIN m_lokasi i on i.m_lokasi_id=p_karyawan_pekerjaan.m_lokasi_id
              where user_id=$iduser";
					$idkar=DB::connection()->select($sqlidkar);?>
			<form action="{!!route('fe.update_profile',[$idkar[0]->p_karyawan_id,'type=profile'])!!}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
							
								    
									

<div class="profile-pic">
  <label class="-label" for="file">
    <span class="glyphicon glyphicon-camera"></span>
    <span>Change Image</span>
  </label>
  
  <input id="file" type="file" onchange="loadFile(event)" name="image" />
    @if($idkar[0]->foto!=null)
        <img src="{!! asset('dist/img/profile/'.$idkar[0]->foto) !!}" id="output" class="profile-user-img img-fluid img-circle" alt="File Belum diupload" />
  	@else
		<img src="{!! asset('dist/img/profile/user.png') !!}" class="profile-user-img img-fluid img-circle" alt="File Belum diupload">
	@endif
</div>
<div id="profileButton" style="display:none">
    
    <button type="submit" class="btn btn-primary btn-xs"> Simpan</button>
</div>
</form>

			</div>
			<div class="user-details"><b>
			    <p><?=
					 ''.$user[0]->nik;;?></p>
				<p><?=$user[0]->nama;?></p>
				<p><?php
					$id=$idkar[0]->p_karyawan_id;	
					echo $idkar[0]->nmlokasi;	echo '<br>';
					echo $idkar[0]->nmjabatan;
				
				echo '</b>';				
					
					//echo $id;die;
					//echo $id;die;
					$bawahan = '';
					$bawahan = (hirarki_bawahan($idkar[0]->m_jabatan_id,''));
					// echo $bawahan;
					function hirarki_bawahan($id,$e)
					{
					//echo 'e adalah'.$e.'id adalah '.$id.'<br>';
					$filter_Entitas = '';
		
			
					$sqljabatan="SELECT *,(select count(*) from m_jabatan_atasan where m_jabatan_atasan.m_atasan_id = a.m_jabatan_id) as countjabatan
					FROM m_jabatan_atasan a 
					join m_jabatan b on b.m_jabatan_id = a.m_jabatan_id 
					where m_atasan_id = $id ";
					//echo $sqljabatan;
					$jabatan=DB::connection()->select($sqljabatan);
					$return = array(); 
					//echo 'jallo';
					$e=count($jabatan);
					//$e = $jabatan[0]->count_jabatan;
					// echo '<br>';
					//echo '<br>';
					//print_r($e);
					return $e; 
					// print_r($return);
		
					}   
					?></p>
			</div>
		</div>
	</div>
	<?php

	if($bawahan){
	?>
<style>
details {
  padding-left: 16px;
  padding-top: 8px;
  position: relative;
  outline: none;
}
details:focus, details:active {
  outline: none;
}
details:last-child:before {
  height: 16px;
}
details:before {
  /*content: "";
  position: absolute;
  height: 100%;
  top: -8px;
  border-left: 1px dotted;*/
}
details[open] > summary::before {
  width: 16px;
}
details[open] > summary i::before {
  content: "";
}
details summary {
  color: #000000ff;
  padding-left: 16px;
  outline: none;
  list-style: none;
  
}
details summary::-webkit-details-marker {
  display: none;
}
details summary::before {
  content: "";
  position: absolute;
  width: 0px;
  top: 16px;
  left: 16px;
  border-bottom: 1px dotted;
}
details summary:focus, details summary:active {
  outline: none;
}
details summary:hover {
  color: #555;
  cursor: pointer;
}
details summary:hover .selector {
  transition: background-color 0.3s;
  background-color: rgba(73, 73, 74, 0.71);
}
details summary i {
  font-size: 18px;
  margin-left: -22px;
  margin-right: 20px;
  width: auto;
  background: #414042;
  border-radius: 6px;
}
details summary .selector {
  position: absolute;
  top: 0;
  left: -200px;
  right: 0;
  bottom: 0;
  height: 30px;
  pointer-events: none;
  border-bottom: 1px solid #383838;
  z-index: -1;
}.user-notification-block ul li a.menu-style {
  color: #7e8fa1;
  padding: 30px 5px;
}
  </style>

	<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card mb-1">
		<div class="card-body">
	

  
  <div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white ">
	
  <details class="list-group">
    <summary class="button-5 p-2 text-white list-group-item text-center" >
     
     
      Persetujuan
    </summary>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.listed")?>'">
       
        
        Persetujuan 
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.approval_pergantian_hari_libur")?>'">
        
        
        Persetujuan Hari Libur
      </summary>
    </details >
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.klarifikasi_absen")?>'">
       
        
        Persetujuan Klarifikasi Absen
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.approval_karyawan_baru")?>'">
       
        
        Persetujuan Pengajuan Karyawan 
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.appr_mudepro")?>'">
       
        
        Persetujuan Mutasi Demosi dan Promosi
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.approval_kpi")?>'">
        
        
        Persetujuan KPI
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.approval_parameter_kpi")?>'">
      
        
        Persetujuan Parameter Capaian KPI
      </summary>
    </details>
   
  </details>
  <details class="list-group">
    <summary class="button-5 p-2 text-white list-group-item text-center">
      Aktivitas Pemimpin
    </summary>
    <details  class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.kehadiran")?>'">
        
        
        Tinjauan Kehadiran
      </summary>
    </details><details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.laporan_atasan")?>'">
        
        
        Laporan
      </summary>
    </details><details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.penjadwalan_shift")?>'">
       
        
       Penjadwalan Shift
      </summary>
    </details><details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.hari_libur_shift")?>'">
       
        
      Jadwal Libur
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.karyawan_bawahan")?>'">
        
        
      Daftar Tim
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.perintah_lembur")?>'">
        
        
      Perintah Lembur
      </summary>
    </details><details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.karyawan_baru")?>'">
       
      Pengajuan Karyawan Baru
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.pa")?>'">
       
     Penilaian Kinerja
      </summary>
    </details>
    <details class="list-group-item text-center button-6">
      <summary onclick="window.location.href='<?=route("fe.mudepro")?>'">
       
     Mutasi, Demosi dan Promosi
      </summary>
    </details>
  </details>
</div>
</div>
</div>

			
	<?php }?>							<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card">
		<div class="card-body">
			<ul class="list-group">
				<li class="list-group-item text-center button-5"><a href="#" class="text-white">Aktivitas Harianku</a></li>
				
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.penilaian_kpi") !!}" class="text-dark">Penilaian KPI</a></li>
				<!--<li class="list-group-item text-center button-6"><a href="{!! route("fe.laporan_cuti") !!}" class="text-dark">Laporan Cuti</a></li>-->
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.status_persetujuan") !!}" class="text-dark">Status persetujuan</a></li>
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.chat_list") !!}" class="text-dark">Pesan</a></li>
			</ul>
		</div>
	</div>
</aside>