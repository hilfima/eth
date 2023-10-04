@extends('layouts.app_fe')



@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/froala_editor.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/froala_style.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/code_view.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/draggable.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/colors.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/emoticons.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/image_manager.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/image.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/line_breaker.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/table.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/char_counter.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/video.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/fullscreen.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/file.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/quick_insert.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/help.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/third_party/spell_checker.css">
  <link rel="stylesheet" href="<?=url('plugins\wysiwyg');?>/css/plugins/special_characters.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
<div class="content-wrapper">
	@include('flash-message')
	<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

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
	<div class="card shadow-sm ctm-border-radius">
		<div class="card-body align-center">
			<h4 class="card-title float-left mb-0 mt-2"> <?=($keluh_kesah[0]->judul) ?></h4>


		</div>
	</div>
	<div class="card ctm-border-radius shadow-sm flex-fill">

		<div class="card-body">
			<?=$keluh_kesah[0]->keluh_kesah; ?>
		</div>
		
	</div>
	<?php 
    foreach($balasan as $balasan){?>
    <div class="card ctm-border-radius shadow-sm flex-fill">

		<div class="card-header">
		  <?php
		    if($balasan->type==2){
		        echo 'Admin HC ';
		    }else{
		        echo 'Karyawan';
		    }
		  ?>
		  </div>
		<div class="card-body">
			<?=$balasan->pesan; ?>
		</div>

	</div>
    <?php }?>
    <form method="POST" action="{!! route('fe.simpan_keluh_kesah_detail') !!}" enctype="multipart/form-data">
	    {{csrf_field()}}
	<div class="card">
	    <div class="card-header">
	        Balasan
	    </div>
	<div class="card-body">
	    <input type="hidden" name="t_keluh_kesah_id" value="<?=$keluh_kesah[0]->t_keluh_kesah_id; ?>">
	    <div class="col-sm-12">
                            <div class="form-group">
                                <textarea id="edit" name="pesan" class="form-control " required></textarea>
                            </div>
                        </div>
	</div>
	
	    <div class="card-header">
	        <button type="submit" class="btn btn-primary">Simpan</button>
	    </div>
	</div>
	</form>
</div>
</div>
</div>
</form>
<script src="http://code.jquery.com/jquery-1.11.3.min.js"> </script>

<script src="http://code.jquery.com/jquery-1.11.3.min.js"> </script>

    <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.2.7/purify.min.js"></script>

  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/froala_editor.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/align.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/char_counter.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/code_beautifier.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/code_view.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/colors.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/draggable.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/emoticons.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/entities.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/file.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/font_size.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/font_family.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/fullscreen.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/image.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/image_manager.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/line_breaker.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/inline_style.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/link.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/lists.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/paragraph_format.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/paragraph_style.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/quick_insert.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/quote.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/table.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/save.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/url.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/video.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/help.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/print.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/third_party/spell_checker.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/special_characters.min.js"></script>
  <script type="text/javascript" src="<?=url('plugins\wysiwyg');?>/js/plugins/word_paste.min.js"></script>

  <script>
    (function () {
      new FroalaEditor("#edit")
    })()
  </script>
@endsection