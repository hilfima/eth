	
								<div class="row">
								<div class="col-md-12">
							<div class="card card-table">
								<div class="card-header">
									<h3 class="card-title mb-0">Absensi Karyawan</h3>
									
								</div>
								</div>
								</div>
								
										
											 @if(!empty($list_karyawan))
							@foreach($list_karyawan as $list_karyawan)
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
							<div class="dash-widget clearfix card-box" href="{!! route('be.kontrak') !!}">
								<span class=""> @if($list_karyawan->foto!=null)
                                                            <img src="{!! asset('dist/img/profile/'.$list_karyawan->foto) !!}"  class="dash-widget-icon" style="height: 75px;">
                                                        @else
                                                            <img src="{!! asset('dist/img/profile/user.png') !!}" class="dash-widget-icon"  style="height: 75px;">
                                                        @endif</span>
								<div class="dash-widget-info">
								<h5>{!! $list_karyawan->nama !!}</h5>
									<span><?php
                                    	$date = date('Y-m-d');
                                    	$main = 'STR1
                                    			STRLINK 
                                    			<br><div class="badge " style="font-size: small;color:white;STRBG"> STRKETERANGAN </div> STRENDLINK</td>	
                                    	';
									$warna = '';
									$ket = '';
									$content = '';
									if(isset($rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['masuk'])){
										if($rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['masuk'] >= $rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['jam_masuk'] and  $list_karyawan->m_pangkat_id!=5){
											$ket = 'Terlambat';
											$warna = 'orange';
										}else{
											$warna = 'green';
											$ket = 'Masuk';
										}
										$content .= ' '.$rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['masuk'];
										if(isset($rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['keluar'])){
											
											$content.=' 
											s/d  '.$rekap['absen']['a'][$date][$list_karyawan->p_karyawan_id]['keluar'].'
											';	
										}
									}
									if(isset($rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['nama_ijin'])){
										if(!in_array($help->nama_hari($date),array('Minggu','Sabtu'))  ){
											if($rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['tipe']==3)
											$warna = 'green';
											else if($rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['tipe']==4)
											$warna = 'blue';
											else if($rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['tipe']==1)
											$warna = 'purple';
														
											$ket .= ' '.$rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['nama_ijin_only'].'<br> 
											
										
											';	
											if($rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['nama_ijin']
												== 'IZIN PERJALANAN DINAS'){
												$ket .= '<br> dari jam '.$rekap['pengajuan']['ci'][$date][$list_karyawan->p_karyawan_id]['jam_awal'];
											}
										}
													
									}
									if(!$warna){
										$warna = 'red';
										$ket = 'No Information';
										$link = '<a href="'. route('be.input_absen',$list_karyawan->no_absen) .'" target="_blank" title="Input Absen">';
										$endlink = '</a>';
									}
									else{
										$link = '';
										$endlink = '';
									}	
									
											
									$main = str_ireplace('STR1',$content,$main);
									$main = str_ireplace('STRKETERANGAN',$ket,$main);
									$main = str_ireplace('STRBG','background:'.$warna,$main);
									$main = str_ireplace('STRLINK',$link,$main);
									$main = str_ireplace('STRENDLINK',$endlink,$main);
									
											
											
									echo $main;
                                    	?></span>
								</div>
							</div>
						</div>
						
						
							
							
                                @endforeach
                            @endif
                            </div>