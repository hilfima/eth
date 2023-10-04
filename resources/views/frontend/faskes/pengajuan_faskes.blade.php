@extends('layouts.app_fe')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content ">
	@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

	<div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Pengajuan Faskes</h4>


</div>
</div>
<form action="{!!route('fe.simpan_faskes')!!}" method="post" enctype="multipart/form-data">
	    {{ csrf_field() }}
<div class="card">
	<div class="card-body">
							<div class="form-group">
                                <label>Nama Pasien</label>
                                  <select class="form-control " id="tgl_absen" name="keluarga"  type="text" >
                                  <option value="-1"><?=$idkar[0]->nama;?>(Diri Sendiri)</option>
                                  <?php foreach($keluarga as $keluarga){?>
                                	<option value="<?=$keluarga->p_karyawan_keluarga_id;?>"><?=$keluarga->hubungan;?> - <?=$keluarga->nama;?></option>
                                  <?php } ?>
                                 </select>
                            </div><div class="form-group">
                                <label>Nama Penyakit</label>
                                  <input class="form-control " id="tgl_absen" name="penyakit"  type="text" placeholder="Nama Penyakit" >
                                
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                  <input class="form-control " id="numberBox" name="nominal" value="Rp " placeholder="Masukan Topik Masalah" onkeypress="handleNumber(event, 'Rp {15,3}')" min="0" max="<?php if(count($fasilitas)) echo $fasilitas[0]->saldo;else echo 0;?>">
                                    
                                
                            </div><div class="form-group">
                                <label>Foto</label>
                                  <input class="form-control " id="tgl_absen" name="file"  type="file" placeholder="Masukan Topik Masalah" >
                                
                            </div><div class="form-group">
                                <label>Tanggal Kebutuhan</label>
                                  <input class="form-control " id="tgl_absen" name="tanggal_kebutuhan"  type="date" >
                                
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                              
                                    <textarea class="form-control " id="tgl_absen" name="keterangan" value="" placeholder="Keterangan"></textarea>
                                    
                                
                            </div>
	
	<button type="submit" class="btn btn-primary">Simpan</button>
	</div>
</div>

</form>

</div>
</div>
<script>
	 $(function () {
       $( "#numberBox" ).change(function() {
       	let maxtext = $(this).attr('max');
		let result1 = maxtext.replace(/./g, "");
		 result1 = result1.replace(/Rp /g, "");
          var max = parseInt(result1);
          let mintext = $(this).attr('min');
		let result2 = maxtext.replace(/./g, "");
		 result2 = result2.replace(/Rp /g, "");
          var min = parseInt(result2);
          if ($(this).val() > max)
          {
              $(this).val(max);
          }
          else if ($(this).val() < min)
          {
              $(this).val(min);
          }       
        }); 
    });
	 
	 function handleNumber(event, mask) {
    /* numeric mask with pre, post, minus sign, dots and comma as decimal separator
        {}: positive integer
        {10}: positive integer max 10 digit
        {,3}: positive float max 3 decimal
        {10,3}: positive float max 7 digit and 3 decimal
        {null,null}: positive integer
        {10,null}: positive integer max 10 digit
        {null,3}: positive float max 3 decimal
        {-}: positive or negative integer
        {-10}: positive or negative integer max 10 digit
        {-,3}: positive or negative float max 3 decimal
        {-10,3}: positive or negative float max 7 digit and 3 decimal
    */
    with (event) {
        stopPropagation()
        preventDefault()
        if (!charCode) return
        var c = String.fromCharCode(charCode)
        if (c.match(/[^-\d,]/)) return
        with (target) {
            var txt = value.substring(0, selectionStart) + c + value.substr(selectionEnd)
            var pos = selectionStart + 1
        }
    }
    var dot = count(txt, /\./, pos)
    txt = txt.replace(/[^-\d,]/g,'')

    var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
    var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
    if (!txt.match('^' + (!sign?'':'-?') + '\\d*' + (!decimals?'':'(,\\d*)?') + '$')) return

    txt = txt.split(',')
    if (integers && txt[0] && count(txt[0],/\d/) > integers) return
    if (decimals && txt[1] && txt[1].length > decimals) return
    txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

    with (event.target) {
        value = mask[1] + txt.join(',') + mask[5]
        selectionStart = selectionEnd = pos + (pos==1 ? mask[1].length : count(value, /\./, pos) - dot) 
    }

    function count(str, c, e) {
        e = e || str.length
        for (var n=0, i=0; i<e; i+=1) if (str.charAt(i).match(c)) n+=1
        return n
    }
}
</script>
@endsection