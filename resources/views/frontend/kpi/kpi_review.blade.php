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
         <form class="form-horizontal" method="get" action="{!! route('fe.kpi_review',[$id]) !!}" enctype="multipart/form-data">
                 {{ csrf_field() }}
             <div class="row mt-3">
                 <?php 
                 $total=0;
                    	if($kpi->status_appr_2==1){
                    ?>
                 <h4 class="card-title col-md-12 float-left mb-2 mt-2">KPI </h4>
           
                <div class="col-md-5 mb-3">
                   
                <select name="tahun_tw" class="form-control">
                    <option value="">Pilih</option>
                    <option value="semua">Semua</option>
                    <?php
                    $total =0;
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
                              for($j=$tw;$j<=$tw_akhir;$j++){
                                	$tahun = $i;
                                	$type = $j;
                                	$total+=1;
                                	echo '<option value="'.$tahun.'-'.$type.'">Tahun '.$tahun.' - TW '.$type.'</option>';
                              }
                            }
                    ?>
                    <option value=""></option>
                </select>
                </div>
            </div>
            <button type="submit"  class="btn btn-info mt-3"><span class="fa fa-check"></span> Cari</button>
            	<?php }else{
            		echo '<h3>KPI BELUM DI APPROVE, SILAHKAN UNTUK HUBUNGI ATASAN</h3>';	
            	}?>
            </form>
        </div>
    </div>
   
    <div id="contentKPI">
              <?php if(isset($_GET['tahun_tw'])){
                 if($_GET['tahun_tw']=='semua'){
                    
                 }else{
    $tw_tahun = explode('-',$_GET['tahun_tw']);
    $i = $tw_tahun[0];
    $j = $tw_tahun[1];
    
    
                 }
                       
    ?> 
             <form class="form-horizontal" method="POST" action="{!! route('fe.simpan_kpi_pencapaian_all',[$id,$i,$j]) !!}" enctype="multipart/form-data">
                 {{ csrf_field() }}
        <div style="overflow-x:auto;">
            <table id="" class="table table-bordered table-striped" >
                <thead>
                    
                    <tr>
                        <td rowspan="3">No.</td>
                        <td colspan="7">INDIKATOR KINERJA</td>
                        <td colspan="<?=4*($total+1)?>">Review KINERJA</td>
                    </tr>
                    <tr>
                    
                        <td rowspan="2">AREA  KERJA</td>
                        <td colspan="2">SASARAN DAN PARAMETER</td>
                        <td rowspan="2">TARGET</td>
                        <td rowspan="2">SATUAN</td>
                        <td>PRIORITAS</td>
                        <td>BOBOT</td>
                        <?php if($_GET['tahun_tw']=='semua'){
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
                              for($j=$tw;$j<=$tw_akhir;$j++){
                                	$tahun = $i;
                                	$type = $j;
                                	echo '<td colspan="4">'; 
                                     echo 'Tahun '. $i.' ';
                                    if($kpi->periode_kpi==1){
                                        echo 'Bulan';
                                    }else if($kpi->periode_kpi==2){
                                        echo 'Bulan';
                                    }else if($kpi->periode_kpi==3){
                                        echo 'TW';
                                    }else if($kpi->periode_kpi==4){
                                        echo 'tahun';
                                    }
                               echo $j;  
                                echo '
                                </td>
                                	';
                              }
                            }
                             }else{?>
                        
                        <td colspan="4"><?php 
                        echo 'Tahun '. $i.' ';
                                if($kpi->periode_kpi==1){
                                    echo 'Bulan';
                                }else if($kpi->periode_kpi==2){
                                    echo 'Bulan';
                                }else if($kpi->periode_kpi==3){
                                    echo 'TW';
                                }else if($kpi->periode_kpi==4){
                                    echo 'tahun';
                                }
                                ?><?=$j?>  
                                
                                </td>
                        <?php }?>    
                       
                        
                    <tr>
                        <td>SASARAN KERJA
</td>
                        <td>DEFINISI</td>
                        
                        <td>'1-3-5-7-9</td>
                        <td> (%)</td>
                        <?php for($x=0;$x<($_GET['tahun_tw']!='semua'?1:$total);$x++){?>
                        <td>Rencana</td>
                        <td >Realisasi</td>
                        <td>Pencapaian</td>
                        <td>Hasil</td>
                        <?php }?>
                    </tr>
                </thead>
                <?php 
                function content($capaian,$kpi_detail,$id,$i,$j,$no){
                    $appr = 	$sqlkpi="SELECT *
                       FROM t_kpi_pengajuan_appr a
                        left join t_kpi f on a.t_kpi_id = f.t_kpi_id
                       left join p_karyawan b on f.p_karyawan_id = b.p_karyawan_id 
                       
                       where a.t_kpi_id=$id  and a.tw = $j and a.tahun=$i ";
                $appr = DB::connection()->select($sqlkpi);
                $status_appr_pengajuan2=3;
                if(count($appr)){
                    $status_appr_pengajuan2=$appr[0]->status_appr_pengajuan2;
                    //print_r($appr);
                   // die; 
                }
                $tahun = $i;
                            	$type = $j;
                            	if(isset( $capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type])){
	                            	 	$realisasi=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['realisasi'];
	                            	 	$rencana=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['rencana'];
	                            	 	$pencapaian=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['pencapaian'];
	                            	 	$hasil=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['hasil'];
	                            	 	$appr_status=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['appr_status'];
	                            	 	$t_kpi_pencapaian_id=$capaian[$kpi_detail->t_kpi_detail_id][$tahun][$type]['t_kpi_pencapaian_id'];
	                            	 	$ada=1;
	                            	 }else{
	                            	 	$t_kpi_pencapaian_id=0;
	                            	 	$ada=0;
	                            	 	$realisasi=0;
	                            	 	$rencana=0;
	                            	 	$pencapaian=0;
	                            	 	$hasil=0;
	                            	 	$appr_status=3;
	                            	 }
                            echo '<input  type="hidden" name="id[]" value="'.$kpi_detail->t_kpi_detail_id.'" />
	                            	<input  type="hidden" name="ada['.$kpi_detail->t_kpi_detail_id.']" value="'.$ada.'" />
	                            	<input  type="hidden" name="capai_id['.$kpi_detail->t_kpi_detail_id.']" value="'.$t_kpi_pencapaian_id.'" />
                                ';
                                 $style = $appr_status==3?'style="color:red"':'';
                                 
                                if($kpi_detail->satuan=='persen'){
                                    echo '<td '.$style.'>'.$rencana.'%
                                    <input type="hidden" id="rencana-'.$no.'" value="'.$rencana.'">
                                    </td>';
                                    // if($realisasi)
                                    echo '<td '.$style.'>
                                    <input type="text" '.($status_appr_pengajuan2==3?'':'disabled').' class="form-control" name="realisasi['.$kpi_detail->t_kpi_detail_id.']"  id="realisasi-'.$no.'" value="'.$realisasi.'" onkeypress="handleNumber(event, '."'{-15,3}'".')"  onkeyup="change_realisasi('.$no.')">
                                    </td>';
                                    //  if($pencapaian)
                                     echo '<td '.$style.'><span id="pencapaian-content-'.$no.'">'.$pencapaian.'</span>%
                                    <input type="hidden" id="pencapaian-'.$no.'" name="pencapaian['.$kpi_detail->t_kpi_detail_id.']"  value="'.number_format($pencapaian,0,',','.').'">
                                    </td>';
                                    //  if($hasil)
                                    echo '<td '.$style.'><span id="hasil-content-'.$no.'">'.$hasil.'</span>%
                                    <input type="hidden" name="hasil['.$kpi_detail->t_kpi_detail_id.']" class="hasil" id="hasil-'.$no.'" value="'.$hasil.'">
                                    </td>';
                                }
                                else 
                                if($kpi_detail->satuan=='nominal'){
                                    echo '<td '.$style.'>'.number_format($rencana,0,',','.').'
                                    <input type="hidden" id="rencana-'.$no.'" value="'.$rencana.'"></td>';
                                    //  if($realisasi)
                                     echo '<td '.$style.'>
                                    <input type="text" '.($status_appr_pengajuan2==3?'':'disabled').' class="form-control"  style="width:200px" name="realisasi['.$kpi_detail->t_kpi_detail_id.']" id="realisasi-'.$no.'" value="'.$realisasi.'" onkeypress="handleNumber(event, '."'{-30,3}'".')" onkeyup="change_realisasi('.$no.')"></td>';
                                    //   if($pencapaian)
                                     echo '<td '.$style.'><span id="pencapaian-content-'.$no.'">'.number_format($pencapaian,0,',','.').'</span>
                                    <input type="hidden" name="pencapaian['.$kpi_detail->t_kpi_detail_id.']" id="pencapaian-'.$no.'" value="'.$pencapaian.'"></td>';
                                      //if($hasil)
                                     echo '<td '.$style.'><span id="hasil-content-'.$no.'">'.number_format($hasil,0,',','.').'</span>
                                    <input type="hidden" name="hasil['.$kpi_detail->t_kpi_detail_id.']" class="hasil" id="hasil-'.$no.'" value="'.$hasil.'">
                                    </td>';
                                }
                                
                                else 
                                if($kpi_detail->satuan=='poin'){
                                    echo '<td '.$style.'>'.$rencana.' Poin
                                    <input type="hidden" id="rencana-'.$no.'" value="'.$rencana.'"></td>';
                                    // if($realisasi)
                                    echo '<td '.$style.'>
                                    <input type="text" '.($status_appr_pengajuan2==3?'':'disabled').' class="form-control"   name="realisasi['.$kpi_detail->t_kpi_detail_id.']"id="realisasi-'.$no.'" value="'.$realisasi.'" onkeypress="handleNumber(event, '."'{-15,3}'".')"  onkeyup="change_realisasi('.$no.')"></td>';
                                    //  if($pencapaian)
                                    echo '<td '.$style.'><span id="pencapaian-content-'.$no.'">'.$pencapaian.'</span> Poin
                                    <input type="hidden" iname="pencapaian['.$kpi_detail->t_kpi_detail_id.']" d="pencapaian-'.$no.'" value="'.$pencapaian.'"></td>';
                                    //  if($hasil)
                                    echo '<td '.$style.'><span id="hasil-content-'.$no.'">'.$hasil.'</span> Poin
                                    <input type="hidden"  name="hasil['.$kpi_detail->t_kpi_detail_id.']" class="hasil" id="hasil-'.$no.'" value="'.$hasil.'">
                                    </td>';
                                }
              }
                ?>
                <tbody>
                    
                    <?php $no = 0;
                    // echo '<pre>';
                    // print_r($capaian);
                    ?>
                    @if(!empty($kpi_detail))
                    <?php $total_tw1=0;;?>
                    <?php $total_tw2=0;?>
                    <?php $total_tw3=0;?>
                    <?php $total_tw4=0;?>
                    @foreach($kpi_detail as $kpi_detail)
                    <?php $no++ ?>
                   
                    <tr>
                        <td>{!! $no !!}</td>
                        <td>{!! $kpi_detail->nama_area_kerja !!}</td>
                        <td>{!! $kpi_detail->sasaran_kerja !!}</td>
                        <td>{!! $kpi_detail->definisi !!}</td>
                        <td>
                            @if($kpi_detail->satuan=='nominal')
                            {!! number_format($kpi_detail->target,0,',','.') !!}
                            @else
                            {!! ($kpi_detail->target) !!}
                            @endif
                            </td>
                        <td>{!! ucwords($kpi_detail->satuan) !!}
                        <input type="hidden" name="satuan" value="{!! ($kpi_detail->satuan) !!}">
                        </td>
                        <td>{!! $kpi_detail->prioritas !!}</td>
                        <td>{!! round($bobot = $kpi_detail->prioritas/$kpi_detail->sum*100,2) !!}%
                        
                        
                                  <?=  '<input type="hidden" id="bobot-'.$no.'" value="'.$bobot.'">';?>
                                    </td><?php 
                                if($_GET['tahun_tw']=='semua'){
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
                                      for($j=$tw;$j<=$tw_akhir;$j++){
                                        	$tahun = $i;
                                        	$type = $j;
                                        echo  content($capaian,$kpi_detail,$id,$i,$j,$no);
                                      }
                                    }
                                }   else{
                            	echo  content($capaian,$kpi_detail,$id,$i,$j,$no);
                                }
                                ?>
                             
                          
                        
                    </tr>
                   
 					@endforeach
 					<tfoot>
 					    <tr>
 					        <td colspan=11></td>
 					        <td ><span id="all_total">0</span>%</td>
 					    </tr>
 					</tfoot>
 					
                    @endif
            </table>
        </div>
         <div class="card-footer">
        <?php if(isset($_GET['appr'])){
        
        if($kpi->atasan_1==$idkaryawan){
                            $nappr = 1;
                        }else if($kpi->atasan_2==$idkaryawan){
                            $nappr = 2;
                        }
                        $status = 'status_appr_pengajuan'.$nappr;
                        
                        if(count($appr)){
                        ?>
                        @if($appr[0]->$status==3)
                                        <a href="{!! route('fe.acc_kpi_pangajuan',[$appr[0]->t_kpi_pengajuan_appr_id,$nappr]) !!}" class="btn btn-success btn-sm"  title='Approve - 1 ' data-toggle='tooltip'> 
                                    	Approve  
                                    </a><a href="{!! route('fe.dec_kpi_pangajuan',[$appr[0]->t_kpi_pengajuan_appr_id,$nappr]) !!}" class="btn btn-danger btn-sm" title='Tolak - 1' data-toggle='tooltip'> 
                                    	Tolak    
                                    </a> 
                        @endif
                        <?php }?>

        
        <?php }else{
        if($_GET['tahun_tw']!='semua'){
        ?>
                        <a href="{!! route('fe.kpi_review',$id) !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="pull-right btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><span class="fa fa-check"></span> Simpan</button>
        <?php }}?>            
        </div>
    </div>
    <?php }?>
</div>
    <script>
    sum_hasil()
    function sum_hasil(){
         var sum = 0;
            $('.hasil').each(function(){
                if($(this).val())
                sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
            });
            //alert(sum);
            $('#all_total').html(sum.toFixed(2));
    } 
    function change_realisasi(id){
       
        	    rencana = parseFloat(rupiahtonumber($('#rencana-'+id).val()));
	    		realisasi = parseFloat(rupiahtonumber($('#realisasi-'+id).val()));
	    		bobot = parseFloat(($('#bobot-'+id).val()));
	    	//	alert(rencana);
	    		
	    		pencapaian = parseFloat(realisasi/rencana*100);
	    		hasil = parseFloat(pencapaian*bobot/100);
	    		$('#pencapaian-'+id).val(pencapaian.toFixed(2));
	    		$('#pencapaian-content-'+id).html(pencapaian.toFixed(2));
	    		$('#hasil-'+id).val(hasil.toFixed(2));
	    		$('#hasil-content-'+id).html(hasil.toFixed(2));
	    		sum_hasil();
    }
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