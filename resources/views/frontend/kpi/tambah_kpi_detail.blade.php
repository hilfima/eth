@extends('layouts.app_fe')

@section('content')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

    <!-- Content Wrapper. Contains page content -->
    <div class="content">
    @include('flash-message')
    <!-- Content Header (Page header) -->
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Tambah Parameter</h4>

</div>
</div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kpi_detail',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                            <!-- text input -->
                            <div class="form-group">
                                <label>Area Kerja</label>
                                
                                <input class="form-control" id="alasan" name="area_kerja" required="">
                                	
                            </div>
                           
                            
                            <div class="form-group" > 
                            	 <label>Sasaran Kerja</label>                               
                            	<textarea class="form-control" placeholder="Sasaran Kerja" id="alasan" name="sasaran_kerja" required="" ></textarea>
                            </div>
                            <div class="form-group" > 
                            	 <label>Definisi</label>                               
                            	<textarea class="form-control" placeholder="Definisi" id="alasan" name="definisi"required="" ></textarea>
                            </div>
                            <div class="form-group row" > 
                            <div class="col-md-6" > 
                            	 <label class="">Target</label>                               
                            	<input class="form-control" placeholder="Target" id="target" name="target" type="text" required="">
                           
                            </div>
                            <div class="col-md-6" > 
                            	 <label>Satuan</label>                               
                            	<select class="form-control" placeholder="Satuan" id="satuan" name="satuan" required="">
                            		<option value="persen">Persen</option>
                            		<option value="poin">Poin</option>
                            		<option value="nominal">Nominal</option>
                            	</select>
                            </div>
                            </div>
                            
                            <div class="form-group" > 
                            	 <label>Prioritas</label>                               
                            	<select class="form-control" placeholder="Area Kerja Lainnya" id="alasan" name="prioritas" required="" >
                            		<option value="">Prioritas</option>
                            		<option value="1">Sangat Rendah</option>
                            		<option value="3">Rendah</option>
                            		<option value="5">Sedang</option>
                            		<option value="7">Tinggi</option>
                            		<option value="9">Sangat Tinggi</option>
                            	</select>
                            </div>
                            <div class="card">
                            <div class="card-body">
                            	<h4>Breakdown Target</h4>
                            	<br>
                            
                       	<?php 
						$kpi = $kpi[0];
						$no = 0;
						
                    $tahun_awal = date('Y',strtotime(date($kpi->tanggal_awal)));
                        $bulan_awal = date('m',strtotime(date($kpi->tanggal_awal)));
                        $tahun_akhir = date('Y',strtotime(date($kpi->tanggal_akhir)));
                        $bulan_akhir = date('m',strtotime(date($kpi->tanggal_akhir)));
                       	for($i=$tahun_awal;$i<=$tahun_akhir;$i++){
                       		$tw = 1;
	                        $tw_akhir = 4;
	                        if($i==$tahun_awal){
	                            if(in_array($bulan_awal,array(1,2,3))){
                                    $tw=1;
                                }else if(in_array($bulan_awal,array(4,5,6))){
                                    $tw=2;
                                }else if(in_array($bulan_awal,array(7,8,9))){
                                    $tw=3;
                                }else if(in_array($bulan_awal,array(10,11,12))){
                                    $tw=4;
                                }
	                        }elseif($i==$tahun_akhir){
	                            if(in_array($bulan_akhir,array(1,2,3))){
                                    $tw_akhir=1;
                                }else if(in_array($bulan_akhir,array(4,5,6))){
                                    $tw_akhir=2;
                                }else if(in_array($bulan_akhir,array(7,8,9))){
                                    $tw_akhir=3;
                                }else if(in_array($bulan_akhir,array(10,11,12))){
                                    $tw_akhir=4;
                                }
	                        }
	                        echo "Tahun ".$i;
	                        echo '<div class="row">';
                       		for($j=$tw;$j<=$tw_akhir;$j++){
                       			$no++;?>
							<div class="col-md-3" > 
							<div class="form-group" > 
                            	 <label>Rencana TW-<?=$j;?> Tahun <?=$i;?></label>                               
                            	<input class="form-control rencana" placeholder="Rencana" type="text" id="alasan" name="rencana[<?=$i;?>][<?=$j;?>]" onkeyup="totalRENCANA()" required="" onkeypress="change_rencana(event)" onkeypress=""  onchange="handleNumber(event, 'Rp {-15,3}')"  value="0" data-literasi="<?=$no;?>">
                            </div>
                            </div>
							<?php }
							echo '</div>';
						}
                       	?>
                       	<script>
                       	    function change_rencana(e){
                       	        if($('#satuan').val()=='nominal'){
                       	            handleNumber(e, 'Rp {-15,3}')
                       	           
                       	        }else{
                       	            handleNumber(e, '{-15,3}')
                       	           
                       	        }
                       	    }
                       	</script>
                       	<div class="">
                       		<h4>Total Rencana</h4>
                       		<div id="totalRencanaContent"></div>
                       	</div>
                       	<div id="pesan_submit"></div>
                       	<input type="hidden" id="totalrencana">
                            </div>
                            </div>
                    </div>
                    
                    <!-- /.box-body -->
                    <div class="card-footer">
                    	
                        <a href="{!! route('fe.kpi_detail',$id) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" onsubmit="totalRENCANA()" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <script>
                    function totalRENCANA(){
                    	var sum = 0;
			            $('.rencana').each(function(){
			                sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
			            });
			            
			            target = $('#target').val();
			            satuan = $('#satuan').val();
			            $('#totalRencanaContent').html(sum +" "+satuan);
			            if(target>sum){
			            	$('#pesan_submit').html('<div class="alert alert-danger" role="alert">Total Rencana masih kurang dari Target</div>');
			            	return false;
			            }else{
			            	$('#pesan_submit').html('');
			            	return true;
			            }
                    }
                    function simpan(){
                    	var required = $('#formAdd input,#formAdd textarea,#formAdd select').filter('[required]:visible');
			            var allRequired = true;
			            required.each(function(){
			                if($(this).val() == ''){
			                    allRequired = false;
			                }
			            });
           		 		if(allRequired){
                 		$('#pesan_submit').html('');
                    	if(totalRENCANA())
                    		document.getElementById('formAdd').submit();
						}else{
							$('#pesan_submit').html('<div class="alert alert-danger" role="alert">Cek kembali isian</div>');
						}
                    }
                    	function change(e){
                    		if($(e).val()=='Lainnya'){
                    			$('#area_kerja_kontent').show();	
                    		}else{
                    			$('#area_kerja_kontent').hide();	
                    			
                    		}
                    	}
                    </script>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    	function change_jenis(e){
			var val = e.value;
			if(val==20)
			$('#file').attr('required', true);   
			else			
			$('#file').attr('required', false);   
		}
		function countdate()
		{
			if( $("#tgl_akhirdate").val() &&  $("#tgl_awaldate").val()){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url : "{!! route('fe.hitung_hari') !!}", 
				data : {'tgl_awal' : $("#tgl_awaldate").val(),'tgl_akhir' : $("#tgl_akhirdate").val(),'cuti' : $("#cuti").val()},
				type : 'get',
				dataType : 'json',
				success : function(result){
						$('#lama').val(result.count);
						//console.log("===== " + result + " =====");
					
				}
				
			});
	
	 		}
	 	}
	 	
		function countdate2(){
			var start = new Date($("#tgl_awaldate").val());
			var end = new Date($("#tgl_akhirdate").val());

//alert($("#tgl_awaldate").val());
			var loop = new Date(start);
			var count = 0;
			while(loop <= end){
			   var newDate = loop.setDate(loop.getDate() + 1);
			   loop = new Date(newDate);
			   //if()
			   count+=1;
			}
        	///alert(count);
          
            
           // $("#x_Date_Difference").val(diffDays);
       
		}
		$(".mask").inputmask('Regex', {regex: "^[0-9]{1,6}(\\,\\d{1,2})?$"});
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
function format(number, prefix='Rp ', decimals = 2, decimalSeparator = ',', thousandsSeparator = '.') {
  const roundedNumber = number.toFixed(decimals);
  let integerPart = '',
    fractionalPart = '';
  if (decimals == 0) {
    integerPart = roundedNumber;
    decimalSeparator = '';
  } else {
    let numberParts = roundedNumber.split('.');
    integerPart = numberParts[0];
    fractionalPart = numberParts[1];
  }
  integerPart =prefix+ integerPart.replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousandsSeparator}`);
  return `${integerPart}${decimalSeparator}${fractionalPart}`;
}
function rupiahtonumber(text){
	var chars = {'.':'',',':'.','R':'','p':'',' ':''};

text = text.replace(/[.,Rp ]/g, m => chars[m]);


 return text
}
function formatRupiah(angka, prefix){
				var reverse = angka.toString().split('').reverse().join(''),
				 ribuan = reverse.match(/\d{1,3}/g);
				 ribuan = ribuan.join('.').split('').reverse().join('');
				
				return prefix == undefined ? ribuan : (ribuan ? 'Rp ' + ribuan : '');
			}
    
    </script>
@endsection
