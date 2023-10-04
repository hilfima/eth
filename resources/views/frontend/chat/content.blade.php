<?php foreach($chat_room as $chat_room){
	
	?>
 <div class="msg <?=$chat_room->sisi=='karyawan'?'right':'left';?>-msg">
      
      <div class="msg-bubble">
        

        <div class="msg-text">
         <?=$chat_room->pesan;?>
        </div>
          <div class="msg-info-time" style="font-size: 9px"><?=date('H:i',strtotime($chat_room->time_send));?></div>
      </div>
    </div>
<?php }?>

    