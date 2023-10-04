 <div class="user-card card shadow-sm bg-white text-center ctm-border-radius mb-1">
		<div class="user-info card-body">
			
			<div class="user-details">
				<p>Club</p>
				<h4><b><?php 
				$sqlclub="SELECT * FROM club
        		
                WHERE 1=1 and club_id = $id";
		        $club=DB::connection()->select($sqlclub);
		        echo $club[0]->nama;
				?></b></h4>
				
			</div>
		</div>
	</div>
	<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card">
		<div class="card-body">
			<ul class="list-group">
				<li class="list-group-item text-center button-5"><a href="#" class="text-white">Club</a></li>
				<!--<li class="list-group-item text-center button-6"><a href="{!! route("fe.laporan_cuti") !!}" class="text-dark">Laporan Cuti</a></li>-->
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.anggota_club",$id) !!}" class="text-dark">List Anggota</a></li>
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.kegiatan_club",$id) !!}" class="text-dark">Info Kegiatan</a></li>
				<li class="list-group-item text-center button-6"><a href="{!! route("fe.galeri_club",$id) !!}" class="text-dark">Galeri</a></li>
			</ul>
		</div>
	</div>