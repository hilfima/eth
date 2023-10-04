<ul ><?php foreach($chat_room as $chat_room){
	
	?>
	<div class="chat chat-<?=$chat_room->sisi=='karyawan'?'left':'right';?>">
														<div class="chat-body">
															<div class="chat-bubble">
																<div class="chat-content">
																	<p><?=$chat_room->pesan;?></p>
																	<span class="chat-time"><?=date('H:i',strtotime($chat_room->time_send));?></span>
																</div>
																
															</div>
														</div>
													</div>
	
				
<?php }?>