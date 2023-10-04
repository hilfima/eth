<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }public function chat_list(Request $request,$id_chat=null)
    {
    	
		$where = '';
		if($request->get('tujuan'))
		$where .= 'and tujuan = '.$request->get('tujuan');
		if($request->get('selesai'))
		$where .= ' and selesai = '.$request->get('selesai');
		$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
		left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  $where
    	order by created_by,tujuan ";
    	$chat_list=DB::connection()->select($chat_list);
    	
		if ($id_chat and $id_chat!=-1) {

			$chat= "select chat_room.*,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id 
    				left join p_karyawan b on appr = b.p_karyawan_id where chat_room_id = $id_chat 
    	
    	order by  created_date desc";
    	$c=DB::connection()->select($chat);
		print_r($chat);
    	$c= $c[0];
		}else{
			$c=null;
			
		}
    	
    	
    	
    	return view('backend.chat.chat_list',compact('chat_list','id_chat','c'));
    }
    public function simpan_chat(Request $request)
    {  
    	DB::beginTransaction();
        try{
        	
          
				$SQL = "SELECT max(chat_room_id) as max from chat_room";
                $chat_room=DB::connection()->select($SQL);
                $id_chat = ($chat_room[0]->max)+1;
               // echo print_r($chat_room[0]);echo $id_chat;
               // die;
           	 DB::connection()->table("chat_room")
                ->insert([
                   
                    "topik"=>($request->get("topik")),
                    "deskripsi"=>($request->get("keterangan")),
                   
                    "tujuan"=>($request->get("tujuan")),
                   
                  
                    "chat_room_id" => $id_chat,
                    "p_karyawan_create_id" => $iduser,
                    "created_by" => $iduser,
                    "created_date" => date("Y-m-d H:i:s")
                ]);
              

           
				
				
			
            DB::commit();
            return redirect()->route('be.chat',$id_chat)->with('success','Topik Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

	} public function update_klarifikasi(Request $request, $Id)
    {  
    	DB::beginTransaction();
        try{
        	
			$iduser=Auth::user()->id;
				
               // echo print_r($chat_room[0]);echo $id_chat;
               // die;
           	 DB::connection()->table("chat_room")
                ->where('chat_room_id',$Id)
                ->update([
                   
				"appr_hr_status"=>($request->get("appr_hr_status")),
				"appr_hr_by"=>$iduser,
				"selesai"=>($request->get("selesai")),
				"keterangan_hr"=>($request->get("keterangan_hr")),
                    
                    "appr_hr_date" => date("Y-m-d H:i:s")
                ]);
				if ($request->file('file_hr')) {
					//echo 'masuk';die;
					$file = $request->file('file_hr');
					$destination="dist/img/file/";
					$path='hr_klarifikasi-'.date('ymdhis').'-'.$file->getClientOriginalName();
					$file->move($destination,$path);
					//echo $path;die;
					DB::connection()->table("chat_room")
					->where("chat_room_id",$Id)
					->update([
						"file"=>$path
					]);
				}

           	if($request->selesai=="0") $selesai ="Proses HR";
           	else if($request->selesai=="1") $selesai ="Masuk Proses Atasan, menunggu approval dari atasan terkait";
			else if($request->selesai=="2") $selesai ="Disetujui Atasan dan Menunggu tindak lanjut HC";
			else if($request->selesai=="3") $selesai ="Selesai";
			else if($request->selesai=="4") $selesai ="Karyawan Selesai Konfirmasi, menunggu tindak lanjut HC";
			else if($request->selesai=="5") $selesai ="Klarifikasi tidak bisa ditindak lanjuti";
			else if($request->selesai=="6") $selesai ="Masuk Proses konfirmasi Karyawan";
			
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				$kode = $Id;
			$notifdata=DB::connection()->select("select * from chat_room where chat_room_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$iduser,
                        "database_from"=>"chat_room",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_create_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Klarifikasi Absen/Gaji  pada tanggal ".$notifdata[0]->tanggal." $selesai",
             ]);
				
			
            DB::commit();
			return redirect()->route('be.klarifikasi_absen',$Id)->with('success','Topik Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

	}public function selesai_klarifikasi(Request $request, $Id)
    {  
    	DB::beginTransaction();
        try{
        	
          
               // echo print_r($chat_room[0]);echo $id_chat;
               // die;
           	 DB::connection()->table("chat_room")
                ->where('chat_room_id',$Id)
                ->update([
				"selesai"=>3,
                ]);
				
           
				
			$selesai ="Selesai";	
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
				$kode = $Id;
			$id=$idkar[0]->p_karyawan_id;
			$notifdata=DB::connection()->select("select * from chat_room where chat_room_id=$kode");
            DB::connection()->table("notifikasi")
                    ->insert([
                        "id_assign"=>$id,
                        "database_from"=>"chat_room",
                        "datebase_id"=>$kode,
                        "p_karyawan_id"=>$notifdata[0]->p_karyawan_create_id,
                        "date_action"=>date('Y-m-d H:i:s'),
                        "notif"=>"Klarifikasi Absen/Gaji  pada tanggal ".$notifdata[0]->tanggal." $selesai",
             ]);
            DB::commit();
			return redirect()->route('be.klarifikasi_absen',$Id)->with('success','Topik Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

	}
	public function selesai_chat($id_chat)
    {  
    	DB::beginTransaction();
        try{
        	
           $iduser=Auth::user()->id;
			
               // echo print_r($chat_room[0]);echo $id_chat;
               // die;
           	 DB::connection()->table("chat_room")
           	 ->where('chat_room_id',$id_chat)
                ->update([
                   
                    "selesai"=>($id_chat),
                    
                ]);
              

           
				
				
			
            DB::commit();
            return redirect()->route('be.view_chat',$id_chat)->with('success','Topik Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

	}
	public function send_chat(Request $request,$id_chat)
    {  
    	DB::beginTransaction();
        try{
        	
           $iduser=Auth::user()->id;
			
				$SQL = "SELECT max(chat_room_message_id) as max from chat_room_message";
                $chat_room=DB::connection()->select($SQL);
                $id_chat_m = ($chat_room[0]->max)+1;
                
                $SQL = "SELECT max(sort) as max from chat_room_message where chat_room_id=$id_chat";
                $chat_room=DB::connection()->select($SQL);
                $sort= ($chat_room[0]->max)+1;
               // echo print_r($chat_room[0]);echo $id_chat;
               // die;
           	 DB::connection()->table("chat_room_message")
                ->insert([
                   
                    "pesan"=>($request->get("pesan")),
                    "pengirim"=>($iduser),
                    "sisi"=>'admin',
                    "read_pihak_2"=>1,
                   
                    "chat_room_message_id" => $id_chat_m,
                    "chat_room_id" => $id_chat,
                    "sort" => $sort,
                 
                    "date_send" => date('Y-m-d'),
                    "time_send" => date("H:i:s")
                ]);
              

           
				
				
			
            DB::commit();
           
        }
        catch(\Exeception $e){
            DB::rollback();
           
        }

	}
    public function chat_room ($id_chat)
    {
    	$chat_room= "select * from chat_room where chat_room_id = $id_chat ";
    	$chat_room=DB::connection()->select($chat_room);
    	$chat_room = $chat_room[0];
    	
    	
    	return view('backend.chat.room',compact('id_chat','chat_room'));
    }
    public function content_chat($id_chat=-1)
    {
    	
    	$chat_room= "select * from chat_room_message where chat_room_id = $id_chat order by sort, date_send, time_send";
    	$chat_room=DB::connection()->select($chat_room);
    	$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		//$id=$idkar[0]->p_karyawan_id;
    	$id = null;
    	return view('backend.chat.chat_content',compact('id','id_chat','chat_room'));
    }
    public function tambah_chat()
    {
    	return view('backend.chat.tambah');
	}public function edit_chat_room (Request $request, $id_chat=null)
    {
		
		$chat_list= "select * from chat_room left join p_karyawan a on chat_room.p_karyawan_create_id  =a.p_karyawan_id
    	where 1=1  and chat_room_id = $id_chat
    	order by selesai,created_by,tujuan ";
		$chat_list=DB::connection()->select($chat_list);
		


		$sqlappr="SELECT * from get_data_karyawan()  ";
		$appr=DB::connection()->select($sqlappr);
		return view('backend.chat.edit',compact('chat_list','appr','id_chat'));
    	
    }public function klarifikasi_absen(Request $request, $id_chat=null)
    {
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		//$id=$idkar[0]->p_karyawan_id;
		if($request->get('tgl_awal') and $request->get('tgl_akhir')){
    		$tgl_awal = $request->get('tgl_awal');
    		$tgl_akhir = $request->get('tgl_akhir');
    		$sql_tgl_awal ="tanggal >= '$tgl_awal' and ";
    	}else{
    		$bulan = (date('m'));
    		if($bulan==1){
				$bulan = 12;    			
    			$tahun = date('Y')-1;
    		}
    		else{
				$bulan = date('m')-1;    			
    			$tahun = date('Y');
    		}
    		$tgl_awal = $tahun.'-'.$bulan.'-25';
    		$tgl_akhir = date('Y-m-d');
    		$sql_tgl_awal ='';
    	}
		$where = '';
		if ($request->get('tujuan'))
			$where .= 'and tujuan = '.$request->get('tujuan');
		if ($request->get('selesai')==-1)
			$where .= ' and selesai = 0';
		else if ($request->get('selesai'))
			$where .= ' and selesai = '.$request->get('selesai');
		else 
			$where .= ' and selesai in(0,2,4) ';
		if ($request->get('appr_hr_status'))
			$where .= ' and appr_hr_status = '.$request->get('appr_hr_status');
		if ($request->get('nama'))
			$where .= ' and p_karyawan_create_id = '.$request->get('nama');
		//and selesai 
			$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
			left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  $where   and (($sql_tgl_awal tanggal<='$tgl_akhir' ) or tanggal is null)
    	-- and tujuan in(1,2,3,4,5,9)
		order by selesai desc";
		$chat_list=DB::connection()->select($chat_list);

		if ($id_chat and $id_chat!=-1) {

			$chat= "select * from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan_id where chat_room_id = $id_chat order by  created_date desc";
			$c=DB::connection()->select($chat);
			print_r($chat);
			$c= $c[0];
		} else {
			$c=null;
		}
        $karyawan = DB::connection()->select('select * from p_karyawan where active=1');


		return view('backend.chat.klarifikasi_absen',compact('chat_list','id_chat','c','karyawan','tgl_awal','tgl_akhir','request'));
    }public function klarifikasi_gaji(Request $request, $id_chat=null)
    {
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		//$id=$idkar[0]->p_karyawan_id;
		if($request->get('tgl_awal') and $request->get('tgl_akhir')){
    		$tgl_awal = $request->get('tgl_awal');
    		$tgl_akhir = $request->get('tgl_akhir');
    		$sql_tgl_awal ="tanggal >= '$tgl_awal' and ";
    	}else{
    		$bulan = (date('m'));
    		if($bulan==1){
				$bulan = 12;    			
    			$tahun = date('Y')-1;
    		}
    		else{
				$bulan = date('m')-1;    			
    			$tahun = date('Y');
    		}
    		$tgl_awal = $tahun.'-'.$bulan.'-25';
    		$tgl_akhir = date('Y-m-d');
    		$sql_tgl_awal ='';
    	}
		$where = '';
		if ($request->get('tujuan'))
			$where .= 'and tujuan = '.$request->get('tujuan');
		if ($request->get('selesai')==-1)
			$where .= ' and selesai = 0';
		else if ($request->get('selesai'))
			$where .= ' and selesai = '.$request->get('selesai');
		else 
			$where .= ' and selesai in(0,2,4) ';
		if ($request->get('appr_hr_status'))
			$where .= ' and appr_hr_status = '.$request->get('appr_hr_status');
		if ($request->get('nama'))
			$where .= ' and p_karyawan_create_id = '.$request->get('nama');
		

		//and selesai 
			$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
			left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  $where   and (($sql_tgl_awal tanggal<='$tgl_akhir' ) or tanggal is null)
    	 and tujuan in(6,7,8,9)
		order by selesai desc";
		$chat_list=DB::connection()->select($chat_list);

		if ($id_chat and $id_chat!=-1) {

			$chat= "select * from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan_id where chat_room_id = $id_chat order by  created_date desc";
			$c=DB::connection()->select($chat);
			print_r($chat);
			$c= $c[0];
		} else {
			$c=null;
		}
		$karyawan = DB::connection()->select('select * from p_karyawan where active=1');



		return view('backend.chat.klarifikasi_absen',compact('chat_list','id_chat','karyawan','c','tgl_awal','tgl_akhir','request'));
    }
}