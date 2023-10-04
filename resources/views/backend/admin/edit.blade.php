@extends('layouts.appsA')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @include('flash-message')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">	Role</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{!! route('admin') !!}">Admin</a></li>
                            <li class="breadcrumb-item active">Role</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header --> 

        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <!--<h3 class="card-title">DataTable with default features</h3>-->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{!! route('be.update_admin',$id) !!}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <div class="form-group">
                                <label>Nama Role</label>
                               <input name="user" class="form-control " placeholder="Nama Role" value="<?=$role[0]->nama_role;?>" <?=$selectMenu['disable']?>>
                               		
                               	 </div>
                               	 	<div class="form-group">
	                                <label>Entitas</label>
	                               <select name="entitas" class="form-control select2" placeholder="Nama Role" <?=$selectMenu['disable']?>>
	                               	<option value="">- Pilih Entitas -</option>
	                               	<?php foreach($lokasi as $entitas){
											$selected1 = '';
	                               		if($role[0]->user_entitas_access==$entitas->m_lokasi_id){
											$selected1 = 'selected';
										}
	                               		?>
	                               		
	                               		<option value="<?=$entitas->m_lokasi_id;?>" <?=$selected1;?>><?=$entitas->nama;?></option>
	                               	<?php }?>
	                               </select>		
                               	 </div>
						<div class="form-group">
							<label>Periode Gajian</label>
							<select name="periode_gaji" class="form-control " placeholder="Nama Role">
								<option value="">- Pilih Periode Gajian -</option>
								<option value="-1" <?=$role[0]->periode_gaji_role==-1?'selected':''?>>Pekanan</option>
								<option value="1" <?=$role[0]->periode_gaji_role==-1?'selected':''?>>Bulanan</option>
								<option value="2" <?=$role[0]->periode_gaji_role==-1?'selected':''?>>Bulanan & Pekanan</option> 
							</select> 
						</div>
                               	 <hr>
                            <div class="row">
								<div class="col-md-12 row">
								<div class="col-6"> <h3>Select All</h3> </div>
								<div class="col-6"><input type="checkbox" class="form-control" value="1" data-id="" style="height: 20px" onclick="checkall(this)" <?=$selectMenu['disable']?>></div>
								<div class="col-12"><hr><hr></div>
                          <?php 
							$sqlmenu="SELECT *,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = 0 "; 
							$menuSide=DB::connection()->select($sqlmenu);
							echo menuListFunction($selectMenu,0,2);
							function menuListFunction($selectMenu,$parent,$h,$p='',$pp=''){
							 $sqlmenu="SELECT *,a.parent as p,(select count(*) from m_menu b where b.parent = a.m_menu_id ) as count from m_menu a where a.parent = $parent"; 
							 $h=$h+1;
							 $return = '';
							 $menuSide=DB::connection()->select($sqlmenu);
							 if(count($menuSide)){
							 	
							 $return.= '';
							 foreach($menuSide as $menuSide){
							 	$selected = '';
							 	if(in_array($menuSide->m_menu_id,$selectMenu['checked']))
							 		$selected = 'checked';
								$selectedadd = '';
							 	if(in_array($menuSide->m_menu_id,$selectMenu['checked_add']))
							 		$selectedadd = 'checked';
								$selectededit= '';
								if(in_array($menuSide->m_menu_id,$selectMenu['checked_edit']))
									$selectededit = 'checked';
								$selectedview = '';
							 	if(in_array($menuSide->m_menu_id,$selectMenu['checked_view']))
							 		$selectedview = 'checked';
							 	$selectedhapus = '';
							 	if(in_array($menuSide->m_menu_id,$selectMenu['checked_hapus']))
							 		$selectedhapus = 'checked';
							 	$select = '';
							 	$hh = '<h'.$h.'>';
							 	$hhh = '</h'.$h.'>';
							 	$ex = explode('-',$p);
							 	if($menuSide->p and !in_array($menuSide->p,$ex))
							 	$p .= $menuSide->p.'-';
							 	$pp .= 'checkboxchild-'.$menuSide->p.' ';
							 	
							 	if(!menuListFunction($selectMenu,$menuSide->m_menu_id,$h)){
									$select ='<input type="checkbox" class="form-control content-'.$p.'select '.$pp.' childchekbox" name="menu[]" value="'.$menuSide->m_menu_id.'" data-id="'.$p.'" style="height: 20px" onclick="checkparent(this)" '.$selected.' '.$selectMenu['disable'].'>';
									$selectadd = '<input type="checkbox" class="form-control content-' . $p . 'select ' . $pp . 'add  childchekboxadd" name="add[]" value="' . $menuSide->m_menu_id . '" data-id="' . $p . '"  '.$selectedadd.' '.$selectMenu['disable'].'style="height: 20px" onclick="checkparent(this)">';
												$selectedit = '<input type="checkbox" class="form-control content-' . $p . 'select ' . $pp . 'edit  childchekboxedit" name="edit[]" value="' . $menuSide->m_menu_id . '" data-id="' . $p . '" '.$selectededit.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkparent(this)">';
												$selecthapus = '<input type="checkbox" class="form-control content-' . $p . 'select ' . $pp . 'hapus  childchekboxhapus" name="hapus[]" value="' . $menuSide->m_menu_id . '" data-id="' . $p . '" '.$selectedhapus.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkparent(this)">';
												$selectview = '<input type="checkbox" class="form-control content-' . $p . 'select ' . $pp . 'view  childchekboxview" name="view[]" value="' . $menuSide->m_menu_id . '" data-id="' . $p . '" '.$selectedview.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkparent(this)">';
												
												$hh = '';
							 		$hhh = '';
							 	}else{
									$select ='<input type="checkbox" class="form-control '.$pp.'" name="parent[]" id="checkbox-'.$menuSide->m_menu_id.'" value="'.$menuSide->m_menu_id.'" data-id="" style="height: 20px" onclick="checkchild(this)" '.$selected.' '.$selectMenu['disable'].'>';
									$selectadd = '<input type="checkbox" class="form-control ' . $pp . 'add" name="parent_add[]" id="checkbox-' . $menuSide->m_menu_id . '" value="' . $menuSide->m_menu_id . '" data-id="" style="height: 20px" '.$selectedadd.' '.$selectMenu['disable'].' onclick="checkchild(this,'."'add'".')" >';
												$selectedit = '<input type="checkbox" class="form-control ' . $pp . 'edit" name="parent_edit[]" id="checkbox-' . $menuSide->m_menu_id . '" value="' . $menuSide->m_menu_id . '" data-id="" '.$selectededit.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkchild(this,'."'edit'".')" >';
												$selecthapus = '<input type="checkbox" class="form-control ' . $pp . 'hapus" name="parent_hapus[]" id="checkbox-' . $menuSide->m_menu_id . '" value="' . $menuSide->m_menu_id . '" data-id="" '.$selectedhapus.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkchild(this,'."'hapus'".')" >';
												$selectview = '<input type="checkbox" class="form-control ' . $pp . 'view" name="parent_view[]" id="checkbox-' . $menuSide->m_menu_id . '" value="' . $menuSide->m_menu_id . '" data-id="" '.$selectedview.' '.$selectMenu['disable'].' style="height: 20px" onclick="checkchild(this,'."'view'".')" >';
											
								}
							 	if($h==3){
									
							 	if($menuSide->type == 1){
									$type = 'Back End ';
								}else{
									$type = 'Front End ';
									
								}
								}else	
									$type='';
							 $return.= '
								<div class="col-6"> '.$hh.$type.$menuSide->nama_menu.$hhh.' </div>
								<div class="col-2">' . $select . '</div>
								<div class="col-1">' . $selectview . 'View</div>
								<div class="col-1">' . $selectadd . ' Add</div>
								<div class="col-1">' . $selectedit . ' Edit</div>
								<div class="col-1">' . $selecthapus . ' Hapus</div>
								<div class="col-12"><hr></div>
									
                            	'.menuListFunction($selectMenu,$menuSide->m_menu_id,$h,$p,$pp);
								 }
                            	$return.= '';
							 }
							 return $return;
							};
							
							
							?>  
							
									
								
								</div>
							</div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{!! route('be.admin') !!}" class="btn btn-default pull-left"><span class="fa fa-times"></span> Kembali</a>
                        <button type="submit" class="btn btn-info pull-right"><span class="fa fa-check"></span> Simpan</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <script>
    	function checkall(e){
			var val = $(e).val();
    			//alert(val)
    			var checked = false;
					if ($(e).is(':checked')) {
		    			checked = true;			
					}
					
				if(checked)
					$('input:checkbox').prop('checked',true);
				else 
					$('input:checkbox').prop('checked',false);
		
		}
    	function checkchild(e){
    		
    	 		var val = $(e).val();
    			//alert(val)
    			var checked = false;
					if ($(e).is(':checked')) {
		    			checked = true;			
					}
					
				if(checked)
					$('.checkboxchild-'+val).prop('checked',true);
				else 
					$('.checkboxchild-'+val).prop('checked',false);
					
		}
			
    	function checkparent(e){
    		var varclass = ($(e).attr("data-id"))
    		var checkedVals = $('.content-'+varclass+'select:checkbox:checked').map(function() {
			    return this.value;
			}).get();
		 	var allvalue =(checkedVals.join(","));
    			myarr = varclass.split("-");
			var checked = false;
			if (allvalue) {
    			checked = true;			
			}
			for(i=0;i<myarr.length;i++){
				//alert(myarr[i]);
				if(checked)
					$('#checkbox-'+myarr[i]).prop('checked',true);
				else 
					$('#checkbox-'+myarr[i]).prop('checked',false);
			}
		}
    </script>
    <!-- /.content-wrapper  -->
@endsection
