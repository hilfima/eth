<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin()
    {

        $sqladmin = "SELECT m_role.nama_role,m_role_id from m_role
       
                WHERE 1=1 and active = 1 ";
        $Admin = DB::connection()->select($sqladmin);

        $sql = "SELECT * from m_lokasi
       
                WHERE 1=1  and active = 1 ";
        $lokasi = DB::connection()->select($sqladmin);
        return view('backend.admin.admin', compact('Admin', 'lokasi'));
    }


    public function lihat($id)
    {

        $sqlrole = "SELECT m_role.nama_role,m_role_id,user_entitas_access from m_role
       
                WHERE 1=1  and m_role_id = $id";
        $role = DB::connection()->select($sqlrole);
        $sqladmin = "SELECT * from users_admin
                WHERE 1=1  and m_role_id = $id";
        $Admin = DB::connection()->select($sqladmin);
        $sql = "SELECT * from m_lokasi
       
                WHERE 1=1  and active = 1 ";
        $lokasi = DB::connection()->select($sql);
        $selectMenu = array();
        $selectMenu['checked'] = array();
        $selectMenu['checked_add'] = array();
        $selectMenu['checked_edit'] = array();
        $selectMenu['checked_hapus'] = array();
        $selectMenu['checked_view'] = array();
        foreach ($Admin as $menu) {
            $selectMenu['checked'][] = $menu->menu_id;
            if($menu->_add)
            $selectMenu['checked_add'][] = $menu->menu_id;
            if($menu->_edit)
            $selectMenu['checked_edit'][] = $menu->menu_id;
            if($menu->_delete)
            $selectMenu['checked_hapus'][] = $menu->menu_id;
            if($menu->_view)
            $selectMenu['checked_view'][] = $menu->menu_id;
        }
        $selectMenu['disable'] = 'disabled';

        return view('backend.admin.edit', compact('selectMenu', 'role', 'id', 'lokasi'));
    }
    public function edit_admin($id)
    {
        $sql = "SELECT * from m_lokasi
       
                WHERE 1=1  and active = 1 ";
        $lokasi = DB::connection()->select($sql);
        $sqlrole = "SELECT m_role.*,m_role_id,user_entitas_access from m_role
       
                WHERE 1=1  and m_role_id = $id";
        $role = DB::connection()->select($sqlrole);
        $sqladmin = "SELECT * from users_admin
       
                WHERE 1=1  and m_role_id = $id";
        $Admin = DB::connection()->select($sqladmin);
        $selectMenu = array();
        $selectMenu['checked'] = array();
        $selectMenu['checked_add'] = array();
        $selectMenu['checked_edit'] = array();
        $selectMenu['checked_hapus'] = array();
        $selectMenu['checked_view'] = array();
        foreach ($Admin as $menu) {
            $selectMenu['checked'][] = $menu->menu_id;
            if($menu->_add)
            $selectMenu['checked_add'][] = $menu->menu_id;
            if($menu->_edit)
            $selectMenu['checked_edit'][] = $menu->menu_id;
            if($menu->_delete)
            $selectMenu['checked_hapus'][] = $menu->menu_id;
            if($menu->_view)
            $selectMenu['checked_view'][] = $menu->menu_id;
        }
        $selectMenu['disable'] = '';
        return view('backend.admin.edit', compact('selectMenu', 'role', 'id', 'lokasi'));
    }

    public function tambah_admin()
    {
        $sql = "SELECT * from m_lokasi
       
                WHERE 1=1  and active = 1 ";
        $lokasi = DB::connection()->select($sql);
        $sqluser = "SELECT * from m_role
                WHERE 1=1 and  active = 1 ";
        $users = DB::connection()->select($sqluser);
        return view('backend.admin.tambah_admin', compact('users', 'lokasi'));
    }

    public function hapus_admin($id)
    {
        DB::connection()->table("m_role")
            ->where("m_role_id", $id)
            ->update([
                "active" => 0,


            ]);
        DB::connection()->table("users_admin")
            ->where("m_role_id", $id)
            ->update([
                "active" => 0,
            ]);
        return redirect()->route('be.admin')->with('success', ' admin Berhasil di Hapus!');
    }

    public function simpan_admin(Request $request)
    {

        // echo $kode;die;
        $sqluser = "SELECT count(*) as count from m_role";
        $users = DB::connection()->select($sqluser);
        $id = $users[0]->count + 1;
        DB::connection()->table("m_role")
            ->insert([
                "m_role_id" => $id,
                "nama_role" => $request->get("user"),
                "user_entitas_access" => $request->get("entitas"),
                "periode_gaji_role" => $request->get("periode_gaji")

            ]);

        $menu = $request->get("menu");
        $add = $request->get("add");
        $edit = $request->get("edit");
        $hapus = $request->get("hapus");
        $view = $request->get("view");
        DB::connection()->table("users_admin")
            ->where("m_role_id", $id)
            ->delete();
        if ($menu or $add or $edit or $hapus or $view ) {

            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->insert([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ]);
            }
            $menu = $request->get("parent");
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->insert([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ]);
            }

            $menu = $request->get("parent_add");
            if(isset($menu)){
                for ($i = 0; $i < count($menu); $i++) {
    
                    $idUser = Auth::user()->id;
                    DB::connection()->table("users_admin")
                        ->where([
                            "m_role_id" => $id,
                            "menu_id" => $menu[$i],
    
                        ])->update(["_add" => 1]);
                }
            }
            
            $menu = $request->get("parent_edit");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_edit" => 1]);
            }
            }
            $menu = $request->get("parent_hapus");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {
                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],
                    ])->update(["_delete" => 1]);
            }
            }

            $menu = $request->get("parent_view");
            //print_r($menu);
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_view" => 1]);
            }
            }
            $menu = $request->get("add");
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_add" => 1]);
            }
            $menu = $request->get("edit");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_edit" => 1]);
            }
            }
            $menu = $request->get("hapus");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {
                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],
                    ])->update(["_delete" => 1]);
            }
            }

            $menu = $request->get("view");
            if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_view" => 1]);
            }
            }
        }

        return redirect()->route('be.admin')->with('success',' admin Berhasil di input!');
    }
    public function update_admin(Request $request, $id)
    {

        // echo $kode;die;
        DB::beginTransaction();
        try {
            DB::connection()->table("m_role")
                ->where("m_role_id", $id)
                ->update([
                    "nama_role" => $request->get("user"),
                    "user_entitas_access" => $request->get("entitas"),
                    "periode_gaji_role" => $request->get("periode_gaji")
                ]);

            $menu = $request->get("menu");
            DB::connection()->table("users_admin")
                ->where("m_role_id", $id)
                ->delete();
                for ($i = 0; $i < count($menu); $i++) {

                    $idUser = Auth::user()->id;
                    DB::connection()->table("users_admin")
                        ->insert([
                            "m_role_id" => $id,
                            "menu_id" => $menu[$i],
    
                        ]);
                }
                $menu = $request->get("parent");
                for ($i = 0; $i < count($menu); $i++) {
    
                    $idUser = Auth::user()->id;
                    DB::connection()->table("users_admin")
                        ->insert([
                            "m_role_id" => $id,
                            "menu_id" => $menu[$i],
    
                        ]);
                }
    
            $menu = $request->get("parent_add");
            if(isset($menu)){
                for ($i = 0; $i < count($menu); $i++) {
    
                    $idUser = Auth::user()->id;
                    DB::connection()->table("users_admin")
                        ->where([
                            "m_role_id" => $id,
                            "menu_id" => $menu[$i],
    
                        ])->update(["_add" => 1]);
                }
            }
            
            $menu = $request->get("parent_edit");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_edit" => 1]);
            }
            }
            $menu = $request->get("parent_hapus");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {
                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],
                    ])->update(["_delete" => 1]);
            }
            }

            $menu = $request->get("parent_view");
            //print_r($menu);
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_view" => 1]);
            }
            }
            $menu = $request->get("add");
            if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_add" => 1]);
            }
            }
            $menu = $request->get("edit");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_edit" => 1]);
            }
            }
            $menu = $request->get("hapus");
             if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {
                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],
                    ])->update(["_delete" => 1]);
            }
            }

            $menu = $request->get("view");
            if(isset($menu)){
            for ($i = 0; $i < count($menu); $i++) {

                $idUser = Auth::user()->id;
                DB::connection()->table("users_admin")
                    ->where([
                        "m_role_id" => $id,
                        "menu_id" => $menu[$i],

                    ])->update(["_view" => 1]);
            }
            }
            DB::commit();
        } catch (\Exeception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('be.admin')->with('success',' admin Berhasil di input!');
    }
}
