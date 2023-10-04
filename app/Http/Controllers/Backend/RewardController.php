<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class RewardController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }public function reward()
	{

		$sqlreward="SELECT * from p_karyawan_award
				left join p_karyawan on p_karyawan_award.p_karyawan_id = p_karyawan.p_karyawan_id
				left join m_jenis_reward on m_jenis_reward.m_jenis_reward_id = p_karyawan_award.m_jenis_reward_id
				WHERE 1=1 and p_karyawan_award.active = 1 ";
		$Reward=DB::connection()->select($sqlreward);
		return view('backend.reward.reward',compact('Reward'));
	}

	public function tambah_reward()
	{
		$type = 'simpan_reward';
		$id = '';
		$data['m_jenis_reward_id'] = '';;
		$data['p_karyawan_id'] = '';;
		$data['tgl'] = '';;
		$karyawan = $Reward=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$reward=DB::connection()->select("select * from m_jenis_reward where active=1 ");
		return view('backend.reward.tambah_reward',compact('id','type','data','karyawan','reward'));
	}

	public function simpan_reward(Request $request)
	{
		
		// echo $kode;die;
		DB::connection()->table("p_karyawan_award")
		->insert([
			"m_jenis_reward_id" => $request->get("reward"),
			"hadiah" => $request->get("hadiah"),
			"tgl_award" => $request->get("tgl_award"),
			"p_karyawan_id" => $request->get("p_karyawan_id"),

		]);

		return redirect()->route('be.reward_karyawan')->with('success',' reward Berhasil di input!');
	}

	public function edit_reward($id)
	{

		
		$sqlreward="SELECT * FROM p_karyawan_award WHERE active=1 and p_karyawan_award_id = $id  ";
		$reward=DB::connection()->select($sqlreward);
		$data['m_jenis_reward_id'] =$reward[0]->nama_award;;
		$data['p_karyawan_id'] = $reward[0]->p_karyawan_id;;
		$data['tgl'] = $reward[0]->tgl_award;;
		$karyawan = $Reward=DB::connection()->select("select * from p_karyawan where active=1 order by nama");
		$type = 'update_reward';
		
		return view('backend.reward.tambah_reward',compact('id','type','data','karyawan'));
	}

	public function update_reward(Request $request, $id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("p_karyawan_award")
		->where("p_karyawan_award_id",$id)
		->update([
		"m_jenis_reward_id" => $request->get("reward"),
			"hadiah" => $request->get("hadiah"),
		"tgl_award" => $request->get("tgl_award"),
		"p_karyawan_id" => $request->get("p_karyawan_id"),
		]);

		return redirect()->route('be.reward_karyawan')->with('success',' reward Berhasil di Ubah!');
	}

	public function hapus_reward($id)
	{
		$idUser=Auth::user()->id;
		DB::connection()->table("p_karyawan_award")
		->where("p_karyawan_award_id",$id)
		->update([
			"active"=>0,

		]);

		return redirect()->back()->with('success',' reward Berhasil di Hapus!');
	}
}
