<?php

 
echo view('layouts.header');
$iduser=Auth::user()->id;
        $sqluser="SELECT p_recruitment.foto,role,m_lokasi.nama as nmlokasi,p_karyawan.nama,users.role FROM users
				left join p_karyawan on p_karyawan.user_id=users.id
				left join p_karyawan_pekerjaan on p_karyawan_pekerjaan.p_karyawan_id=p_karyawan.p_karyawan_id
				left join m_lokasi on p_karyawan_pekerjaan.m_lokasi_id=m_lokasi.m_lokasi_id
				left join p_recruitment on p_recruitment.p_recruitment_id=p_karyawan.p_karyawan_id
				where users.id=$iduser";
        $user=DB::connection()->select($sqluser);
        function wordSimilarity($s1,$s2) {
			
		    $words1 = preg_split('/\s+/',$s1);
		    $words2 = preg_split('/\s+/',$s2);
		    $diffs1 = array_diff($words2,$words1);
		    $diffs2 = array_diff($words1,$words2);

		    $diffsLength = strlen(join("",$diffs1).join("",$diffs2));
		    $wordsLength = strlen(join("",$words1).join("",$words2));
		    if(!$wordsLength) return 0;

		    $differenceRate = ( $diffsLength / $wordsLength );
		    $similarityRate = 1 - $differenceRate;
			echo $similarityRate;
		    echo $s1,$s2;
			echo '<br>';
			echo '<br>';
		    return $similarityRate;

		}
        
        $ex = explode('/',$_SERVER['REQUEST_URI']);
        $uri = $ex[0];
        if(isset($ex[1])) $uri .= $ex[1];
        if(isset($ex[2])) $uri .= '/'.$ex[2];
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$uri/";
       // echo $actual_link;
       $menuuser=DB::connection()->select("Select * from users_admin join m_menu on users_admin.menu_id = m_menu.m_menu_id where m_role_id = ".$user[0]->role);
      // echo
       $i =0;
       foreach($menuuser as $menuuser){
	   		if($menuuser->link!= '#'){
	   		 	$value = similar_text($actual_link,route($menuuser->link,1,1),$percent);
	   		 	//echo $percent;
	   		 	
	   		 	//echo '<br>';
				if($i<$percent)
					$i = $percent;
			}
	   }
    //   echo 'high kesamaan adalah '.$i;die;
        //echo route('be.generatejabatanstruktural');
        
?>
			<!-- /Header -->
			<style>
	    th{
	        text-align:center;
	        font-size:11px;
	        
	    }
	    td{
	        
	        font-size:11px;
	    }
	    tr td:first-child {text-align: center;}
	</style>	
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li> 
								<a href="{!!route('admin')!!}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
							</li>
							
							@if($user[0]->role==1 or $user[0]->role==-1 or $user[0]->role==5  or $user[0]->role==3  ) 
							<li class="submenu">
								<a href="#"><i class="la la-building"></i> <span> Menu & Akses </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
							@if($user[0]->role==1 or $user[0]->role==-1 ) 
									<li><a href="{!!route('be.menu')!!}"> Menu </a></li>
									<!--<li><a href="{!!route('be.role')!!}"> Role </a></li>-->
									<li><a href="{!!route('be.admin')!!}"> Role </a></li>
									<li><a href="{!!route('be.akun')!!}"> Akun Khusus </a></li>
							@endif
									<li><a href="{!!route('be.user')!!}"> User </a></li>
								</ul>
							</li>
							@endif
							<?php 
							$sqlmenu="SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = 0 and active=1 and type=1 and 
							(
							(select count(*) from users where role = -1 and id=$iduser)>=1
							 or
							 a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
							)  
							
							order  by urutan"; 
							$menuSide=DB::connection()->select($sqlmenu);
							function menuFunction ($parent,$iduser){
							 $sqlmenu="SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = $parent and active=1 and type=1 and 
							(
							(select count(*) from users where role = -1 and id=$iduser)>=1
							 or
							 a.m_menu_id in (select users_admin.menu_id from users join users_admin on m_role_id = role and users_admin.active=1 where  id=$iduser)
							)  
							order  by urutan, nama_menu"; 
							 $return = '';
							 $menuSide=DB::connection()->select($sqlmenu);
							 if(count($menuSide)){
							 	
							 $return.= '<ul style="display: none;">';
							 foreach($menuSide as $menuSide){
							 	$link = $menuSide->link=='#'?'javascript:void(0)':route($menuSide->link,$menuSide->link_sub);
							 $return.= '
									<li class="nav-item">
		                                <a href="'.$link.'" class="nav-link">
		                                    
		                                  '.$menuSide->nama_menu.' 
		                                </a>
		                                
										'.menuFunction($menuSide->m_menu_id,$iduser).'
                            		</li>
                            	';
								 }
                            	$return.= '</ul>';
							 }
							 return $return;
							};
							
							
							foreach($menuSide as $menuSide){?>
							<li <?=$menuSide->count?'class="submenu"':'';?>>
								<a href="<?=$menuSide->link=='#'?'javascript:void(0)':route($menuSide->link,$menuSide->link_sub);?>"><?=$menuSide->icon?'<i class="'.$menuSide->icon.'"></i>':'';?> <span><?=$menuSide->nama_menu;?></span> <?=$menuSide->count?'<span class="menu-arrow"></span>':'';?></a>
								<?php if($menuSide->count){
									echo menuFunction($menuSide->m_menu_id,$iduser);
									 ?>
									
								
								<?php }?>
                            </li>
                            <?php }?>
                            
							
						</ul>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
			<!-- Page Wrapper -->
            <div class="page-wrapper">
			
				<!-- Page Content -->
                <div class="content container-fluid">
					
					<!-- Page Title -->
					
					@if($user[0]->role==2  )
						<div class="card">
						<div class="card-body">
							Maaf, diakses untuk user admin saja
							
							<br>
							<br>
							<a href="{{route('home')}}">Kembali Ke Panel Karyawan</a>
						</div>
						</div>
					@else
					 @yield('content')
					@endif
					<!-- /Content End -->
					
                </div>
				<!-- /Page Content -->
				
            </div>
			<!-- /Page Wrapper -->
			
        </div>
		<!-- /Main Wrapper -->
		
		<!-- Sidebar Overlay -->
		<div class="sidebar-overlay" data-reff=""></div>
		
		<!-- jQuery -->
		
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
</script><script>
	function queue(){
		
		
		var ajaxTime= new Date().getTime();	
		$.ajax({
				type: 'get',
				
				url: '<?=route('be.queue','admin');?>',
				dataType: 'json',
				success: function(data){
					
					if(data.status){
						
							var totalTime = new Date().getTime()-ajaxTime;
							queue();
					}
					
					//var tgl_cicilan = myDate.addMonths(cicilan);
						
					
					
				    //console.log(data);
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
		
	}
	
	$(document).ready(function(){
  		historis();
  		queue();
  		
	});
	
	function historis(){
		
		$.ajax({
				type: 'get',
				data:{"historis_page":'<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>'},
				url: '<?=route('historis');?>',
				dataType: 'json',
				success: function(data){
				},
				error: function (error) {
				    console.log('error; ' + eval(error));
				    //alert(2);
				}
			});
		
	}
	
</script>
  <?php echo view('layouts.footer');?>      