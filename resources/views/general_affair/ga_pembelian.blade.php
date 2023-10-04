<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ES-iOS | GENERAL AFFAIR </title>
    <link href="logo.png" rel="shortcut icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        $( function() {
            $( "#tanggal" ).datepicker({
                dateFormat: "yy-mm-dd"
            });

            $( "#tgl" ).datepicker({
                dateFormat: "yy-mm-dd"
            });
        } );
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</head>
<body>
<div class="container">
    <hr>
    <p><h2 align="center"><b>FORM PENGAJUAN PEMBELIAN</b></h2></p>
    <hr>
    <br/>

    <div class="table-responsive">
        <div class="row" style="text-align: center; background-color: lightgreen">
            @include('flash-message')
        </div>
        <form method="post" id="dynamic_forms" action="{!! route('simpan_ga_pembelian') !!}">
        @csrf
            <!--<span id="result"></span>-->
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Nama Pengaju *</label>
                        <select class="form-control select2" name="pengaju" style="width: 100%;" required>
                            <option value="">Pilih</option>
                            <?php
                            foreach($karyawan AS $karyawan){
                                echo '<option value="'.$karyawan->p_karyawan_id.'">'.$karyawan->nama.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Unit Kerja *</label>
                        <select class="form-control select2" name="unit_kerja" style="width: 100%;" required>
                            <option value="">Pilih</option>
                            <?php
                            foreach($departemen AS $departemen){
                                echo '<option value="'.$departemen->m_departemen_id.'">'.$departemen->nama.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Sifat Pengajuan *</label>
                        <select class="form-control select2" name="pengajuan" style="width: 100%;" required>
                            <option value="">Pilih </option>
                            <option value="1">ATK BULANAN</option>
                            <option value="2">ASSET</option>
                            <option value="3">TOOLS MARKETING</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Tanggal Pengajuan *</label>
                        <input type="text" class="form-control datetimepicker-input" id="tanggal" name="tanggal" data-target="#tanggal"  value="" autocomplete="off"/>
                    </div>

                </div>
            </div>
            <hr>
            <table class="table table-bordered table-striped" id="user_table">
                <thead>
                <tr>
                    <th width="25%">Nama Barang</th>
                    <th width="10%">Qty</th>
                    <th width="25%">Deskripsi</th>
                    <th width="25%">Link Gambar</th>
                    <th width="15%">Tanggal Digunakan</th>
                    <th width="15%">Action</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3" align="right">&nbsp;</td>
                    <td>
                        @csrf
                        <a href="{!! route('list_ga') !!}" class="btn btn-primary">Kembali</a>
                            <!--<button type="button" name="save" id="save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>-->
                        <input type="submit" name="save" id="save" class="btn btn-primary" value="Simpan" />
                    </td>
                </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
</body>
</html>

<script>
    $(document).ready(function(){

        var count = 1;

        dynamic_field(count);

        function dynamic_field(number)
        {
            html = '<tr>';
            html += '<td><input type="text" name="nama[]" class="form-control" /></td>';
            html += '<td><input type="text" name="qty[]" class="form-control" /></td>';
            html += '<td><input type="text" name="desc[]" class="form-control" /></td>';
            html += '<td><input type="text" name="link[]" class="form-control" /></td>';
            html += '<td><input type="text" name="tgl[]" class="form-control" placeholder="yyyy-mm-dd" /></td>';
            if(number > 1)
            {
                html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
                $('tbody').append(html);
            }
            else
            {
                html += '<td><button type="button" name="add" id="add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button></td></tr>';
                $('tbody').html(html);
            }
        }

        $(document).on('click', '#add', function(){
            count++;
            dynamic_field(count);
        });

        $(document).on('click', '.remove', function(){
            count--;
            $(this).closest("tr").remove();
        });

        $('#dynamic_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:'',
                method:'post',
                data:$(this).serialize(),
                dataType:'json',
                beforeSend:function(){
                    $('#save').attr('disabled','disabled');
                },
                success:function(data)
                {
                    if(data.error)
                    {
                        var error_html = '';
                        for(var count = 0; count < data.error.length; count++)
                        {
                            error_html += '<p>'+data.error[count]+'</p>';
                        }
                        $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                    }
                    else
                    {
                        dynamic_field(1);
                        $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                    }
                    $('#save').attr('disabled', false);
                }
            })
        });

    });
</script>