<?php

namespace App\Http\Controllers\Backend;

use App\ajuan_xls;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Setting;
use DB;
use Maatwebsite\Excel\Excel;
use Mail;
use App\Helper_function;
use Response;

class MenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function menu(Request $request)
    {
    	
		 $iduser=Auth::user()->id;
      	$sqluser="SELECT p_recruitment.foto FROM users
			left join p_karyawan on p_karyawan.user_id=users.id
			left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
			where users.id=$iduser"; $user=DB::connection()->select($sqluser);
			
		$sqlmenu="SELECT a.*,b.nama_menu as nama_parent from m_menu a left join m_menu b on a.parent = b.m_menu_id"; 
		$menu=DB::connection()->select($sqlmenu);
		$content_var[] = 'menu';
		return view('backend.menu.menu',compact('menu'));;
    } public function tambah(){
		$data['nama_menu']='';
		$data['icon']='';
		
		$data['parent']='';
		$data['link']='';
		$data['link_sub']='';
		$data['urutan']='';
		$data['type']='';
		$id='';
		$title='menu';
		$type='simpan_'.str_replace(' ','_',$title);
		$page='Tambah';
		$sqlmenu="SELECT m_menu.*,a.nama_menu as nama_parent from m_menu left join m_menu a on m_menu.parent = a.m_menu_id"; 
		$menu=DB::connection()->select($sqlmenu);
		
		return view('backend.menu.tambah_edit_menu',compact('data','type','id','page','title','title','menu'));
	}public function edit($id){
		$page='Edit';
		$title='menu';
		$type='update_'.str_replace(' ','_',$title);
		$sql = "SELECT * FROM m_menu where m_menu_id=$id ";
    	$cos=DB::connection()->select($sql);
		$data['nama_menu']=$cos[0]->nama_menu;
		$data['icon']=$cos[0]->icon;
		$data['parent']=$cos[0]->parent;
		$data['link']=$cos[0]->link;
		$data['link_sub']=$cos[0]->link_sub;
		$data['urutan']=$cos[0]->urutan;
		$data['type']=$cos[0]->type;
		
		$data['active']=$cos[0]->active;
		$sqlmenu="SELECT m_menu.*,a.nama_menu as nama_parent from m_menu left join m_menu a on m_menu.parent = a.m_menu_id "; 
		$menu=DB::connection()->select($sqlmenu);
		return view('backend.menu.tambah_edit_menu',compact('menu','data','type','id','page','title'));
	}public function simpan(Request $request){
		$title='menu';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_menu")
                ->insert([
                    "nama_menu"=>($request->get("nama_menu")),
                    "icon"=>($request->get("icon")),
                    "parent"=>($request->get("parent")),
                    "link"=>($request->get("link")),
                    "link_sub"=>($request->get("link_sub")),
					"urutan"=>($request->get("urutan")),
					"type"=>($request->get("type")),
                    "active"=>(1),
                   
                    "create_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil di input!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }

    }public function update(Request $request, $id){
		$title='menu';
        DB::beginTransaction();
        try{
            $idUser=Auth::user()->id;
            DB::connection()->table("m_menu")
                ->where("m_menu_id",$id)
                ->update([
                    "nama_menu"=>($request->get("nama_menu")),
                    "icon"=>($request->get("icon")),
                    "parent"=>($request->get("parent")),
                    "link"=>($request->get("link")),
					"link_sub"=>($request->get("link_sub")),
					"urutan"=>($request->get("urutan")),
					"type"=>($request->get("type")), 
                    "active"=>(1),
                    
                    "update_date" => date("Y-m-d H:i:s")
                ]);
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil di ubah!');
     
        } catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
        } 
    public function hapus($id)
    {
		$title='menu';
        DB::beginTransaction();
        try{
            DB::connection()->table("m_menu")
                ->where("m_menu_id",$id)
                ->delete();
            DB::commit();
            return redirect()->route('be.'.str_replace(' ','_',$title))->with('success',ucwords($title).' Berhasil dihapus!');
        }
        catch(\Exeception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e);
        }
    }
} 