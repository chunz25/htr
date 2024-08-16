<div class="page-title">
	<div class="title_left">
		<h3>Detail Approval Daily Report Activity</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="col-md-12 col-sm-12 col-xs-12" style="height:15px;"></div>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="col-md-8"></div>
			<div class="col-md-4">
				<div class="form-group">
					<div class="pull-right">
						<label class="control-label col-md-4 col-sm-4 col-xs-12">Status Form</label>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<span class="badge badge-primary">
								<?php echo $info['status']; ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<form id="id_formdaily" class="form-horizontal form-label-left">
				<input type="hidden" name="pegawaiid" value="<?php echo $this->input->get('pegawaiid'); ?>" />
				<input type="hidden" name="nourut" value="<?php echo $this->input->get('nourut'); ?>" />
				<input type="hidden" name="pengajuanid" value="<?php echo $this->input->get('pengajuanid'); ?>" />
				<div class="x_panel">
					<div class="x_title">
						<h2>Data Karyawan</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="col-md-4">
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">NIK</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['nik']; ?></span>
								</div>
							</div>
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['nama']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['jabatan']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['lokasi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Unit Bisnis</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext">ECI</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Direktorat</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['direktorat']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Divisi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['divisi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['departemen']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['seksi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?php echo $info['subseksi']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Form Daily Report Activity</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="pull-right">
							<button type="button" id="id_detail" class="btn btn-success" aria-label="Left Align">Lihat Detail</button>
						</div>
						<table id="gridcontent" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">No </th>
									<th class="column-title">Jenis Form </th>
									<th class="column-title">Tanggal </th>
									<th class="column-title">Activity Job </th>
									<th class="column-title">Keterangan </th>
									<th class="column-title"></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($daftarcuti as $r) : ?>
									<tr data-id="<?php echo $i; ?>" data-jenis="<?php echo $r['jenis']; ?>" data-tglmulai="<?php echo $r['tglmulai']; ?>" data-actjob="<?php echo $r['actjob']; ?>" data-keterangan="<?php echo $r['keterangan']; ?>">
										<td><?php echo $r['no']; ?></td>
										<td><?php echo $r['jenis']; ?></td>
										<td><?php echo $r['tglmulai']; ?></td>
										<td style="max-width:450px; white-space:pre-wrap; word-wrap: break-word"><?php echo $r['actjob']; ?></td>
										<td style="max-width:450px; white-space:pre-wrap; word-wrap: break-word"> <?php echo $r['keterangan']; ?></td>
										<td></td>
									</tr>
									<?php $i++; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_content">
						<div class="col-md-12 col-xs-12">
							<div class="x_title">
								<h2>Disetujui oleh</h2>
								<div class="clearfix"></div>
							</div>
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Nama</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasannama" class="form-control readtext">
											<?php echo $info['atasannama']; ?>
										</span>
										<input id="id_fieldatasanid" type="hidden" name="atasanid" value="<?php echo $info['atasanid']; ?>">
										<input id="id_fieldatasanemail" type="hidden" name="atasanemail" value="">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasanposisi" class="form-control readtext">
											<?php echo $info['atasanjabatan']; ?>
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-6"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-right">
			<input type="button" class="btn btn-success" value=" Kembali " onclick="history.back()">
		</div>
	</div>
</div>

<div id="id_winconfirmapprove" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<h4>Apakah anda yakin menyetujui Activity ini?</h4>
				<form id="id_form_content_approve" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="pegawaiid" />
					<input type="hidden" name="nourut" />
					<input type="hidden" name="atasanid" />
					<input type="hidden" name="atasanemail" />
					<input type="hidden" name="pengajuemail" />
					<input type="hidden" name="nama" />
					<input type="hidden" name="nik" />
					<input type="hidden" name="action" />
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<div id="id_winconfirmreject" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">�</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				<form id="id_form_content_reject" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="pegawaiid" />
					<input type="hidden" name="nourut" />
					<input type="hidden" name="atasanid" />
					<input type="hidden" name="atasanemail" />
					<input type="hidden" name="pengajuemail" />
					<input type="hidden" name="nama" />
					<input type="hidden" name="nik" />
					<input type="hidden" name="action" />
					<input type="hidden" name="batalalasan" />
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan Ditolak</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_alasanditolak" class="form-control" rows="3" name="batalalasan" placeholder="Alasan Ditolak"></textarea>
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirmreject_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<div id="id_win_content" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> x </span></button>
				<h4 class="modal-title" id="myModalLabel2">Detail Daily Activity Report <?php echo $r['nama']; ?></h4>
			</div>
			<div class="modal-body">

				<!-- Start Form Activity -->
				<form id="id_formcontent" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="pegawaiid" />
					<input type="hidden" name="nourut" />
					<input type="hidden" name="atasanid" />
					<input type="hidden" name="atasanemail" />
					<input type="hidden" name="pengajuemail" />
					<input type="hidden" name="nama" />
					<input type="hidden" name="nik" />
					<input type="hidden" name="action" />
					<div class="row">
						<div class="form-group" id="id_group_daily_report" style="display:none;">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Tanggal</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_cekhari" class="input-group date">
										<input type="text" name="cekhari" class="form-control" value="<?php echo $r['tglmulai']; ?>" readonly><span class="input-group-addon">
											<i class="glyphicon glyphicon-calendar"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div id="id_group_actjob" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Aktivitas Kerja</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_actjob" name="actjob" class="form-control" rows="5" readonly><?php echo $r['actjob']; ?>
								</textarea>
							</div>
						</div>
						<div id="id_group_keterangan" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Keterangan</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_keterangan" name="keterangan" class="form-control" rows="5" readonly><?php echo $r['keterangan']; ?>
								</textarea>
							</div>
						</div>
						<div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Analisa Atasan</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<textarea id="id_atasannotes" class="form-control" rows="4" name="atasannotes" placeholder="Analisa Atasan..."></textarea>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- End Form Activity -->

			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-default" value=" Setuju " onclick=$(this).showApprove("<?php echo $info['pegawaiid']; ?>","<?php echo $info['nourut']; ?>","<?php echo $info['atasanid']; ?>","","<?php echo $name = str_replace(' ', '_', $info["nama"]); ?>","<?php echo $info["nik"]; ?>","1")>
				<input type="button" class="btn btn-default" value=" Tolak " onclick=$(this).showRejected("<?php echo $info['pegawaiid']; ?>","<?php echo $info['nourut']; ?>","<?php echo $info['atasanid']; ?>","","","<?php echo $name = str_replace(' ', '_', $info["nama"]); ?>","<?php echo $info["nik"]; ?>","2")>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view("v_wincaripegawai.php"); ?>

<script type="text/javascript">
	$(function() {
		var availabledates = jQuery.parseJSON('<?php echo $vharilibur; ?>');
		var formdaily = $('#id_formdaily');
		formdaily[0].reset();

		$('#id_detail').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			// $('#id_winconfirmapprove').modal('show');

			$('#id_win_content').modal('show');
			$('#id_win_content').on('shown.bs.modal', function() {
				$('#id_jenisform').val('1');
				$('#id_group_daily_report').show();
			});

		});

		$('#id_cekhari').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function(date) {
				var mindate = moment().add(0, 'd').format("YYYY-MM-DD"); // D-1
				var curr_date = moment(date).format("YYYY-MM-DD"); // Current Calendar
				var numDays = moment(date).isoWeekday(); // 1 week
				var checked = $.inArray(curr_date, availabledates); // Today
				if ((curr_date > mindate) && (checked == -1 && (numDays < 8))) {
					return false;
				} else {
					return false;
				}
			}
		});

		$.fn.showApprove = function(pegawaiid, nourut, atasanid, atasanemail, pengajuemail, nama, nik, action, atasannotes) {
			var form = $('#id_formcontent');
			$('#id_win_content').modal('hide');
			$('#id_winconfirmapprove').modal('show');
			form.find('input[name=pegawaiid]').val(pegawaiid);
			form.find('input[name=nourut]').val(nourut);
			form.find('input[name=atasanid]').val(atasanid);
			form.find('input[name=atasanemail]').val(atasanemail);
			form.find('input[name=pengajuemail]').val(pengajuemail);
			form.find('input[name=nama]').val(nama);
			form.find('input[name=nik]').val(nik);
			form.find('input[name=action]').val(action);
			form.find('input[name=atasannotes]').val(atasannotes);

			var cekhari = moment($('#id_cekhari').datepicker('getDate')).format('dddd');
			$('#id_confirm_yes').on('click', function(e) {
				if (cekhari == 'Friday') {
					$('#id_form_content_approve').validate({
						onfocusout: false,
						rules: {
							atasannotes: {
								required: true
							},
						},
						highlight: function(element) {
							$(element).closest('.form-group').addClass('has-error');
						},
						unhighlight: function(element) {
							$(element).closest('.form-group').removeClass('has-error');
						},
						errorElement: 'span',
						errorClass: 'help-block',
						errorPlacement: function(error, element) {
							if (element.parent('.input-group').length) {
								error.insertAfter(element.parent());
							} else {
								error.insertAfter(element);
							}
						},
						messages: {
							atasannotes: {
								required: "Harap mengisi Penilaian Atasan form Daily Report"
							},
						}
					});
				}

				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_formcontent');
				if (form.valid()) {
					$.ajax({
						type: 'POST',
						url: SITE_URL + '/listapprove/approveDaily',
						data: form.serialize(),
						async: false,
						cache: false,
						dataType: 'json',
						beforeSend: function() {
							$("#loading-image").show(0).delay(15000).hide(0);
							window.location = SITE_URL + '/listapprove/detaillistdaily?pegawaiid=' + Base64.encode(pegawaiid);
						},
						success: function(response) {
							if (response.success) {
								var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
								var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
								$("#loading-image").hide();
								$('#id_winconfirmapprove').modal('hide');
								location.href = SITE_URL + '/listapprove/detaillistdaily?pegawaiid=' + Base64.encode(pegawaiid);
								$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
							}
						}
					});
				}
			});
		}

		$.fn.showRejected = function(pegawaiid, nourut, atasanid, atasanemail, pengajuemail, nama, nik, action, batalalasan) {
			var form = $('#id_form_content_reject');
			$('#id_win_content').modal('hide');
			$('#id_winconfirmreject').modal('show');
			form.find('input[name=pegawaiid]').val(pegawaiid);
			form.find('input[name=nourut]').val(nourut);
			form.find('input[name=atasanid]').val(atasanid);
			form.find('input[name=atasanemail]').val(atasanemail);
			form.find('input[name=pengajuemail]').val(pengajuemail);
			form.find('input[name=nama]').val(nama);
			form.find('input[name=nik]').val(nik);
			form.find('input[name=action]').val(action);
			form.find('input[name=batalalasan]').val(batalalasan);

			$('#id_confirmreject_yes').on('click', function(e) {
				$('#id_form_content_reject').validate({
					onfocusout: false,
					rules: {
						batalalasan: {
							required: true
						},
					},
					highlight: function(element) {
						$(element).closest('.form-group').addClass('has-error');
					},
					unhighlight: function(element) {
						$(element).closest('.form-group').removeClass('has-error');
					},
					errorElement: 'span',
					errorClass: 'help-block',
					errorPlacement: function(error, element) {
						if (element.parent('.input-group').length) {
							error.insertAfter(element.parent());
						} else {
							error.insertAfter(element);
						}
					},
					messages: {
						batalalasan: {
							required: "Harap mengisi alasan tolak form kehadiran"
						},
					}
				});

				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_form_content_reject');
				if (form.valid()) {
					$.ajax({
						type: 'POST',
						url: SITE_URL + '/listapprove/rejectDaily',
						data: form.serialize(),
						async: false,
						cache: false,
						dataType: 'json',
						beforeSend: function() {
							$("#loading-image").show(0).delay(15000).hide(0);
							window.location = '<?php echo site_url(); ?>' + '/listapprove';
						},
						success: function(response) {
							if (response.success) {
								var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
								var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
								$("#loading-image").hide();
								$('#id_winconfirmreject').modal('hide');
								location.href = SITE_URL + "/listapprove/";
								$.fn.load_grid_content(tglmulai, tglselesai, start, limit);
							}
						}
					});
				}
			});
		}
	});
</script>

<style>
	.div-checkbox {
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxcheck {
		width: 20px;
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxlabel {
		margin-top: 1px;
		display: inline-block;
		vertical-align: middle;
	}

	.div-checkbox .boxinput {
		margin-top: 1px;
		display: inline-block;
		vertical-align: middle;
	}

	.highlight_calender {
		background-color: #d90036 !important;
		color: #fff;
	}

	#loading-image {
		display: none;
		position: fixed;
		z-index: 1000;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		background: rgba(255, 255, 255, .8) url('<?php echo base_url(); ?>media/asset/images/loading-image.gif') 50% 50% no-repeat;
	}
</style>