@extends('layouts.app_fe')

@section('content')
<Style>
	
#gallery{
  -webkit-column-count:4;
  -moz-column-count:4;
  column-count:4;
  
  -webkit-column-gap:20px;
  -moz-column-gap:20px;
  column-gap:20px;
}
@media (max-width:1200px){
  #gallery{
  -webkit-column-count:3;
  -moz-column-count:3;
  column-count:3;
    
  -webkit-column-gap:20px;
  -moz-column-gap:20px;
  column-gap:20px;
}
}
@media (max-width:800px){
  #gallery{
  -webkit-column-count:2;
  -moz-column-count:2;
  column-count:2;
    
  -webkit-column-gap:20px;
  -moz-column-gap:20px;
  column-gap:20px;
}
}
@media (max-width:600px){
  #gallery{
  -webkit-column-count:1;
  -moz-column-count:1;
  column-count:1;
}  
}
#gallery img,#gallery video {
  width:100%;
  height:auto;
  margin: 4% auto;
  box-shadow:-3px 5px 15px #000;
  cursor: pointer;
  -webkit-transition: all 0.2s;
  transition: all 0.2s;
}
.modal-img,.model-vid{
  width:100%;
  height:auto;
}
.modal-body{
  padding:0px;
}
</Style>
<div class="row">
    <div class="col-md-3">
    	
   <?= view('frontend.club.sidebar',compact('id')); ?>
    </div>
    <!-- Content Wrapper. Contains page content -->
   <div class="col-md-9">
        <div class="card shadow-sm ctm-border-radius">
<div class="card-body align-center">
<h4 class="card-title float-left mb-0 mt-2">Foto Kegiatan Club</h4>

</div>
</div>

<div id="gallery" class="container-fluid">  
<?php foreach($club as $club){
	echo '<img src="'.url('dist/img/file/'.$club->foto).'" class="img-responsive" onclick="preview_img(this)">';
}?>

</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body" id="preview">
      </div>
    </div>

  </div>
</div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
<script>
	
 function preview_img(e){
  	
  var t = $(e).attr("src");
  $("#preview").html("<img src='"+t+"' class='modal-img'>");
  $("#myModal").modal();
		}

</script>
@endsection