@extends('layouts.appsA')

@section('content')
<div class="card">
<div class="card-body text-center" >
<h2>QR Acara</h2>
<h5>Silahkan Scan</h5>
<div id="qrcontent">
	<?php echo '<img src="'.url('bower_components/qrcode/qrcode.php?s=qrh&d='.$code).'"  width="250px"><br>';?>
</div>
</div>
</div>
<script type="text/javascript" src="<?= url('bower_components/qrcode/js/jquery.js');?>"></script>

<script>
	function generate(){
		
		$.ajax({
					type: 'get',
					url: '<?=route('be.generate_qr',$id)?>',
					dataType: 'html',
					success: function(data){
						
						$('#qrcontent').html(data);
            			
            			
					}
				});	
	}
	//generate();
	//setInterval(generate,60000)
</script>

@endsection