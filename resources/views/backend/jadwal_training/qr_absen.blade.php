@extends('layouts.appsA')

@section('content')
<!DOCTYPE html>
 <link rel='stylesheet' href='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.css'><link rel="stylesheet" href="<?=url('bower_components\sweetalert\style.css')?>">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="<?= url('bower_components/qrcode/js/jquery.min.js');?>"></script>
<script src="<?= url('bower_components/qrcode/js/bootstrap.min.js');?>"></script>
    <div class="card center text-center p-3">
      <div class="panel-heading">
        <h3 class="panel-title">Arahkan Kode Kamera ke QR!</h3>
      </div>
      <div class="panel-body text-center" >
      <button class="btn btn-success" onclick="startcam()">start</button>
        <canvas></canvas>
        <hr>
        <select></select>
      </div>
      <div class="panel-footer">
          <center><a class="btn btn-danger" href="../">Kembali</a></center>
      </div>
    </div>

<!-- Js Lib -->
<script type="text/javascript" src="<?= url('bower_components/qrcode/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?= url('bower_components/qrcode/js/qrcodelib.js');?>"></script>
<script type="text/javascript" src="<?= url('bower_components/qrcode/js/webcodecamjquery.js');?>"></script>
 <script src='https://cdn.rawgit.com/t4t5/sweetalert/v0.2.0/lib/sweet-alert.min.js'></script>
 <script  src="<?=url('bower_components\sweetalert\script.js')?>"></script>
<script type="text/javascript">
	
    var arg = {
        resultFunction: function(result) {
           		id_acara = result.code;
					$.ajax({
					type: 'get',
					url: '<?=url("backend/");?>/save_absen/<?=$id;?>/'+id_acara,
					dataType: 'json',
					success: function(data){
						//$('#loading').html(data.respons);
            			
            			if(data.success==1){
            				swal("Sukses!!", "Selamat Datang Peserta "+data.respons+"", "success");
							
						}else if(data.success==2){
            				swal("Gagal!!", "Kode tidak terdaftar sebagai peserta..", "danger");
						}else if(data.success==3){
            				swal("Gagal!!", "Peserta "+data.respons+" sudah terabsen pada "+data.tanggal+" pukul "+data.jam, "danger");
						}else if(data.success==4){
            				swal("Gagal!!", "Kode tidak terdaftar sebagai peserta..", "danger");
							
						}else{
            				swal("Gagal!!", "Absensi Gagal..", "danger");
							
						}
						
						decoder.stop()
					}
				});
        }
    };
    
    var decoder = $("canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
    decoder.buildSelectMenu("select");
   function startcam(){
    decoder.play();
   }
    /*  Without visible select menu
        decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
    */
    $('select').on('change', function(){
        decoder.stop().play();
    });

    // jquery extend function
    $.extend(
    {
        redirectPost: function(location, args)
        {
            var form = '';
            $.each( args, function( key, value ) {
                form += '<input type="hidden" name="'+key+'" value="'+value+'">';
            });
            $('<form action="'+location+'" method="POST">'+form+'</form>').appendTo('body').submit();
        }
    });

</script>
</body>
</html>
@endsection