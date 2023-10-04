
					<!-- /Page Title -->
				 <?php 
                //$id_prl = $request
                $view = 'pdf';
                $id_kary = $request->get('p_karyawan');
                echo view('frontend.slip.pdf_slip',compact('karyawan','request','id_prl','id_kary','help','generate','view'));?>
	