@extends('layouts.app_fe')



@section('content')
<div class="content-wrapper">
@include('flash-message')
<!-- Content Header (Page header) -->
<style>
	.sticky {
		position: fixed;
		top: 100px;
		width: 96%;
		z-index: 999
	}

	/* Add some top padding to the page content to prevent sudden quick movement (as the header gets a new position at the top of the page (position:fixed and top:0) */
	.sticky + .content {
		padding-top: 102px;
	}
</style>
<form action="{!!route('fe.selesai_baca_sop',$sop[0]->sop_id)!!}" method="post" enctype="multipart/form-data">
<div class="card" id="myHeaderSticky" >
	<div class="card-body align-center">
		<h3 class="card-title  mb-0 mt-2 text-center"  style="font-weight: 800"><?=$sop[0]->judul_sop;?></h3>

		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
			{{ csrf_field() }}
			<input type="hidden" name="waktu_awal" value="<?=date('Y-m-d H:i:s');?>"/>

			</li>

		</ul>

	</div>
</div><?php
$file=url('/dist/img/file/'. $sop[0]->file );
$pNum=isset($_GET['pNum'])?$_GET['pNum']:1;

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PDF Viewer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  
  <script language=JavaScript>
<!--

//Disable right mouse click Script
//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="Function Disabled!";

///////////////////////////////////
function clickIE4(){
if (event.button==2){
alert(message);
return false;
}
}

function clickNS4(e){
if (document.layers||document.getElementById&&!document.all){
if (e.which==2||e.which==3){
alert(message);
return false;
}
}
}

if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=clickNS4;
}
else if (document.all&&!document.getElementById){
document.onmousedown=clickIE4;
}

document.oncontextmenu=new Function("alert(message);return false")

// --> 
</script>
</head>
<body>
    <div align="center">
        <?php
                $total_pages = 500;
                ?>
                    <ul class="pagination">
						<li><a href="<?=route('fe.baca_sop',$sop[0]->sop_id)?>?pNum=1">First</a></li>
						<li class="<?php if($pNum <= 1){ echo "disabled"; } ?>">
						<a href="<?php if($pNum <= 1){ echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=1"; } else { echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=".($pNum - 1); } ?>">Prev</a>
						</li>
						<li class="<?php if($pNum >= $total_pages){ echo "disabled"; } ?>">
						<a target="_self" href="<?php if($pNum >= $total_pages){ echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=1"; } else { echo  route('fe.baca_sop',$sop[0]->sop_id)."?pNum=".($pNum + 1); } ?>">Next</a>
						</li>
                       
                    </ul>            
           <?php  
        ?>
        
        <br/>
		{{ csrf_field() }}
		
		<canvas id="the-canvas" style="border:1px  solid black"></canvas>
		<br/>
		<ul class="pagination">
			
			<li><a href="<?=route('fe.baca_sop',$sop[0]->sop_id)?>?pNum=1">First</a></li>
			<li class="<?php if($pNum <= 1){ echo "disabled"; } ?>">
				<a href="<?php if($pNum <= 1){ echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=1"; } else { echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=".($pNum - 1); } ?>">Prev</a>
			</li>
			<li class="<?php if($pNum >= $total_pages){ echo "disabled"; } ?>">
			<a target="_self" href="<?php if($pNum >= $total_pages){ echo route('fe.baca_sop',$sop[0]->sop_id)."?pNum=1"; } else { echo  route('fe.baca_sop',$sop[0]->sop_id)."?pNum=".($pNum + 1); } ?>">Next</a>
			</li>
			
		</ul>   		
		
		<form action="{!!route('fe.selesai_baca_sop',$sop[0]->sop_id)!!}" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="form-group d-flex content-justify-center" style="justify-content: center;align-items: center;display: flex;">

			<input type="checkbox" class="form-control " required  id="pernyataan" name="pernyataan" style="width: 15px"> Dengan ini saya menyatakan bahwa saya telah membaca, bersedia mematuhi dan menerapkan segala ketentuan peraturan perusahaan diatas

		</div>
		<div id="jika" class="text-muted"></div>

		<button  type="submit" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding" onclick="check()">
			Selesai Membaca
		</button>
		</form>
    </div>
    
    <script src="http://blogchem.com/kasmui/dokumen/build/pdf.js"></script>
    
    <script id="script">
      var url = "<?php echo $file; ?>";
    
      pdfjsLib.GlobalWorkerOptions.workerSrc =
        'http://blogchem.com/kasmui/dokumen/build/pdf.worker.js';
    
      var loadingTask = pdfjsLib.getDocument(url);
      loadingTask.promise.then(function(pdf) {
        
        pdf.getPage(<?php echo $pNum; ?>).then(function(page) {
          var scale = 1.5;
          var viewport = page.getViewport({ scale: scale, });
    
          var canvas = document.getElementById('the-canvas');
          var context = canvas.getContext('2d');
          canvas.height = viewport.height;
          canvas.width = viewport.width;
    
          var renderContext = {
            canvasContext: context,
            viewport: viewport,
          };
          page.render(renderContext);
        });
      });
    </script>
</body>
</html>
@endsection