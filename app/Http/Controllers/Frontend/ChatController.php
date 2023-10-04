<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\rekaplembur_xls;
use App\User;
use App\Helper_function;
use Illuminate\Http\Request;
use DB;
use Auth;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use Response;

class ChatController extends Controller{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function chat_list()
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$id_karyawan=$idkar[0]->p_karyawan_id;
		
		$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id 
    				left join p_karyawan b on appr = b.p_karyawan_id 
		where p_karyawan_create_id = $id order by created_date";
		$chat_list=DB::connection()->select($chat_list);
    	
		
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		return view('frontend.chat.klarifikasi_list',compact('chat_list','appr'));
	}
	public function karyawan_klarifikasi(Request $request,$id_chat){  
	$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
			left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  
		and chat_room_id = $id_chat
    	order by created_by,tujuan ";
		$chat_list=DB::connection()->select($chat_list);
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		return view('frontend.chat.karyawan_klarifikasi',compact('chat_list','id_chat','appr'));
	}
	public function simpan_chat(Request $request){  
		DB::beginTransaction();
		try{
        	
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
			$topik =$request->get("topik");
			$SQL = "SELECT count(*) as count from chat_room where p_karyawan_create_id=$id and topik ='$topik'";
			$chat=DB::connection()->select($SQL);
			if(!$chat[0]->count){
				
				
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
					"tanggal"=>($request->get("tanggal")),
					"appr"=>($request->get("atasan")),
					"jam_masuk"=>($request->get("jam_masuk")),
					"jam_keluar"=>($request->get("jam_keluar")),
                   
                  
					"chat_room_id" => $id_chat,
					"p_karyawan_create_id" => $id,
					"created_by" => $iduser,
					"created_date" => date("Y-m-d H:i:s")
				]);
			if($request->file('file')){//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='chat-'.date('ymdhis').'-'.$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("chat_room")
					->where("chat_room_id",$id_chat)
					->update([
							"file"=>$path
						]);
			}

           
				
				
			
			DB::commit();
			return redirect()->route('fe.chat_list',$id_chat)->with('success','Topik Berhasil di input!');
			}else{
				return redirect()->route('fe.tambah_chat',['','key='.$topik])->with('error','Topik Klarifikasi sudah ada, silahkan cek di menu chat!');
				
			}
		}
		catch(\Exeception $e){
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}

	} 
	public function send_chat(Request $request,$id_chat){  
		DB::beginTransaction();
		try{
        	
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;
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
					"pengirim"=>($id),
					"sisi"=>'karyawan',
                   
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
	public function chat_room ($id_chat){
		$chat_room= "select * from chat_room where chat_room_id = $id_chat ";
		$chat_room=DB::connection()->select($chat_room);
		$chat_room = $chat_room[0];
    	
    	
		return view('frontend.chat.room',compact('id_chat','chat_room'));
	}
	public function content_chat($id_chat){
		$chat_room= "select * from chat_room_message where chat_room_id = $id_chat order by sort, date_send, time_send";
		$chat_room=DB::connection()->select($chat_room);
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		return view('frontend.chat.content',compact('id','id_chat','chat_room'));
	}
	public function klarifikasi_absen(Request $request, $id_chat=null)
	{
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;
		$where = '';
		if ($request->get('tujuan'))
			$where .= 'and tujuan = '.$request->get('tujuan');
		if ($request->get('selesai'))
			$where .= ' and selesai = '.$request->get('selesai');
		$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
			left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  $where 
    	and appr = $id
    	and appr_hr_status =2
    	order by created_by,tujuan ";
		$chat_list=DB::connection()->select($chat_list);

		



		return view('frontend.chat.klarifikasi_absen',compact('chat_list','id_chat'));
	}
	public function appr_atasan_klarifikasi ($id_chat)
	{
		$chat_list= "select *,p_karyawan.nama as nama, b.nama as nama_atasan from chat_room left join p_karyawan on p_karyawan_create_id = p_karyawan.p_karyawan_id
			left join p_karyawan b on appr = b.p_karyawan_id
    	where 1=1  
		and chat_room_id = $id_chat
    	order by created_by,tujuan ";
		$chat_list=DB::connection()->select($chat_list);
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		return view('frontend.chat.appr_atasan_klarifikasi',compact('chat_list','id_chat','appr'));
	}public function update_klarifikasi(Request $request, $Id)
	{
		DB::beginTransaction();
		try {

			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkar=$idkar[0]->p_karyawan_id;

			// echo print_r($chat_room[0]);echo $id_chat;
			// die;
			DB::connection()->table("chat_room")
			->where('chat_room_id',$Id)
			->update([

				"appr_status"=>($request->get("appr_status")),
				"selesai"=>2,
				"keterangan_atasan"=>($request->get("keterangan_hr")),

				"appr_date" => date("Y-m-d H:i:s")
			]);
			


			$selesai ="Disetujui Atasan dan Menunggu tindak lanjut HC";
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
			return redirect()->route('fe.klarifikasi_absen')->with('success','Topik Berhasil di input!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}public function update_klarifikasi_karyawan(Request $request, $Id)
	{
		DB::beginTransaction();
		try {

			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$idkar=$idkar[0]->p_karyawan_id;

			// echo print_r($chat_room[0]);echo $id_chat;
			// die;
			DB::connection()->table("chat_room")
			->where('chat_room_id',$Id)
			->update([
				"deskripsi_karyawan"=>($request->get("keterangan_hr")),
				"selesai"=>4,
			]);
			if($request->file('file_karyawan')){//echo 'masuk';die;
				$file = $request->file('file_karyawan');
				$destination="dist/img/file/";
				$path='file_karyawan_klarifikasi-'.date('ymdhis').'-'.$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()->table("chat_room")
					->where("chat_room_id",$Id)
					->update([
							"file_karyawan"=>$path
						]);
			}

			$selesai ="Karyawan Selesai Konfirmasi, menunggu tindak lanjut HC";
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
			return redirect()->route('fe.chat_list')->with('success','Topik Berhasil di ubah!');
		} catch (\Exeception $e) {
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}
	}
	public function tambah_chat(){
		$iduser=Auth::user()->id;
		$sqlidkar="select * from p_karyawan
		left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id = p_karyawan.p_karyawan_id
		where user_id=$iduser";
		$idkar=DB::connection()->select($sqlidkar);
		$id=$idkar[0]->p_karyawan_id;

		$id_karyawan = $idkar[0]->p_karyawan_id;
		
		$help=new Helper_function();
		$jabstruk = $help->jabatan_struktural($id_karyawan);
		$atasan = $jabstruk['atasan'];
		$bawahan = $jabstruk['bawahan'];
		$sejajar = $jabstruk['sejajar'];

		$sqlappr="SELECT * from get_data_karyawan() WHERE m_jabatan_id in($atasan) ";
		$appr=DB::connection()->select($sqlappr);
		return view('frontend.chat.tambah',compact('appr'));
	}
}