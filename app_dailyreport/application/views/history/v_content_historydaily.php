<div class="page-title">
	<div class="title_left">
		<h3>History Daily Report Activity</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<?php if ($this->session->userdata('aksesid_dailyreport') == '97') : ?>
					<div class="column1">
						<select id="id_status" class="form-control" name="statusid">
							<option value="">Pilih Status</option>
							<?php foreach ($vstatusdaily as $r) : ?>
								<option value="<?php echo $r['statusid']; ?>"><?php echo $r['status']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="column1">
					<div id="id_tglawal" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<div id="id_tglakhir" class="input-group date" style="width:140px;margin-bottom:0px;">
						<input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
					</div>
				</div>
				<div class="column1">
					<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align" style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
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
								<th class="column-title">Tanggal </th>
								<th class="column-title">Activity Job </th>
								<th class="column-title">Keterangan </th>
								<th class="column-title">Status </th>
								<th class="column-title">Nama Approval </th>
								<th class="column-title">Analisa Atasan </th>
								<th class="column-title"></th>
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
<div id="id_winconfirm" class="modal fade hapus-modal-md" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Konfirmasi</h4>
			</div>
			<div class="modal-body">
				Apakah anda akan menghapus form daily activity ini ?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button id="id_confirm_yes" type="button" class="btn btn-primary">Ya</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var date = new Date(),
			y = date.getFullYear(),
			m = date.getMonth();
		var firstDay = new Date(y, 0, 1);
		var lastDay = new Date(y, 12, 0);
		firstDay2 = moment(firstDay).format('YYYY-MM-DD');
		lastDay2 = moment(lastDay).format('YYYY-MM-DD');
		var pegawaiid = '<?php echo $vpegawaiid; ?>';

		$('#id_tglawal').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function(date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglawal').datepicker("setDate", firstDay);

		$('#id_tglakhir').datepicker({
			format: 'dd/mm/yyyy',
			todayHighlight: true,
			autoclose: true,
			toggleActive: true,
			beforeShowDay: function(date) {
				var curr_date = moment(date).format("YYYY-MM-DD");
			}
		});
		$('#id_tglakhir').datepicker("setDate", lastDay);

		$.fn.load_grid_content = function(pegawaiid, tglmulai, tglselesai, jenisformid, statusid, keyword, nstatus, start, limit) {
			$.getJSON(
				SITE_URL + '/history/getListHistoryDaily',
				'pegawaiid=' + pegawaiid + '&tglmulai=' + tglmulai + '&tglselesai=' + tglselesai + '&jenisform=' + jenisformid + '&status=' + statusid + '&nstatus=' + nstatus + '&start=' + start + '&limit=' + limit + '&keyword=' + keyword,
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.tglmulai) ? '' : record.tglmulai) + '</td>';
						html += '<td class=" " style="max-width:450px; white-space:pre-wrap; word-wrap: break-word">' + (isEmpty(record.actjob) ? '' : record.actjob) + '</td>';
						html += '<td class=" " style="max-width:450px; white-space:pre-wrap; word-wrap: break-word">' + (isEmpty(record.keterangan) ? '' : record.keterangan) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.status) ? '' : record.status) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.atasannama) ? '' : record.atasannama) + '</td>';
						html += '<td class=" " style="max-width:450px; white-space:pre-wrap; word-wrap: break-word">' + (isEmpty(record.atasannotes) ? '' : record.atasannotes) + '</td>';
						html += '<td class=" ">';

						if (record.files != null) {
							html += '<a onclick=$(this).files("' + record.files + '") class="btn btn-success btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Files"><i class="fa fa-paperclip" aria-hidden="true"></i></a>';
						}

						if (record.pegawaiid == pegawaiid && record.status == 'Pengajuan Baru') {
							html += '<a onclick=$(this).delete("' + record.pegawaiid + '","' + record.pengajuanid + '") class="btn btn-danger btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Hapus Pengajuan"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';
						}

						if (record.pegawaiid == pegawaiid && record.status == 'Disetujui Approval') {
							html += '<a onclick=$(this).detailhistory("' + record.pegawaiid + '","' + record.nourut + '") class="btn btn-success btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>';
						}
						html += '</td>';
						html += '</tr>';
					});

					$('#gridcontent > tbody').html(html);
					$("#gridcontent tbody tr").click(function() {
						var selected = $(this).hasClass("activated");
						$("#gridcontent tr").removeClass("activated");
						if (!selected) $(this).addClass("activated");
					});

					if (response.count > 0) {
						var visiblePages = Math.ceil(response.count / limit);
						$('#gridpaging').twbsPagination({
							initiateStartPageClick: false,
							totalPages: visiblePages,
							visiblePages: 10,
							startPage: 1,
							onPageClick: function(event, page) {
								var start = (page - 1) * limit;
								$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jenisformid, statusid, keyword, nstatus, start, limit);
							}
						});
					}
				}
			)
		};

		$.fn.delete = function(pegawaiid, pengajuanid) {
			var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
			var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
			var jenisformid = null;
			var statusid = null;
			let keyword = $('#id_keyword').val();
			var nstatus = $('#id_status').val();
			var start = 0;
			var limit = 25;

			$('#id_winconfirm').modal('show');
			$('#id_confirm_yes').one('click', function() {
				$.ajax({
					type: 'POST',
					url: SITE_URL + '/history/deleteDraft',
					data: 'pegawaiid=' + pegawaiid + '&pengajuanid=' + pengajuanid,
					async: false,
					cache: false,
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							$('#id_winconfirm').modal('hide');
							location.reload();
							$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jenisformid, statusid, keyword, nstatus, start, limit);
						}
					}
				});
			});
		};

		$.fn.detailhistory = function(pegawaiid, nourut, periode, pengajuanid) {
			window.location = SITE_URL + '/history/detail?pegawaiid=' + Base64.encode(pegawaiid) + '&nourut=' + Base64.encode(nourut);
		};

		$.fn.showAlasan = function(pegawaiid, nourut) {
			$('#div_alasan' + pegawaiid + nourut).collapse('toggle');
		}

		$.fn.files = function(files) {
			window.location = SITE_URL + '/history/download?filename=' + files;
		};

		var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
		var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
		var jenisformid = null;
		var statusid = $('#id_status').val();
		let keyword = $('#id_keyword').val();
		var nstatus = null;
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jenisformid, statusid, keyword, nstatus, start, limit);

		$('#id_search').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var tglmulai = moment($('#id_tglawal').datepicker('getDate')).format('DD/MM/YYYY');
			var tglselesai = moment($('#id_tglakhir').datepicker('getDate')).format('DD/MM/YYYY');
			let keyword = $('#id_keyword').val();
			var nstatus = null;
			var jenisformid = null;
			var statusid = $('#id_status').val();

			$.fn.load_grid_content(pegawaiid, tglmulai, tglselesai, jenisformid, statusid, keyword, nstatus, start, limit);
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