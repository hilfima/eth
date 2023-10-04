<ul ><?php foreach($chat_room as $chat_room){
	
	?>
	<li class=" <?=$chat_room->sisi=='karyawan'?'sent':'replies';?> d-grid">
					
					<p>  <?=$chat_room->pesan;?>
						<br>
						<span style="font-size: 10px"><?=date('H:i',strtotime($chat_room->time_send));?>
					</span>
					</p>
				</li>
				
<?php }?></ul>

    