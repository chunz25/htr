<div class="page-title">
	<div class="title_left">
		<h3>History Absensi Pegawai</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<div class="column1">
					<div id="id_tglawal" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control" onkeydown="return false"><span
							class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<div id="id_tglakhir" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control" onkeydown="return false"><span
							class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align"
						style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="pull-right">
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<table id="gridcontent" class="table table-striped jambo_table bulk_action">
						<thead>
							<tr class="headings">
								<th class="column-title">No </th>
								<th class="column-title">NIK </th>
								<th class="column-title">Nama </th>
								<th class="column-title">Hari </th>
								<th class="column-title">Tanggal </th>
								<th class="column-title">Scan Masuk </th>
								<th class="column-title">Scan Keluar </th>
								<th class="column-title">Pengecualian </th>
								<th class="column-title">Keterangan </th>
								<th class="column-title">Action </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div class="pull-right">
						<ul id="gridpaging" class="pagination-sm"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- MODAL TAMBAH ABSENSI -->
<div id="id_win_content" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel2">Form Pengajuan Absensi</h4>
			</div>
			<div class="modal-body">

				<!-- Start Form Activity -->
				<form id="id_formcontent" class="form-horizontal form-label-left" action="./" method="POST"
					enctype="multipart/form-data">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Tanggal</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="input-group date">
									<input type="text" id="tglabsen" name="tglabsen" class="form-control" readonly>
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Jam Masuk</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="input-group">
									<input type="time" id="masuk" name="masuk" class="form-control"
										onkeypress="return false">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Jam Keluar</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="input-group">
									<input type="time" id="keluar" name="keluar" class="form-control"
										onkeypress="return false">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-12">Alasan Absensi</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<textarea id="alasan" name="alasan" class="form-control" rows="5"
									onkeyup="this.value = this.value.replace(/[`\\]/g, '')"></textarea>
							</div>
						</div>
					</div>
				</form>
				<!-- End Form Activity -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button id="id_addabsen" type="button" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth(),
			d = date.getDate();
		var firstDay = new Date(y, m, 1);
		var lastDay = new Date(y, m, d);
		firstDay2 = moment(firstDay).format('YYYY-MM-DD');
		lastDay2 = moment(lastDay).format('YYYY-MM-DD');
		var nik = '<?= $vnik; ?>';
		var atasanid = '<?= $vatasan; ?>';

		$('#id_tglawal').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function (date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglawal').datepicker("setDate", firstDay);

		$('#id_tglakhir').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function (date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
				var mindate = moment().add(0, 'd').format("YYYY-MM-DD"); // D-1

				if ((curr_date > mindate)) {
					return false;
				} else {
					return true;
				}
			}
		});
		$('#id_tglakhir').datepicker("setDate", lastDay);

		$.fn.load_grid_content = function (nik, tglmulai, tglselesai, start) {
			$.getJSON(
				SITE_URL + '/absensi/getListAbsensi',
				'nik=' + nik + '&startdate=' + tglmulai + '&enddate=' + tglselesai + '&top=' + start,
				function (response) {

					let html = '';
					$.each(response.data, function (index, record) {
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" " width="150">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.hari) ? '' : record.hari) + '</td>';
						html += '<td class=" " width="120">' + (isEmpty(record.tgl) ? '' : record.tgl) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.scanmasuk) ? '' : record.scanmasuk) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.scankeluar) ? '' : record.scankeluar) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.pengecualian) ? '' : record.pengecualian) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.ket) ? '' : record.ket) + '</td>';
						html += '<td class=" ">';
						let param1 = record.scanmasuk === null && record.ket === null && record.tgl < lastDay2;
						let param2 = record.scankeluar === null && record.ket === null && record.tgl < lastDay2;

						if (param1 || param2) {
							html += '<a onclick=$(this).tambahAbsensi("' + record.nik + '","' + record.tgl + '","' + record.scanmasuk + '","' + record.scankeluar + '") class="btn btn-success btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';
						}
						html += '</td>';
						html += '</tr>';
					});

					$('#gridcontent > tbody').html(html);
					$("#gridcontent tbody tr").click(function () {
						let selected = $(this).hasClass("activated");
						$("#gridcontent tr").removeClass("activated");
						if (!selected) $(this).addClass("activated");
					});

					if (response.count > 0) {
						if ($('#gridpaging').data("twbs-pagination")) {
							$('#gridpaging').twbsPagination('destroy');
						}
						let visiblePages = Math.ceil(response.count / 25);
						let next_page = response.paging + 1;
						$('#gridpaging').twbsPagination({
							initiateStartPageClick: false,
							totalPages: visiblePages,
							visiblePages: 10,
							startPage: next_page,
							onPageClick: function (event, page) {
								let start = (page - 1) * 25;
								$.fn.load_grid_content(nik, tglmulai, tglselesai, start);
							}
						});

					}
				}
			)
		};

		$.fn.tambahAbsensi = function (nik, tgl, masuk, keluar) {

			$('#id_win_content').modal('show');
			$('#id_win_content').on('shown.bs.modal', function () {
				$('#alasan').val('');
				$('#tglabsen').val(tgl);
				$('#masuk').val(masuk);
				$('#keluar').val(keluar);
			});
		}

		$('#id_addabsen').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			var form = $('#id_formcontent');
			

			if (form.valid()) {
				var tglabsen = $("#tglabsen").val();
				var masuk = $("#masuk").val();
				var keluar = $("#keluar").val();
				var alasan = $("#alasan").val();								

				$.ajax({
					type: "POST",
					url: SITE_URL + '/absensi/simpanabsensi',
					data: {
						nik: nik,
						tglabsen: moment(tglabsen).format("DD/MM/YYYY"),
						masuk: masuk,
						keluar: keluar,
						alasan: alasan,
						atasanid: atasanid
					},
					success: function (res) {
						location.reload();
					}
				});
			};
		});

		let tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('YYYY-MM-DD');
		let tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('YYYY-MM-DD');
		let start = 0;
		$.fn.load_grid_content(nik, tglmulai, tglselesai, start);

		$('#id_search').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();

			let tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('YYYY-MM-DD');
			let tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('YYYY-MM-DD');

			$.fn.load_grid_content(nik, tglmulai, tglselesai, start);
		});
	});
</script>
<style>
	.div_filter {
		float: left;
	}

	.div_filter .column1 {
		padding: 5px 3px 5px 3px;
		float: left;
	}

	.div_verifikasinotes {
		padding-top: 5px;
		padding-bottom: 5px;
	}

	#gridcontent .activated {
		background: rgba(38, 185, 154, .16);
	}
</style>