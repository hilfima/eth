@extends('layouts.appsA')

@section('content')
<div class="row">
						<div class="col-sm-8 col-5">
							<h4 class="page-title">Menu</h4>
						</div>
						<div class="col-sm-4 col-7 text-right m-b-30">
							<a href="<?=route('be.tambah_menu');?>" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">	
								<table class="table table-striped custom-table datatable mb-0">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Menu</th>
											<th>Icon</th>
											<th>Link</th>
											<th>Parent</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php 
									$no=0;
									foreach($menu as $menu){
											$no++;
									?>
										<tr>
											<td><?=$no?></td>
											<td>
												<h2><?=$menu->nama_menu;?></h2>
											</td>
											<td><?=$menu->icon?'<i class="'.$menu->icon.'"></i>':'';?></td>
											<td><?=$menu->link;?></td>
											<td><?=$menu->nama_parent;?></td>
											<td style="text-align: center">
                                    <a href="{!! route('be.edit_menu',$menu->m_menu_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-edit'></span></a>
                                    <a href="{!! route('be.hapus_menu',$menu->m_menu_id) !!}" title='Ubah' data-toggle='tooltip'><span class='fa fa-trash'></span></a></td>
										</tr>
									<?php }?>	
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					
@endsection