<div class="page-title">
	<div class="title_left">
		<h3>Daily Report Activity</h3>
	</div>
</div>
<input type="hidden" value="<?php foreach ($vstatusdaily as $r) : echo $r;
							endforeach; ?>">
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<form id="id_formdaily" class="form-horizontal form-label-left">
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
									<span class="form-control readtext"><?= $vinfopegawai['nik']; ?></span>
								</div>
							</div>
							<div>
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['nama']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Jabatan</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['jabatan']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Lokasi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['lokasi']; ?></span>
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
									<span class="form-control readtext"><?= $vinfopegawai['direktorat']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Divisi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['divisi']; ?></span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Departemen</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['departemen']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['seksi']; ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Seksi</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<span class="form-control readtext"><?= $vinfopegawai['subseksi']; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_content">
						<div class="pull-right">
							<button type="button" id="id_tambah" class="btn btn-success" aria-label="Left Align">Tambah</button>
						</div>
						<table id="gridcontent" class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">No </th>
									<th class="column-title">Jenis Form </th>
									<th class="column-title">Tanggal </th>
									<th class="column-title">Aktivitas Kerja </th>
									<th class="column-title">Keterangan </th>
									<th class="column-title"></th>
								</tr>
							</thead>
							<tbody>
								<?php if ($vdraftdaily) {
									for ($x = 0; $x < count(json_decode($vdraftdaily)); $x++) { ?>
										<tr>
											<td><?= json_decode($vdraftdaily)[$x]->rn; ?> </td>
											<td>Daily Report Activity</td>
											<td><?= json_decode($vdraftdaily)[$x]->waktu; ?> </td>
											<td style="max-width:450px; white-space:pre-wrap; word-wrap: break-word"><?= json_decode($vdraftdaily)[$x]->actjob; ?> </td>
											<td style="max-width:450px; white-space:pre-wrap; word-wrap: break-word"><?= json_decode($vdraftdaily)[$x]->keterangan; ?> </td>
											<td>
												<button type="button" id="<?= 'id_del' . $x; ?>" class="btn btn-danger btn-xs" aria-label="Left Align" title="Hapus"><span class="glyphicon glyphicon-remove">
													</span></button>

												<button type="button" id="<?= 'id_rows' . $x; ?>" class="btn btn-success btn-xs" aria-label="Left Align" title="Edit"><span class="glyphicon glyphicon-pencil">
													</span></button>
											</td>
										</tr>
								<?php }
								} ?>
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
										<span id="id_fieldatasan2nama" class="form-control readtext">
											<?= $vinfoatasan['atasannama']; ?>
										</span>
										<input id="id_fieldatasan2id" type="hidden" name="atasanid" value="<?php echo $vinfoatasan['atasanid']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12">Jabatan</label>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<span id="id_fieldatasan2posisi" class="form-control readtext">
											<?= $vinfoatasan['atasanjab']; ?>
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
			<button type="button" id="id_ajukandaily" class="btn btn-success" aria-label="Left Align">Ajukan</button>
		</div>
	</div>
</div>



<div id="id_win_content" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel2">Form Daily Activity Report</h4>
			</div>
			<div class="modal-body">

				<!-- Start Form Activity -->
				<form id="id_formcontent" class="form-horizontal form-label-left" action="./" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Jenis Form</label>
							<div class="col-md-10 col-sm-10 col-xs-12" style="padding-left:10px;">
								<select id="id_jenisform" class="form-control" name="jenisform">
									<option value="1">Daily Report Activity</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="id_group_daily_report" style="display:none;">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">Tanggal</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div id="id_tglawal" class="input-group date">
										<input type="text" name="tglawal" class="form-control" readonly="readonly">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-calendar">
											</i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div id="id_group_actjob" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Aktivitas Kerja</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<textarea id="id_actjob" name="actjob" class="form-control" rows="8" onkeyup="this.value = this.value.replace(/[`\\]/g, '')"></textarea>
							</div>
						</div>
						<div id="id_group_keterangan" class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Keterangan</label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select name="idket" id="id_ket" class="form-control">
									<option value="1">WFO</option>
									<option value="2">Off</option>
									<option value="3">Lainnya</option>
								</select>
								<textarea id="id_keterangan" name="keterangan" class="form-control" rows="8" onkeyup="this.value = this.value.replace(/[`\\]/g, '')" style="display:none;"></textarea>
							</div>
						</div>
					</div>
				</form>
				<!-- End Form Activity -->
				<div class="alert alert-danger" id="id_popup_alert" role="alert" style="display:none;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="id_upddaily" type="button" class="btn btn-primary">Update</button>
				<button id="id_adddaily" type="button" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>

<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<img id="loading-image" style="display:none;" />
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Informasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda akan mengajukan form Daily Activity ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary" onclick="this.disabled=true;this.value='Mengirim, harap tunggu...';this.form.submit();">Ya</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view("v_wincaripegawai.php"); ?>

<script type="text/javascript">
	$(function() {
		var availabledates = jQuery.parseJSON('<?= $vharilibur; ?>');
		var formdaily = $('#id_formdaily');
		formdaily[0].reset();

		$.fn.addPengajuanDaily = function() {
			$('#id_formcontent').validate({
				onfocusout: false,
				rules: {
					jenisform: {
						required: true
					},
					tglawal: {
						required: true
					},
					actjob: {
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
					jenisform: {
						required: "Harap untuk mengisi field ini!"
					},
					tglawal: {
						required: "Harap untuk mengisi field ini!"
					},
					actjob: {
						required: "Harap untuk mengisi field ini!"
					},
				}
			});

			$('#id_tglawal').datepicker({
				format: 'dd/mm/yyyy',
				todayHighlight: true,
				autoclose: true,
				toggleActive: true,
				beforeShowDay: function(date) {
					var opendate = jQuery.parseJSON('<?= $vopendate; ?>');
					var mindate = moment().add(0, 'd').format("YYYY-MM-DD"); // D-1
					var mincrit = moment().add(-6, 'd').format("YYYY-MM-DD"); // D-4
					var curr_date = moment(date).format("YYYY-MM-DD"); // Current Calendar
					var numDays = moment(date).isoWeekday(); // 1 week
					var checked = $.inArray(curr_date, availabledates); // Today
					var satker = <?= strlen($vinfopegawai['satkerid']); ?>;
					var idpeg = jQuery.parseJSON('<?= $vopendate; ?>');
					// var idpeg = ['13121215', '15080236'];
					if (satker == 4 || idpeg == <?= $vinfopegawai['nik']; ?>) {
						return;
					} else {
						if ((curr_date > mindate) || (curr_date < mincrit) && (checked == -1 && (numDays < 8))) {
							return false;
						} else {
							return true;
						}
					}
				}
			});

			$('#id_jenisform').on('change', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var selected = $(this).find("option:selected").val();
				if (selected == '1') {
					$('#id_group_daily_report').show();
				} else {
					$('#id_group_daily_report').hide();
				}
			});

			$('#id_ket').on('change', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var selected = $(this).find("option:selected").val();
				if (selected == '3') {
					$('#id_keterangan').show();
				} else {
					$('#id_keterangan').hide();
				}
			});

			$('#id_adddaily').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();

				var form = $('#id_formcontent');

				if (form.valid()) {
					var jenisformid = $('#id_jenisform option:selected').val();
					var jenisformtext = $('#id_jenisform option:selected').text();
					var tglawal = $("#id_tglawal").find("input").val();
					var actjob = $('#id_actjob').val();
					var keterangan = $('#id_keterangan').val();
					var ketpilih = $('#id_ket option:selected').text();
					var gridRows = $('#gridcontent > tbody > tr');
					var id = 1;
					if (gridRows.length > 0) {
						id = $('#gridcontent > tbody > tr')[gridRows.length - 1].getAttribute('data-id');
						id = parseInt(id) + 1;
					}

					$.when($.ajax({
							url: SITE_URL + '/pengajuan/cekPengajuanDaily',
							type: 'POST',
							data: {
								act: 'add',
								tglawal: tglawal,
								nourut: ''
							}
						}))
						.then(function(data, textStatus, jqXHR) {
							var obj = jQuery.parseJSON(data);
							if (obj.data > 0) {
								$('#id_popup_alert').html('Anda sudah pernah mengajukan direntang tanggal yang sama');
								$('#id_popup_alert').fadeIn();
								return;
							}
							var pegawaiid = '<?= $vinfopegawai['pegawaiid']; ?>';
							var atasanid = '<?= $vinfoatasan['atasanid']; ?>';
							var formname = $('#id_jenisform option:selected').text();
							var tglawal = $("#id_tglawal").find("input").val();
							var actjob = $('#id_actjob').val();
							var keterangan = $('#id_keterangan').val();
							var ketpilih = $('#id_ket option:selected').text();

							$.ajax({
								type: "POST",
								url: SITE_URL + '/pengajuan/draftdaily',
								data: {
									pegawaiid: pegawaiid,
									formname: formname,
									tglawal: tglawal,
									actjob: actjob,
									keterangan: keterangan,
									ketpilih: ketpilih,
									atasanid: atasanid
								},
								success: function(res) {
									console.log('Crot..Crot..Crot..!');
									location.reload();
								}
							});
						});
				};
			});

		};

		$('#id_fieldpelimpahan').on('focus', function(e) {
			e.preventDefault();
			e.stopPropagation();

			$('#id_win_caripegawai').modal('show');
			$('#id_win_caripegawai').on('selected', function(event, record) {
				$('#id_fieldpelimpahanid').val(record.pegawaiid);
				$('#id_fieldpelimpahan').val(record.nik);
				$('#id_fieldpelimpahannama').val(record.nama);
				$('#id_fieldpelimpahanposisi').val(record.jabatan);
				$('#id_fieldpelimpahandivisi').val(record.unitkerja);
				$('#id_fieldpelimpahanlokasi').val(record.lokasi);
				$('#id_fieldpelimpahanhp').val(record.hp);
				$('#id_fieldpelimpahanunit').val('ECI');
			});

		});

		$.fn.addPengajuanDaily();
		$('#id_tambah').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var ketpilih = $('#id_ket option:selected').val();

			$('#id_win_content').modal('show');
			$('#id_win_content').on('shown.bs.modal', function() {
				$('#id_jenisform').val('1');
				$('#id_group_daily_report').show();
				$('#id_popup_alert').hide();
				$('#id_adddaily').show();
				$('#id_upddaily').hide();
				$('#id_popup_alert').html('');
				$('#id_tglawal').datepicker('setDate', null);
				if (ketpilih == 3) {
					$('#id_keterangan').show();
				} else {
					$('#id_keterangan').hide();
				}
			});
		});

		$('#id_upddaily').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var pegawaiid = '<?= $vinfopegawai['pegawaiid']; ?>';
			var atasanid = '<?= $vinfoatasan['atasanid']; ?>';
			var formname = $('#id_jenisform option:selected').text();
			var tglawal = $("#id_tglawal").find("input").val();
			var actjob = $('#id_actjob').val();
			var keterangan = $('#id_keterangan').val();
			var ketpilih = $('#id_ket option:selected').text();
			$.ajax({
				type: "POST",
				url: SITE_URL + '/pengajuan/upddraft',
				data: {
					pegawaiid: pegawaiid,
					formname: formname,
					tglawal: tglawal,
					actjob: actjob,
					keterangan: keterangan,
					ketpilih: ketpilih,
					atasanid: atasanid
				},
				success: function(res) {
					console.log('Crot..Update..Crot..!');
					location.reload();
				}
			});
		});

		<?php for ($i = 0; $i < count(json_decode($vdraftdaily)); $i++) { ?> $('#<?= 'id_del' . $i; ?>').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var draftid = '<?= json_decode($vdraftdaily)[$i]->draftid; ?>';
				$.ajax({
					type: "POST",
					url: SITE_URL + '/pengajuan/deldraft',
					data: {
						draftid: draftid
					},
					success: function(res) {
						console.log('Crot..Delete..Crot..!');
						location.reload();
					}
				});
			});
		<?php } ?>

		<?php for ($i = 0; $i < count(json_decode($vdraftdaily)); $i++) { ?> $('#<?= 'id_rows' . $i; ?>').on('click', function(e) {
				var actjob = `<?= json_decode($vdraftdaily)[$i]->actjob; ?>`;
				var keterangan = `<?= json_decode($vdraftdaily)[$i]->keterangan; ?>`;
				var ketpilih = ['WFO', 'Off', 'Lainnya'];
				var waktu = '<?= json_decode($vdraftdaily)[$i]->waktu; ?>';
				var tgl = waktu.substr(5, 10);
				if (ketpilih.includes(keterangan.substr(0, 7))) {
					// ketpilih = keterangan.substr(0, 7)
					if (keterangan.substr(0, 7) == 'WFO') {
						ketpilih = '1';
					}
					else if(keterangan.substr(0, 7) == 'Off') {
						ketpilih = '2';
					} else {
						ketpilih = '3';
					}
				}
				if (ketpilih == '3') {
					keterangan = keterangan.substr(10)
				}

				e.preventDefault();
				e.stopPropagation();
				id_ajukandaily
				$('#id_win_content').modal('show');
				$('#id_win_content').on('shown.bs.modal', function() {
					$('#id_jenisform').val('1');
					$('#id_actjob').val(actjob);
					$('#id_keterangan').val(keterangan);
					$('#id_ket').val(ketpilih);
					$('#id_group_daily_report').show();
					$('#id_popup_alert').hide();
					$('#id_upddaily').show();
					$('#id_adddaily').hide();
					$('#id_popup_alert').html('');
					$('#id_tglawal').datepicker('setDate', tgl);
					if (ketpilih == '3') {
						$('#id_keterangan').show();
					} else {
						$('#id_keterangan').hide();
					}
				});
			});
		<?php } ?>

		$('#id_ajukandaily').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var pegawaiid = '<?= $vinfopegawai['pegawaiid']; ?>';
			var namapegawai = '<?= $vinfopegawai['nama']; ?>';
			var nikpegawai = '<?= $vinfopegawai['nik']; ?>';
			var atasanid = '<?= $vinfoatasan['atasanid']; ?>';

			$.ajax({
				type: "POST",
				url: SITE_URL + '/pengajuan/simpandaily',
				data: {
					pegawaiid: pegawaiid,
					namapegawai: namapegawai,
					nikpegawai: nikpegawai,
					atasanid: atasanid
				},
				success: function(res) {
					console.log('Crot..Success..Crot..!');
					$("#loading-image").hide();
					$('#id_winconfirm').modal('hide');
					$("#id_confirm_yes").attr("disabled", true);
					window.location = '<?= site_url(); ?>' + '/history';
				}
			});
		});
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