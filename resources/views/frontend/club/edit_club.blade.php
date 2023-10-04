@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
    
<div class="row">
	<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
		<?= view('layouts.app_side'); ?>
	</div>
	<div class="col-xl-9 col-lg-8 col-md-12">

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Club</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Club</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="card">
           
            <div class="card-body">
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
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.update_club',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nama Club</label>
                                <input type="text" class="form-control" placeholder="Nama Club ..." id="judul" name="data[nama]" value="<?=$club[0]->nama;?>" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Link Group</label>
                                <input type="text" class="form-control" placeholder="Link Group ..." id="judul" name="data[link_group]" value="<?=$club[0]->link_group;?>"required>
                            </div>
                        </div>
                      
                    
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea id="edit" name="date[keterangan]" class="form-control " required><?=$club[0]->keterangan;?></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.club') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
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
    <!-- /.content-wrapper -->
@endsection
