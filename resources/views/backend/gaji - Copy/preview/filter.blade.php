<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.transaksi',[$data['page'],$data['subpage']]) !!}">
     @if(!empty($request->get('menu')) or @$data['page']=='non_ajuan' or @$data['page']=='ajuan' or @$data['page']=='direksi')
   
     <div class="card">
        @if(@$data['page']=='non_ajuan' and $request->get('preview')!='preview' or @$data['page']=='direksi')
        <div class="card-header">
            <!--<h3 class="card-title">DataTable with default features</h3>-->
            @if(@$request->get('preview')!='Gaji' )
            @if(@$data['page']!='direksi')
            <a href="{!! route('be.tambah_generate_'.$data['subpage']) !!}" target="_blank" class="btn btn-sm btn-primary" title='Tambah Generate Gaji' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Generate <?=ucwords($data['subpage'])?> </a>
              @endif
            @if(@$data['page']=='direksi' or @$data['page']=='non_ajuan' )
            <a href="{!! route('be.transaksi', [$data['page'],$data['subpage'], 'preview=Gaji']) !!}" target="_blank" class="btn btn-sm btn-primary" title='Preview' data-toggle='tooltip'><span class='fa fa-plus'></span><?php if($data['page']=='direksi') echo 'Approval HC'; else echo 'Ajuan '.ucwords($data['subpage']);?> </a>
              @endif
              @endif
            <!--<a href="{!! route('be.generategaji') !!}" target="_blank" class="btn btn-sm btn-success" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Edit Data </a>-->
        </div>
        @elseif($request->get('preview')=='preview')
        <div class="card-header"
        	<div class="row">
        	<div class="col-4">
        				 <input type="hidden" name="prl_generate" value="{!!$request->get('prl_generate')!!}"/>
        				 <input type="hidden" name="entitas" value="{!!$request->get('entitas')!!}"/>
        				 <input type="hidden" name="menu" value="Gaji"/>
                    <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-download"></span> Excel</button>
        	</div>
        	</div></div>
        @endif
       
        @if(@$request->get('preview')=='Gaji' or @$data['page']=='ajuan')
        <div class="card-body">
            {{ csrf_field() }}
            <div class="row">
            <input type="hidden" name="preview" value="Gaji"/>
                <div class="col-lg-6">
                	@if( @$data['page']!='thr'  )
                    <div class="form-group">
                        <label>Periode Generate</label>
                        <select class="form-control select2" name="prl_generate" style="width: 100%;" required>
                            <option value="">Pilih Periode Generate</option>
                            <?php
                            foreach ($periode as $periode) {
                               $selected = '';
                                if ($periode->prl_generate_id == $id_prl) {
                                	$selected = 'selected';
                                   
                                } 
                                if($data['subpage']=='gaji')
                                    echo '<option '.$selected.' value="' . $periode->prl_generate_id . '">' . $periode->tipe . ' - Periode: ' . $periode->tahun_gener . ' Bulan: ' . $periode->bulan_gener . ' | Absen:' . $periode->tgl_awal . ' - ' . $periode->tgl_akhir . ' | Lembur:' . $periode->tgl_awal_lembur . ' - ' . $periode->tgl_akhir_lembur . '</option>';
                                else if($data['subpage']=='thr')
                                	 echo '<option '.$selected.' value="' . $periode->prl_generate_id . '">Periode: ' . $periode->tahun_gener . '</option>';
                                
                            }
                            ?>
                        </select>
                    </div>
                    
                    @endif
                </div>
                
                @if(@$data['page']!='ajuan' and @$data['subpage']!='thr'  )
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Menu</label>
                        <select class="form-control select2" name="menu" style="width: 100%;" required>
                            <option value="">Pilih Menu</option>
                             
                           <!-- <option value="Gaji" <?= $request->get('menu') == 'Gaji' ? 'selected' : ''; ?>>Gaji(Master,Periode,Berjangka)</option>
                            -->
                            <option value="Gaji2" <?= $request->get('menu') == 'Gaji2' ? 'selected' : ''; ?>>Gaji(Pendapatan,Tunjangan,Potongan)</option>
                            <option value="previewpajak" <?= $request->get('menu') == 'previewpajak' ? 'selected' : ''; ?>>Preview Pajak</option>
                            <option value="Presensi" <?= $request->get('menu') == 'Presensi' ? 'selected' : ''; ?>>Presensi</option>

                        </select>
                    </div>
                </div>
                @else
                <div class="col-lg-6">
                </div>
                @endif

                <div class="col-md-4">

                    <div class="form-group">
                        <label>Filter Entitas</label>
                        <select class="form-control select2" name="entitas" id="entitas" style="width: 100%;">
                            <option value="">Pilih Entitas</option>
                            <?php
                            foreach ($entitas as $entitas) {
                                $selected = '';
                                if ($entitas->m_lokasi_id == $request->get('entitas'))
                                    $selected = 'Selected';

                                echo '<option  value="' . $entitas->m_lokasi_id . '" ' . $selected . '>' . $entitas->nama . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
               
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pajak</label>
                        <select class="form-control select2" name="pajakonoff" id="pajakonoff" style="width: 100%;">
                            <option value="">Pilih Pajak</option>
                            <option value="ON" <?= $request->get('pajakonoff') == 'ON' ? 'selected' : ''; ?>>ON</option>
                            <option value="OFF" <?= $request->get('pajakonoff') == 'OFF' ? 'selected' : ''; ?>>OFF</option>

                        </select>
                    </div>
                </div>
               
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Bank</label>
                        <select type="text" class="form-control select2" id="bank" style="width: 100%;" name="bank" placeholder="Nama bank">
                            <option value="">- Pilih Bank - </option>
                            <?php

                            $sql = "SELECT * FROM m_bank WHERE active=1 ORDER BY nama_bank ASC ";
                            $bank = DB::connection()->select($sql);

                            foreach ($bank as $bank) {
                                $selected = '';
                                if ($request->get('bank') == $bank->m_bank_id)
                                    $selected = 'selected';

                                echo "
									<option value='" . $bank->m_bank_id . "' $selected>" . $bank->nama_bank . "</option>";
                            }

                            ?>

                        </select>
                    </div>
                </div>
                <div class="col-12" id="pesan" style="padding: 20px;color:red">

                </div>
            </div>
            
            <div class="form-group row">
                @if(@$data['page']!='ajuan')
                <div class="col-md-7">
                    <a href="{!! route('be.transaksi',[$data['page'],$data['subpage']]) !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
                    <!-- <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcelRp"><span class="fa fa-file-excel"></span> Excel Rp</button>-->
                    <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan"><span class="fa fa-search"></span> Ajuan</button>
                    <button type="submit" name="Cari" class="btn btn-primary" value="EditKaryawan"><span class="fa fa-search"></span> Edit Karyawan</button>
                </div>
                <div class="col-md-5">
                    @if(!empty($request->get('Cari')) and ($request->get('view')=='appr'  or $request->get('preview')=='Gaji'))
                    @if(@$data['page']=='non_ajuan')
                    <button type="button" onclick="submit_ajukan()" class="btn btn-primary pull-right" value="Ajuan HR"><span class="fa fa-file-excel"></span> Ajuan HR</button>
                   <!-- <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan HR"><span class="fa fa-search"></span> History Ajuan Review</button>-->
                    @elseif(@$data['page']=='direksi')
                    <input type="hidden" value="appr" name="view"/>
                    <button type="button" onclick="submit_voucher()" name="Cari" class="btn btn-primary pull-right" value="Approval"><span class="fa fa-file-excel"></span> Approval Voucher</button>
                    <button type="button" onclick="submit_approve()" name="Cari" class="btn btn-primary pull-right" value="Approval"><span class="fa fa-file-excel"></span> Approval Data</button>
                    <!--<button type="submit" name="Cari" class="btn btn-primary" value="Approval"><span class="fa fa-search"></span> History Approval Direktur</button>-->

                    @endif
                    @endif

                </div>
                @endif
                @if(@$data['page']=='ajuan')
                <div class="col-md-9">
                	<input type="hidden" name="view" value="view"/>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan"><span class="fa fa-search"></span> Ajuan</button>
                     <button type="submit" name="Cari" class="btn btn-primary" value="ExcelAjuan"><span class="fa fa-search"></span> Excel Ajuan</button>
                     <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel Detail Gaji</button>
                </div>
                
                 @if(!empty($request->get('Cari')) and $request->get('view')=='appr')
                	<input type="hidden" name="view" value="appr"/>
                
                <div class="col-md-3">
                    <button type="button" onclick="submit_konfirmasi()" name="Cari" class="btn btn-primary pull-right" value="Konfirmasi"><span class="fa fa-file-excel"></span> Konfirmasi</button>
                    <!--<button type="submit" name="Cari" class="btn btn-primary" value="Approval"><span class="fa fa-search"></span> History Konfirmasi</button>-->
                   
                </div>
             @endif
                @endif
            </div>
            <input type="hidden" name="submit_appr" id="submit_appr" value="">
            <div id="Cari_submit">
            	
            </div>
            <!--<input type="hidden" name="Cari" id="Cari_submit" value="">-->
        </div>	
   		@endif  
        </div>	
   	@endif  
        <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
        <script type="text/javascript">
            function submit_konfirmasi() {
                txt = $("#entitas option:selected").text();
                entitas = $("#entitas").val();
                pajakonoff = $("#pajakonoff").val();
                if (entitas) {
                    $('#pesan').html('')
                    $('#submit_appr').val('Konfirmasi');
                    $('#Cari_submit').val('Cari');

                    swal({
                        title: 'Konfirmasi Selesai Tranfer Gaji Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Konfirmasi!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Konfirmasi !',
                        cancelButtonText: ' Batalkan!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            event.preventDefault();
                            document.getElementById('cari_absen').submit();
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === swal.DismissReason.cancel
                        ) {
                            swal(
                                'Cancelled',
                                'Approve Dibatalkan :)',
                                'error'
                            )
                        }
                    })

                } else {
                    $('#pesan').html('Entitas dan Pajak Harus Dipilih')
                }
            }

            function submit_approve() {
                txt = $("#entitas option:selected").text();
                entitas = $("#entitas").val();
                pajakonoff = $("#pajakonoff").val();
                if (entitas) {
                    $('#pesan').html('')
                    $('#submit_appr').val('Approve');
                    $('#Cari_submit').val('Cari');
                    
                    swal({
                        title: 'Approval Data Gaji Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Approval Direktur!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Approve !',
                        cancelButtonText: ' Batalkan!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            event.preventDefault();
                            document.getElementById('cari_absen').submit();
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === swal.DismissReason.cancel
                        ) {
                            swal(
                                'Cancelled',
                                'Approve Dibatalkan :)',
                                'error'
                            )
                        }
                    })

                } else {
                    $('#pesan').html('Entitas dan Pajak Harus Dipilih')
                }
            }
function submit_voucher() {
                txt = $("#entitas option:selected").text();
                entitas = $("#entitas").val();
                pajakonoff = $("#pajakonoff").val();
                if (entitas) {
                    $('#pesan').html('')
                    $('#submit_appr').val('Approve Voucher');
                    $('#Cari_submit').val('Cari');
                    
                    swal({
                        title: 'Approval Voucher Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Approval Direktur!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Approve !',
                        cancelButtonText: ' Batalkan!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            event.preventDefault();
                            document.getElementById('cari_absen').submit();
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === swal.DismissReason.cancel
                        ) {
                            swal(
                                'Cancelled',
                                'Approve Dibatalkan :)',
                                'error'
                            )
                        }
                    })

                } else {
                    $('#pesan').html('Entitas dan Pajak Harus Dipilih')
                }
            }

            function submit_ajukan() {
                txt = $("#entitas option:selected").text();
                entitas = $("#entitas").val();
                pajakonoff = $("#pajakonoff").val();
                if (entitas) {
                    $('#pesan').html('')
                    $('#submit_appr').val('AjukanHR');
                    
                    //$('#Cari_submit').val('Cari');
					$('<input>').attr({
					    type: 'hidden',
					    value: 'Cari',
					    name: 'Cari'
					}).appendTo('#Cari_submit');
                    swal({
                        title: 'Ajukan Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Pengajuan Review Gaji Direktur!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Ajukan !',
                        cancelButtonText: ' Batalkan!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            event.preventDefault();
                            document.getElementById('cari_absen').submit();
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === swal.DismissReason.cancel
                        ) {
                            swal(
                                'Cancelled',
                                'Pengajuan Dibatalkan :)',
                                'error'
                            )
                        }
                    })

                } else {
                    $('#pesan').html('Entitas dan Pajak Harus Dipilih')
                }
            }


            function activeTag(id) {
                swal({
                    title: 'Are you sure?',
                    text: "",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Active it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        event.preventDefault();
                        document.getElementById('delete-form-' + id).submit();
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        swal(
                            'Cancelled',
                            'Your data is safe :)',
                            'error'
                        )
                    }
                })
            }
        </script>