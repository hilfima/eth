<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Helper_function;

class FaskesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function faskes()
    {
         $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;

		$sqlfasilitas="SELECT * FROM m_faskes
                WHERE 1=1 and active=1 and p_karyawan_id = $id ";
        $m_faskes=DB::connection()->select($sqlfasilitas);
        
        $sql="SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
        $keluarga=DB::connection()->select($sql);
 		
        
        $help = new Helper_function();
        $faskes = $help->lap_faskes($id);
        
        $iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,p_karyawan.nama FROM users
left join p_karyawan on p_karyawan.user_id=users.id
left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_recruitment_id
where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
		
        return view('frontend.faskes.fasilitas_kesehatan',compact('keluarga','help','faskes','m_faskes','user'));
    }public function pengajuan_faskes()
    {
    	 $iduser=Auth::user()->id;
        $sqlidkar="select * from p_karyawan where user_id=$iduser";
        $idkar=DB::connection()->select($sqlidkar);
        $id=$idkar[0]->p_karyawan_id;
		$sqlfasilitas="SELECT * FROM p_karyawan_faskes
					
                WHERE 1=1 and p_karyawan_faskes.active=1 and p_karyawan_faskes.p_karyawan_id = $id ";
        $fasilitas=DB::connection()->select($sqlfasilitas);
        
    	 $sql="SELECT * FROM p_karyawan_keluarga WHERE active=1 and p_karyawan_id=$id ORDER BY nama ASC ";
        $keluarga=DB::connection()->select($sql);
 		
        return view('frontend.faskes.pengajuan_faskes',compact('fasilitas','keluarga','idkar'));
    	
    	
    }public function simpan_faskes(Request $request)
    {
    	DB::beginTransaction();
		try{
			$iduser=Auth::user()->id;
			$sqlidkar="select * from p_karyawan 
			join p_karyawan_pekerjaan on p_karyawan.p_karyawan_id = p_karyawan_pekerjaan.p_karyawan_id
			where user_id=$iduser";
			$idkar=DB::connection()->select($sqlidkar);
			$id=$idkar[0]->p_karyawan_id;

			$help = new Helper_function();
			date_default_timezone_set('Asia/Jakarta');
			//$select = "select max(t_faskes_id) as max from t_faskes";
			//$idkar=DB::connection()->select($select);
			//$nocuti = $idkar[0]->max+1;
			DB::connection()->table("t_faskes")
			->insert([
					
					//"t_faskes_id"=>$nocuti,
					"p_karyawan_id"=>$id,
					"tanggal_pengajuan"=>date('Y-m-d',strtotime($request->get('tanggal_kebutuhan'))),
					
					"keterangan"=>$request->get('keterangan'),
					"nominal"=>$help->hapusRupiah($request->get('nominal')),
					"create_date"=>date('Y-m-d H:i:s'),
					"p_karyawan_keluarga_id"=>$request->get('keluarga'),
					"nama_penyakit"=>$request->get('penyakit'),
					"m_lokasi_faskes_id"=>$idkar[0]->m_lokasi_id,
					"create_by"=>$id,
					"appr_status"=>0,
					"jenis"=>2,
				]);
				$sql = "SELECT last_value FROM seq_t_faskes;";
                $nocuti=DB::connection()->select($sql);
		
			if($request->file('file')){//echo 'masuk';die;
				$file = $request->file('file');
				$destination="dist/img/file/";
				$path='faskes-'.date('YmdHis').$file->getClientOriginalName();
				$file->move($destination,$path);
				//echo $path;die;
				DB::connection()
					->table("t_faskes")
					->where("t_faskes_id",$nocuti[0]->last_value)
					->update([
							"foto"=>$path
						]);
			}

			DB::commit();

			
			return redirect()->route('fe.faskes')->with('success','Pengajuan Kesehatan Berhasil di simpan!');
		}
		catch(\Exeception $e){
			DB::rollback();
			return redirect()->back()->with('error',$e);
		}

    	
    }
	public function generate_kurang_faskes_penyesuaian()
    {
    	 $iduser=Auth::user()->id;
    	$sqlfasilitas="SELECT * from p_karyawan
    		join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id and a.active=1
    		join p_karyawan_gapok b on b.p_karyawan_id = p_karyawan.p_karyawan_id and b.active=1
		    WHERE 1=1 and p_karyawan.active=1";
		$faskes=DB::connection()->select($sqlfasilitas);
		foreach($faskes as $karyawan){
			
			
			if($karyawan->periode_gajian==1){
				$gapok = $karyawan->gapok;
				$grade = $karyawan->tunjangan_grade;
				$nominal = $gapok+$grade;
			}else{
				
				$nominal =  $karyawan->upah_harian*22;
			}
			
			
	    	$sqlfasilitas="SELECT * from t_faskes
	    		
			    WHERE 1=1 and t_faskes.active=1 and jenis=1 and p_karyawan_id = ".$karyawan->p_karyawan_id." and create_date >= '2023-01-01'order by create_date desc";
			$faskes=DB::connection()->select($sqlfasilitas);
			
			$sqlfasilitas="SELECT count(*) as count from t_faskes
	    		
			    WHERE 1=1 and t_faskes.active=1 and jenis=3 and p_karyawan_id = ".$karyawan->p_karyawan_id." ";
			$faskes_chek=DB::connection()->select($sqlfasilitas);
			if(count($faskes)){
				
			if($nominal - $faskes[0]->nominal >0 and $faskes_chek[0]->count==0){
				
			echo $karyawan->nama;
			echo '<br>';				
			echo $nominal ;
			echo '<br>';				
			echo $faskes[0]->nominal;
			echo '<br>';				
			echo $nominal - $faskes[0]->nominal;
			echo '<br>';	
			 		DB::connection()->table("t_faskes")
	               		->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal - $faskes[0]->nominal),
	                  "keterangan"=>'Penyesuaian Gaji Tahun '.(date('Y')),
	                  "tanggal_pengajuan"=>(date('Y-m-d')),
	                  "jenis"=>(3),
	                  "appr_status"=>(1),
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
						
			}
			}
			
		}
    }
    public function generate_update_faskes($id_karyawan=null)
    {
    	 $iduser=Auth::user()->id;
    	 $help= new Helper_function();
    	$date = date('Y-m-d');
    	$sql = "(select max(t_faskes_id) as max_fakses from t_faskes)";
		$faskes=DB::connection()->select($sql);
		
		$sqlfasilitas="SELECT *
    					
    					FROM m_faskes
    					Join p_karyawan on m_faskes.p_karyawan_id  = p_karyawan.p_karyawan_id and p_karyawan.active=1
    					join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
						join p_karyawan_gapok b on b.p_karyawan_id = p_karyawan.p_karyawan_id and b.active=1
                
		                WHERE 1=1 and m_faskes.active=1 and generate_add_date<='$date'";
		$faskes=DB::connection()->select($sqlfasilitas);
		foreach($faskes as $karyawan){
			
			$date = $karyawan->tanggal;
			if($karyawan->periode_gajian==1){
				$gapok = $karyawan->gapok;
				$grade = $karyawan->tunjangan_grade;
				$nominal = $gapok+$grade;
			}else{
				
				$nominal =  $karyawan->upah_harian*22;
			}
			$generate_check = $help->tambah_bulan($date,12);
			DB::connection()->table("m_faskes")
				->where('m_faskes_id',$karyawan->m_faskes_id)
	            ->update([
	                  "tanggal"=>(date('Y-m-d')),
	                  "generate_add_date"=>($generate_check),
	                ]);
	                
	        DB::connection()->table("t_faskes")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>'Plafon Reset',
	                  "tanggal_pengajuan"=>(date('Y-m-d')),
	                  "jenis"=>(1),
	                  "appr_status"=>(1),
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
	                
	           
                $sqlfasilitas="SELECT * FROM p_karyawan_faskes
		                WHERE 1=1 and p_karyawan_id =".$karyawan->p_karyawan_id;
		        $count=DB::connection()->select($sqlfasilitas);
		        
                if(count($count)){
					
                	$saldo = $count[0]->saldo +$nominal;
                	DB::connection()->table("p_karyawan_faskes")
						->where('p_karyawan_faskes_id',$count[0]->p_karyawan_faskes_id)
		               	->update([
		                  "saldo"=>($saldo),
		                  
	                ]);
				}
                else{
                	$saldo = $nominal;
					  DB::connection()->table("p_karyawan_faskes")
		               	->insert([
		                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
		                  "saldo"=>($saldo)
	                ]);					
				}
		}
		
		
		
    }public function update_generate_saldo()
    { 
    	$sqlfasilitas="SELECT * FROM t_faskes
			WHERE 1=1 and jenis = 2 and appr_status=1 order by p_karyawan_id";
        $karyawan=DB::connection()->select($sqlfasilitas);
        foreach($karyawan as $karyawan){
        	$sqlfasilitas="	SELECT * FROM p_karyawan_faskes WHERE p_karyawan_id = ".$karyawan->p_karyawan_id;
       		 $saldo=DB::connection()->select($sqlfasilitas);
        	$get_saldo = ($saldo[0]->saldo)-$karyawan->nominal;
        	DB::connection()->table("p_karyawan_faskes")
        			->where("p_karyawan_faskes_id",$saldo[0]->p_karyawan_faskes_id)->update(['saldo'=>$get_saldo]);
        	echo '<br>';
        	echo '<br>';
        	echo '<br>ID: '.$karyawan->p_karyawan_id;
        	echo '-->Saldo now: '.($saldo[0]->saldo);
        	echo '-->Saldo update: '.$get_saldo;
        }
    }public function generate_faskes($id_karyawan=null)
    {
			$where ='';
    	if(!$id_karyawan){
	    	DB::connection()->table("m_faskes")->update(['active'=>0]);
	    	DB::connection()->table("t_faskes")->update(['active'=>0]);
	    	DB::connection()->table("p_karyawan_faskes")->update(['active'=>0]);
		}else{
			$where =' and p_karyawan.p_karyawan_id='.$id_karyawan;
		}
    	$iduser=Auth::user()->id;
    	$help = new Helper_function();
		$sqlfasilitas="SELECT * FROM p_karyawan 
				join p_karyawan_pekerjaan a on a.p_karyawan_id = p_karyawan.p_karyawan_id
				join p_karyawan_gapok b on b.p_karyawan_id = p_karyawan.p_karyawan_id and b.active=1
                WHERE 1=1 and a.active=1 $where";
        $karyawan=DB::connection()->select($sqlfasilitas);
        foreach($karyawan as $karyawan){
        	$date = $karyawan->tgl_bergabung;
			$x = 1;
			
			if($karyawan->periode_gajian==1){
				$gapok = $karyawan->gapok;
				$grade = $karyawan->tunjangan_grade;
				$nominala = $gapok+$grade;
			}else{
				
				$nominala =  $karyawan->upah_harian*22;
			}
			
			while($date < date('Y-m-d')) {
				if($x==1){
					$generate_check = $help->tambah_bulan($date,3);
					$nominal=0;
				}else{
					$nominal=$nominala;
					$generate_check = $help->tambah_bulan($date,12);
				$id_karyawan = $karyawan->p_karyawan_id;
				$sqlfasilitas="SELECT * FROM m_faskes
		                WHERE 1=1 and active=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
		       
		        	
		        if(count($count)){
					DB::connection()->table("m_faskes")
					->where('m_faskes_id',$count[0]->m_faskes_id)
	               	->update([
	                  "tanggal"=>($date),
	                  "generate_add_date"=>($generate_check),
	                ]);
				}else{
					
				  DB::connection()->table("m_faskes")
	               	->insert([
	                 
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "tanggal"=>($date),
	                  "generate_add_date"=>($generate_check),
	                ]);
				}
                DB::connection()->table("t_faskes")
	               	->insert([
	                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
	                  "nominal"=>($nominal),
	                  "keterangan"=>$x==1?'Karyawan Bergabung ke Perusahaan':'Reset Plafon',
	                  "tanggal_pengajuan"=>($date),
	                  "jenis"=>(1),
	                  "appr_status"=>(1),
	                  "create_date"=>date('Y-m-d'),
	                  "create_by"=>$iduser,
	                ]);
                $sqlfasilitas="SELECT * FROM p_karyawan_faskes
		                WHERE 1=1 and p_karyawan_id = $id_karyawan ";
		        $count=DB::connection()->select($sqlfasilitas);
                if(count($count)){
					
                	$saldo = $nominal;
                	DB::connection()->table("p_karyawan_faskes")
						->where('p_karyawan_faskes_id',$count[0]->p_karyawan_faskes_id)
		               	->update([
		                  "saldo"=>($saldo),
		                  
	                ]);
				}
                else{
                	$saldo = $nominal;
					  DB::connection()->table("p_karyawan_faskes")
		               	->insert([
		                  "p_karyawan_id"=>($karyawan->p_karyawan_id),
		                  "saldo"=>($saldo)
	                ]);					
				}
				}
                $x++;
                $date = $generate_check;
			
			} 
			 
		}
	}
}
