@extends('layouts.appsA')

@section('content')


		<div class="card shadow-sm ctm-border-radius">
			<div class="card-body align-center">
				<h4 class="card-title float-left mb-0 mt-2"> Tambah Karyawan Agenda</h4>


			</div>
		</div>
		<form action="{!!route('be.save_tambah_karyawan_agenda',$id)!!}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="card">
				@include('flash-message')
				<div class="card-body">
				<div class="row">

					
					<div class="col-md-12">
						<div class="form-group">
							<label>Undang Karyawan</label>
							<select type="number" class="form-control select2" multiple="" id="nama" name="karyawan[]" placeholder="Keterangan" value="">
								<option value="">- Pilih Karyawan -</option>
								<?php foreach($karyawan as $karyawan){?>
								<option value="<?=$karyawan->p_karyawan_id?>"><?=$karyawan->nama?></option>
								<?php }?>
							</select>
						</div>
					</div>





				</div>

					<button type="submit" class="btn btn-primary">Simpan</button>
				
			</div>

		</form>

	</div>
</div>

@endsection