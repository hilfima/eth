<form id="cari_absen" class="form-horizontal" method="get" action="{!! route('be.previewthr',$data['page']) !!}">
    <div class="card">
        @if(@$data['page']=='non_ajuan')
        <div class="card-header">
            <!--<h3 class="card-title">DataTable with default features</h3>-->
            <a href="{!! route('be.tambah_generate_thr') !!}" target="_blank" class="btn btn-sm btn-primary" title='Sync Absen' data-toggle='tooltip'><span class='fa fa-plus'></span> Tambah Generate THR </a>
            
        </div>
        @endif
        <div class="card-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-lg-6">
                	
                    <div class="form-group">
                        <label>Periode THR</label>
                        <select class="form-control select2" name="prl_generate" style="width: 100%;" required>
                            <option value="">Pilih Periode THR</option>
                            <?php
                            foreach ($periode as $periode) {
                                	$selected = '';
                                if ($periode->prl_generate_id == $id_prl) {
                                	$selected = 'selected';
                                   
                                } 
                                    echo '<option  value="' . $periode->prl_generate_id . '">Periode: ' . $periode->tahun_gener . '</option>';
                                
                            }
                            ?>
                        </select>
                    </div>
                   
                </div>
                
                
                <div class="col-lg-6">
                </div>
               

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
                    <a href="{!! route('be.previewgaji',$data['page']) !!}" class="btn btn-primary"><span class="fa fa-sync"></span> Reset</a>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Cari"><span class="fa fa-search"></span> Cari</button>


                    <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcel"><span class="fa fa-file-excel"></span> Excel</button>
                    <!-- <button type="submit" name="Cari" class="btn btn-primary" value="RekapExcelRp"><span class="fa fa-file-excel"></span> Excel Rp</button>-->
                    <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan"><span class="fa fa-search"></span> Ajuan</button>
                </div>
                <div class="col-md-5">
                    @if(!empty($request->get('Cari')))
                    @if(@$data['page']=='non_ajuan')
                    <button type="button" onclick="submit_ajukan()" class="btn btn-primary" value="Ajuan HR"><span class="fa fa-file-excel"></span> Ajuan HR</button>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan HR"><span class="fa fa-search"></span> History Ajuan Review</button>
                    @elseif(@$data['page']=='direksi')
                    <button type="button" onclick="submit_approve()" name="Cari" class="btn btn-primary" value="Approval"><span class="fa fa-file-excel"></span> Approval Direktur</button>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Approval"><span class="fa fa-search"></span> History Approval Direktur</button>

                    @endif
                    @endif

                </div>
                @endif
                @if(@$data['page']=='konfirmasi')
                <div class="col-md-9">
                    <button type="submit" name="Cari" class="btn btn-primary" value="Ajuan"><span class="fa fa-search"></span> Ajuan</button>
                </div>
                
                 @if(!empty($request->get('Cari')))
                <div class="col-md-3">
                    <button type="button" onclick="submit_konfirmasi()" name="Cari" class="btn btn-primary" value="Konfirmasi"><span class="fa fa-file-excel"></span> Konfirmasi</button>
                    <button type="submit" name="Cari" class="btn btn-primary" value="Approval"><span class="fa fa-search"></span> History Konfirmasi</button>
                </div>
             @endif
                @endif
            </div>
            <input type="hidden" name="submit_appr" id="submit_appr" value="">
        </div>
        <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
        <script type="text/javascript">
            function submit_konfirmasi() {
                txt = $("#entitas option:selected").text();
                entitas = $("#entitas").val();
                pajakonoff = $("#pajakonoff").val();
                if (entitas) {
                    $('#pesan').html('')
                    $('#submit_appr').val('Konfirmasi');

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

                    swal({
                        title: 'Approval Gaji Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Approval Directur!",
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

                    swal({
                        title: 'Ajukan Entitas ' + txt + ' ' + pajakonoff + '?',
                        text: "Submit Pengajuan Review Gaji Directur!",
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