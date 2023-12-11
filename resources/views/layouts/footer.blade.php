<?php 

if(isset($nojs)){
	
}else{
echo  	'<script src="'.url('plugins/dleohr/assets/js/jquery-3.2.1.min.js').'"></script> ';
}?>
		
<!-- Bootstrap Core JS -->
<script src="<?= url('plugins/purple/assets/js/popper.min.js')?>"></script>
<script src="<?= url('plugins/purple/assets/js/bootstrap.min.js')?>"></script>
		
<!-- Slimscroll JS -->
<script src="<?= url('plugins/purple/assets/js/jquery.slimscroll.min.js')?>"></script>
		
<!-- Custom JS -->
		
<!-- Summernote -->
<script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"></script>

<script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
<script src="<?= url('plugins/purple/assets/js/jquery.dataTables.min.js')?>"></script>
<script src="<?= url('plugins/purple/assets/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?= url('plugins/purple/assets/js/apps.js')?>"></script>
<script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
<script src="{!! asset('plugins/table-with-fixed-header-and-sidebar/dist/script.js') !!}"></script>
<script src="{!! asset('plugins/pace-progress/pace.min.js') !!}"></script>
<!-- ChartJS -->
<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
<!-- JQVMap -->
<script src="{!! asset('plugins/jqvmap/jquery.vmap.min.js') !!}"></script>
<script src="{!! asset('plugins/jqvmap/maps/jquery.vmap.usa.js') !!}"></script>
<!-- jQuery Knob Chart -->
<script src="{!! asset('plugins/jquery-knob/jquery.knob.min.js') !!}"></script>
<!-- daterangepicker -->
<script src="{!! asset('plugins/moment/moment.min.js') !!}"></script>
<script src="{!! asset('plugins/daterangepicker/daterangepicker.js') !!}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{!! asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') !!}"></script>
<!-- Summernote -->
<script src="{!! asset('plugins/summernote/summernote-bs4.min.js') !!}"></script>
<!-- overlayScrollbars -->
<script src="{!! asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') !!}"></script>
<!-- AdminLTE App -->
<script src="{!! asset('dist/js/adminlte.js') !!}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{!! asset('dist/js/pages/dashboard.js') !!}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{!! asset('dist/js/demo.js') !!}"></script>
<!-- DataTables -->
<script src="{!! asset('plugins/datatables/jquery.dataTables.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') !!}"></script>
<script src="{!! asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') !!}"></script>
<!-- bootstrap color picker -->
<script src="{!! asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') !!}"></script>
<!-- Select2 -->
<script src="{!! asset('plugins/select2/js/select2.full.min.js') !!}"></script>
<!-- Bootstrap Switch -->
<script src="{!! asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}"></script>
<!-- BS-Stepper -->
<script src="{!! asset('plugins/bs-stepper/js/bs-stepper.min.js') !!}"></script>
<!-- dropzonejs -->
<script src="{!! asset('plugins/dropzone/min/dropzone.min.js') !!}"></script>
<script src="{!! asset('plugins/chart.js/Chart.min.js') !!}"></script>
<script src="{!! asset('plugins/filterizr/jquery.filterizr.min.js') !!}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
	$(document).ready(function(){
			$('.masking0').mask('###', {reverse: true});
			$('.masking1').mask('#.##0', {reverse: true});
			$('.masking2').mask('#.##0,0', {reverse: true});
			$('.masking3').mask('#,##0', {reverse: true});
		})
	if ($('.datepicker-input').length > 0) {
		$('.datepicker-input').datetimepicker({
				format: 'DD/MM/YYYY',
				pickTime: false ,
				icons: {
					up: "fa fa-angle-up",
					down: "fa fa-angle-down",
					next: 'fa fa-angle-right',
					previous: 'fa fa-angle-left'
				}
			});
	}
	$(function () {
			$("#example1").DataTable({
					"responsive": true,
					"autoWidth": false,
				});
			$('#example2').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": false,
					"ordering": true,
					"info": true,
					"autoWidth": false,
					"responsive": true,
				});
				$("#example3").DataTable({
					"responsive": true,
					"autoWidth": false,
				});$("#example4").DataTable({
					"responsive": true,
					"autoWidth": false,
				});$("#example5").DataTable({
					"responsive": true,
					"autoWidth": false,
				});$("#example6").DataTable({
					"responsive": true,
					"autoWidth": false,
				});$("#example7").DataTable({
					"responsive": true,
					"autoWidth": false,
				});		
			$("#exam").DataTable({
				fixedColumns: {
					left: 2
				},
				"scrollY" : 500,
				"scrollX": true,
				"ordering": true,
				"lengthChange": true,
			});
		});
	$('.select2').select2()

	//Initialize Select2 Elements
	$('.select2bs4').select2({
			theme: 'bootstrap4'
		});
</script>
	
<script type="text/javascript">
	//Initialize Select2 Elements
	$('.select2').select2()

	//Initialize Select2 Elements
	$('.select2bs4').select2({
			theme: 'bootstrap4'
		})
	function handleNumber(event, mask) {
		/* numeric mask with pre, post, minus sign, dots and comma as decimal separator
		{}: positive integer
		{10}: positive integer max 10 digit
		{,3}: positive float max 3 decimal
		{10,3}: positive float max 7 digit and 3 decimal
		{null,null}: positive integer
		{10,null}: positive integer max 10 digit
		{null,3}: positive float max 3 decimal
		{-}: positive or negative integer
		{-10}: positive or negative integer max 10 digit
		{-,3}: positive or negative float max 3 decimal
		{-10,3}: positive or negative float max 7 digit and 3 decimal
		*/
		with (event) {
			stopPropagation()
			preventDefault()
			if (!charCode) return
			var c = String.fromCharCode(charCode)
			if (c.match(/[^-\d,]/)) return
			with (target) {
				var txt = value.substring(0, selectionStart) + c + value.substr(selectionEnd)
				var pos = selectionStart + 1
			}
		}
		var dot = count(txt, /\./, pos)
		txt = txt.replace(/[^-\d,]/g,'')

		var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
		var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
		if (!txt.match('^' + (!sign?'':'-?') + '\\d*' + (!decimals?'':'(,\\d*)?') + '$')) return

		txt = txt.split(',')
		if (integers && txt[0] && count(txt[0],/\d/) > integers) return
		if (decimals && txt[1] && txt[1].length > decimals) return
		txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

		with (event.target) {
			value = mask[1] + txt.join(',') + mask[5]
			selectionStart = selectionEnd = pos + (pos==1 ? mask[1].length : count(value, /\./, pos) - dot) 
		}

		function count(str, c, e) {
			e = e || str.length
			for (var n=0, i=0; i<e; i+=1) if (str.charAt(i).match(c)) n+=1
			return n
		}
	}
	function format(number, prefix='Rp ', decimals = 2, decimalSeparator = ',', thousandsSeparator = '.') {
		const roundedNumber = number.toFixed(decimals);
		let integerPart = '',
		fractionalPart = '';
		if (decimals == 0) {
			integerPart = roundedNumber;
			decimalSeparator = '';
		} else {
			let numberParts = roundedNumber.split('.');
			integerPart = numberParts[0];
			fractionalPart = numberParts[1];
		}
		integerPart =prefix+ integerPart.replace(/(\d)(?=(\d{3})+(?!\d))/g, `$1${thousandsSeparator}`);
		return `${integerPart}${decimalSeparator}${fractionalPart}`;
	}
	function rupiahtonumber(text){
		var chars = {'.':'',',':'.','R':'','p':'',' ':''};

		text = text.replace(/[.,Rp ]/g, m => chars[m]);


		return text
	}
	
</script>
</script>
</body>
</html>