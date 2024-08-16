<div class="page-title">
	<div class="title_left">
		<h3>Approval Daily Report Activity</h3>
	</div>
</div>
<div class="clearfix"></div>
<div class="row center-cont">
	<div class="row" style="padding-left:5px;padding-right:5px;">
		<div class="pull-left">
			<div class="div_filter">
				<?php if ($this->session->userdata('aksesid_dailyreport') == '97') : ?>
					<div class="column1">
						<input id="id_keyword" type="text" class="form-control" style="width:200px;" placeholder="NIK atau Nama">
					</div>
					<div class="column1">
						<button id="id_search" type="button" class="btn btn-success" aria-label="Left Align" style="margin-top:0px;margin-bottom:0px;"><i class="fa fa-search"></i></button>
					</div>
				<?php endif; ?>
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
								<th class="column-title">Divisi </th>
								<th class="column-title">Jabatan </th>
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
				Apakah anda akan menghapus Daily Activity ini ?
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
		var firstDay = new Date(y, m / m - 1, 1);
		var lastDay = new Date(y, m / m + 11, 0);
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

		$.fn.load_grid_content = function(pegawaiid, keyword, start, limit) {
			$.getJSON(
				SITE_URL + '/listapprove/getListPegawai', 'pegawaiid=' + pegawaiid + '&keyword=' + keyword + '&start=' + start + '&limit=' + limit,
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						html += '<tr class="even pointer">';
						html += '<td class=" ">' + (index + 1) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nik) ? '' : record.nik) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.nama) ? '' : record.nama) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.divisi) ? '' : record.divisi) + '</td>';
						html += '<td class=" ">' + (isEmpty(record.jabatan) ? '' : record.jabatan) + '</td>';
						html += '<td class=" ">';

						if (record.pegawaiid == pegawaiid && record.statusid == '1') {
							html += '<a onclick=$(this).detail("' + record.pegawaiid + '","' + record.nourut + '","' + record.periode + '","' + record.pengajuanid + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Ubah"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						} else {
							html += '<a onclick=$(this).detailapprove("' + record.pegawaiid + '") class="btn btn-default btn-xs" aria-label="Left Align" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></a>';
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
								$.fn.load_grid_content(pegawaiid, keyword, start, limit);
							}
						});
					}
				}
			)
		};

		$.fn.showAlasan = function(pegawaiid, nourut) {
			$('#div_alasan' + pegawaiid + nourut).collapse('toggle');
		}

		$.fn.detailapprove = function(pegawaiid) {
			window.location = SITE_URL + '/listapprove/detaillistdaily?pegawaiid=' + Base64.encode(pegawaiid);
		};

		var keyword = $('#id_keyword').val();
		var start = 0;
		var limit = 25;

		$.fn.load_grid_content(pegawaiid, keyword, start, limit);

		$('#id_search').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var keyword = $('#id_keyword').val();

			$.fn.load_grid_content(pegawaiid, keyword, start, limit);
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