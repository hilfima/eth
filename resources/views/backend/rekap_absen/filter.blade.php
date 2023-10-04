<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.rekapabsen') !!}">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-lg-5">
						<div class="form-group">
							<label>Periode Absen</label>
							<select class="form-control select2" name="periode_gajian" style="width: 100%;" required>
								<option value="">Pilih Periode</option>
								<?php
								foreach($periode AS $periode){
								if($periode->periode_absen_id==$periode_absen){
								echo '<option selected="selected" value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								else{
								echo '<option value="'.$periode->periode_absen_id.'">'.ucfirst($periode->tipe_periode).' | '.$periode->bulan.' - '.$periode->tahun.' - '.$periode->tipe.' - '.$periode->tgl_awal.' - '.$periode->tgl_akhir.'</option>';
								}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="form-group">
							<label>Rekap</label>
							<select class="form-control select2" name="rekapget" style="width: 100%;" required>
								<option value="Absen">Semua Absen</option>
								<?php
								$rekaplist[]='Rekap Lembur s/ Ajuan';
								$rekaplist[]='Rekap Lembur s/ Approve';
								$rekaplist[]='Rekap Izin';
								$rekaplist[]='Rekap Perdin';
								$rekaplist[]='Rekap Cuti';
								foreach($rekaplist AS $rekaplist){
								if($rekaplist==$rekapget){
									echo '<option selected="selected" value="'.$rekaplist.'">'.$rekaplist.'</option>';
								}
								else{
									echo '<option value="'.$rekaplist.'">'.$rekaplist.'</option>';
								}
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
								<label>Entitas</label>
								<select class="form-control select2" name="filterentitas" style="width: 100%;" >
									<option value="">Pilih Entitas</option>
									<?php
									foreach($entitas AS $entitas){
									$selected = '';
									if($entitas->m_lokasi_id==$request->filterentitas)
										$selected = 'selected';
										echo '<option value="'.$entitas->m_lokasi_id.'" '.$selected.'>'.$entitas->nama.'</option>';
									
									}
									?>
								</select>
							</div>
					</div><div class="col-lg-3">
						<div class="form-group">
								<label>Jabatan</label>
								<select class="form-control select2" name="filterjabatan" style="width: 100%;" >
									<option value="">Pilih Jabatan</option>
									<?php
									foreach($jabatan AS $jabatan){
									$selected = '';
									if($jabatan->m_jabatan_id==$request->filterjabatan)
										$selected = 'selected';
										echo '<option value="'.$jabatan->m_jabatan_id.'" '.$selected.'>'.$jabatan->nama.'</option>';
									
									}
									?>
								</select>
							</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label>Departement</label>
							<select class="form-control select2" name="departemen" style="width: 100%;" >
								<option value="">Semua Departemen</option>
								<?php
								foreach($departemen AS $departemen){
									echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
								}
								?>
							</select>
						</div>
					</div>
					
				</div>
				<div class="form-group">
					<div class="col-md-12">
						<a href="{!! route('be.rekap_absen') !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
						<button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
						<button type="submit" name="Cari" class="btn btn-primary" value="Generate"><span class="fa fa-file-excel"></span> Generate</button>
					</div>
				</div>
			</form>