<?php
echo view('layouts.header');?>
			<!-- /Header -->
			
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div class="sidebar-menu">
						
						<ul>
							<li> 
								<a href="{!!route('admin')!!}"><i class="la la-home"></i> <span>Back to Home</span></a>
							</li>
							<li> 
								<a href="#"  data-toggle="modal" data-target="#filterchat"><i class="la la-home"></i> <span>Filter</span></a>
							</li>
							<li class="menu-title"><span>Kasus Aktif</span> </li>
							<li > 
								<a href="javascript:void(0)" onclick="kasus_aktif(this)" data-visible=0 ><i class="la la-home"></i> <span>Show</span></a>
							</li>
							<script>
								function kasus_aktif(e){
									if($(e).attr("data-visible")=="1"){
										$('.kasus_aktif').hide();
										$(e).attr("data-visible",0);
									}else{
										$('.kasus_aktif').show();
										$(e).attr("data-visible",1);
										
									}
								}
							</script>
							<?php foreach($chat_list as $chat_room){
								if(!$chat_room->selesai){
									
								?>
							<li class="kasus_aktif">
								<a href="{!!route('be.view_chat',$chat_room->chat_room_id)!!}">
									<span class="chat-avatar-sm user-img">
										<span class="status <?= $chat_room->selesai==0?'online': 'busy	';?>"></span>
									</span> 
									<span class="chat-user"><?= $chat_room->nama;?>
									<p class="name" ><?= str_replace('Klarifikasi Absen Tanggal','',$chat_room->topik)?str_replace('Klarifikasi Absen Tanggal','',$chat_room->topik):$chat_room->tanggal;?><br><?php 
								  if($chat_room->tujuan==1) echo 'Absensi - Finger tidak terbaca'; else 
                                  if($chat_room->tujuan==2) echo 'Absensi - Izin'; else 
                                  if($chat_room->tujuan==3) echo 'Absensi - Sakit'; else 
                                  if($chat_room->tujuan==4) echo 'Absensi - Mesin absen Error'; else 
                                  if($chat_room->tujuan==5) echo 'Absensi - Lainnya'; else 
                                  if($chat_room->tujuan==6) echo 'Gaji -  Kelebihan bayar'; else 
                                  if($chat_room->tujuan==7) echo 'Gaji -  Kekurangan bayar'; else 
                                  if($chat_room->tujuan==8) echo 'Gaji -  Lainnya'; else 
                                  if($chat_room->tujuan==9) echo 'Lainnya';  ?></b> <br><?= $chat_room->deskripsi;?></p></span>
								</a>
							</li>
							
						<?php }?>
						<?php }?><li class="menu-title"><span>Kasus Selesai</span> </li>
							<li > 
								<a href="javascript:void(0)" onclick="kasus_selesai(this)" data-visible=0 ><i class="la la-home"></i> <span>Show</span></a>
							</li>
							<script>
								function kasus_selesai(e){
									if($(e).attr("data-visible")=="1"){
										$('.kasus_selesai').hide();
										$(e).attr("data-visible",0);
									}else{
										$('.kasus_selesai').show();
										$(e).attr("data-visible",1);
										
									}
								}
							</script>
							
							
							<?php foreach($chat_list as $chat_room){
								if($chat_room->selesai){
									
								?>
							<li class="kasus_selesai" style="display: none">
								<a href="{!!route('be.view_chat',$chat_room->chat_room_id)!!}">
									<span class="chat-avatar-sm user-img">
										<span class="status <?= $chat_room->selesai==0?'online': 'busy	';?>"></span>
									</span> 
									<span class="chat-user"><?= $chat_room->nama;?>
									<p class="name" ><?= $chat_room->deskripsi;?><?php 
									if($chat_room->tujuan==1) echo 'Absensi - Finger tidak terbaca'; else 
                                  if($chat_room->tujuan==2) echo 'Absensi - Izin'; else 
                                  if($chat_room->tujuan==3) echo 'Absensi - Sakit'; else 
                                  if($chat_room->tujuan==4) echo 'Absensi - Mesin absen Error'; else 
                                  if($chat_room->tujuan==5) echo 'Absensi - Lainnya'; else 
                                  if($chat_room->tujuan==6) echo 'Gaji -  Kelebihan bayar'; else 
                                  if($chat_room->tujuan==7) echo 'Gaji -  Kekurangan bayar'; else 
                                  if($chat_room->tujuan==8) echo 'Gaji -  Lainnya'; else 
                                  if($chat_room->tujuan==9) echo 'Lainnya';  ?></b> - <?= $chat_room->topik;?><br></p></span>
								</a>
							</li>
							
						<?php }?>
						<?php }?>
						</div>
						</ul>
						<br>
						<br>
						<br>
						<br>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
				<!-- Chat Main Row -->
				<div class="chat-main-row">
				
					<!-- Chat Main Wrapper -->
					<div class="chat-main-wrapper">
					
						<!-- Chats View -->
						<div class="col-lg-9 message-view task-view">
							<div class="chat-window">
								<div class="fixed-header">
									<div class="navbar">
										<div class="user-details mr-auto">
											<div class="float-left user-img">
												<a class="avatar" href="profile.html" title="Mike Litorus">
													
													<span class="status online"></span>
												</a>
											</div>
											<div class="user-info float-left">
											<?php 
											if($id_chat and $id_chat!=-1){
												echo '
												<a href="#"><span>'.$c->nama.' <Br>  '.$c->topik.'</span> <i class="typing-text">';
												if($c->tujuan==1) echo 'Absensi - Finger tidak terbaca'; else
												if($c->tujuan==2) echo 'Absensi - Izin'; else
												if($c->tujuan==3) echo 'Absensi - Sakit'; else 
				                                  if($c->tujuan==4) echo 'Absensi - Mesin absen Error'; else 
				                                  if($c->tujuan==5) echo 'Absensi - Lainnya'; else 
				                                  if($c->tujuan==6) echo 'Gaji -  Kelebihan bayar'; else 
				                                  if($c->tujuan==7) echo 'Gaji -  Kekurangan bayar'; else 
				                                  if($c->tujuan==8) echo 'Gaji -  Lainnya'; else 
				                                  if($c->tujuan==9) echo 'Lainnya';  
												  echo '...</i></a>';
												
												echo '<span class="last-seen">'.$c->deskripsi.'</span>';
												if(!empty($c->file))
													echo '<a href="'.asset('dist/img/file/'.$c->file) .'" target="_blank" title="Download"><span class="fa fa-download"></span></a>';
											}else{
												$id_chat=-1;
											}
											?>
												
												
											</div>
										</div>
										<div class="search-box">
											
										</div>
										<ul class="nav custom-menu">
											<li class="nav-item">
												<a class="nav-link task-chat profile-rightbar float-right" id="task_chat" href="#task_window"><i class="fa fa-user"></i></a>
											</li>
											
											<li class="nav-item dropdown dropdown-action">
												<a aria-expanded="false" data-toggle="dropdown" class="nav-link dropdown-toggle" href=""><i class="fa fa-cog"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<?php 
													if(isset($c->chat_room_id)){?>
													<a href="<?= route('be.selesai_chat',$c->chat_room_id)?>" class="dropdown-item">
														Kasus Telah Selesai
													</a>
													<?php }?>
													
													
													
													
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="chat-contents">
									<div class="chat-content-wrap">
										<div class="chat-wrap-inner">
											<div class="chat-box">
												<div class="chats messages" id="contentMassage" >
													
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="chat-footer">
									<div class="message-bar">
										<div class="message-inner">
											<a class="link attach-icon" href="#" data-toggle="modal" data-target="#drag_files"><img src="assets/img/attachment.png" alt=""></a>
											<div class="message-area">
												<div class="input-group">
													<textarea class="form-control msger-input" placeholder="Type message..."></textarea>
													<span class="input-group-append">
														<button class="btn btn-custom" type="button" onclick="send()"><i class="fa fa-send" ></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- /Chats View -->
						
						<!-- Chat Right Sidebar -->
						
						<!-- /Chat Right Sidebar -->
						
					</div>
					<!-- /Chat Main Wrapper -->
					
				</div>
				<!-- /Chat Main Row -->
				
				<!-- Drogfiles Modal -->
				<div id="drag_files" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-md" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Drag and drop files upload</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
                                <form id="js-upload-form">
									<div class="upload-drop-zone" id="drop-zone">
										<i class="fa fa-cloud-upload fa-2x"></i> <span class="upload-text">Just drag and drop files here</span>
									</div>
                                    <h4>Uploading</h4>
                                    <ul class="upload-list">
                                        <li class="file-list">
                                            <div class="upload-wrap">
                                                <div class="file-name">
                                                    <i class="fa fa-photo"></i>
                                                    photo.png
                                                </div>
                                                <div class="file-size">1.07 gb</div>
                                                <button type="button" class="file-close">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                            <div class="progress progress-xs progress-striped">
												<div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
											</div>
                                            <div class="upload-process">37% done</div>
                                        </li>
                                        <li class="file-list">
                                            <div class="upload-wrap">
                                                <div class="file-name">
                                                    <i class="fa fa-file"></i>
                                                    task.doc
                                                </div>
                                                <div class="file-size">5.8 kb</div>
                                                <button type="button" class="file-close">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                            <div class="progress progress-xs progress-striped">
												<div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
											</div>
                                            <div class="upload-process">37% done</div>
                                        </li>
                                        <li class="file-list">
                                            <div class="upload-wrap">
                                                <div class="file-name">
                                                    <i class="fa fa-photo"></i>
                                                    dashboard.png
                                                </div>
                                                <div class="file-size">2.1 mb</div>
                                                <button type="button" class="file-close">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                            <div class="progress progress-xs progress-striped">
												<div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
											</div>
                                            <div class="upload-process">Completed</div>
                                        </li>
                                    </ul>
                                </form>
								<div class="submit-section">
									<button class="btn btn-primary submit-btn">Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="filterchat" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Filter Ajuan</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">x</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="{!!route('be.view_chat',$id_chat)!!}" method="get" enctype="multipart/form-data">
								{{ csrf_field() }}
									<div class="form-scroll">
										
											<div class="form-group">
                                <label>Masalah</label>
                                  <select class="form-control " id="tgl_absen" name="tujuan" value="" placeholder="Masukan Topik Masalah">
                                  <option value="">Pilih Masalah</option>
                                  <option value="1">Absensi - Finger tidak terbaca</option>
                                  <option value="2">Absensi - Izin</option>
                                  <option value="3">Absensi - Sakit</option>
                                  <option value="4">Absensi - Mesin absen Error</option>
                                  <option value="5">Absensi - Lainnya</option>
                                  <option value="6">Gaji -  Kelebihan bayar</option>
                                  <option value="7">Gaji -  Kekurangan bayar</option>
                                  <option value="8">Gaji -  Lainnya</option>
                                  <option value="9">Lainnya</option>
                                 

                                   </select>
										
									</div><div class="form-group">
                                <label>Aktif</label>
                                  <select class="form-control " id="tgl_absen" name="selesai" value="" placeholder="Masukan Topik Masalah">
                                  <option value="">Pilih Status</option>
                                  <option value="1">Aktif</option>
                                  <option value="0">Selesai</option>
                                 

                                   </select>
										
									</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Drogfiles Modal -->
				
				<!-- Add Group Modal -->
				
				<!-- /Add Group Modal -->
				
				<!-- Add Chat User Modal -->
				
				<!-- /Add Chat User Modal -->
				
				<!-- Share Files Modal -->
				
				<!-- /Share Files Modal -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
		
		<!-- Sidebar Overlay -->
		<div class="sidebar-overlay" data-reff=""></div>
		
<!-- large modal -->
		<!-- jQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>      

<script>
	function content(){
		//alert();
		$.ajax({
				type: 'get',
				//data: {'id': id},
				url: '<?=route('be.content_chat',$id_chat);?>',
				dataType: 'html',
				success: function(data){
					$('#contentMassage').html(data);
					//document.getElementById('contentMassage').scrollIntoView(); 
					//var tgl_cicilan = myDate.addMonths(cicilan);
						
					
					
				    //console.log(data);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
	}
	function send(){
		//alert();
		var pesan = $('.msger-input').val();
		$.ajax({
				type: 'get',
				data: {'pesan': pesan},
				url: '<?=route('be.send_chat',$id_chat);?>',
				dataType: 'html',
				success: function(data){
					//$('.msger-chat').html(data);
					
					//var tgl_cicilan = myDate.addMonths(cicilan);
						$('.msger-input').val('')
						setTimeout(function(){  document.getElementById('contentMassage').scrollTop=document.getElementById('contentMassage').scrollHeight ; }, 1000);
					
					
				    //console.log(data);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
	}
	
	$(document).ready(function(){
  		content();
  		setInterval(content, 1000);
  		
	});
</script>
        <?php echo view('layouts.footer');?>   