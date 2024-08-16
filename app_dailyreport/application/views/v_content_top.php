<div class="nav_menu">
	<nav>
		<div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li class="">
				<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<?php echo $this->session->userdata('nik'); ?> | <?php echo $this->session->userdata('nama'); ?>
					<span class=" fa fa-angle-down"></span>
				</a>
				<ul class="dropdown-menu dropdown-usermenu pull-right">
					<li><a href="<?php echo site_url(''); ?>/pengajuan/changePassword"><i class="fa fa-pencil pull-right"></i> Ubah Password</a></li>
					<li><a href="<?php echo config_item('url_logout'); ?>/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
				</ul>
			</li>

			<li role="presentation" class="dropdown">
				<a id="id_btnnotif" href="javascript;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-envelope-o"></i>
					<span id="id_count_readnotif" class="badge bg-green">0</span>
				</a>
				<ul id="id_panelnotifikasi" class="dropdown-menu list-unstyled msg_list" role="menu"></ul>
			</li>

			<li class="">
					<a href="<?php echo config_item('base_url') . 'absensi.php'; ?>">
						<span class="badge bg-blue">View Absensi</span>
					</a>
				</li>

			<?php if (	($this->session->userdata('userid') == '1') || 
							($this->session->userdata('userid') == '5') || 
							($this->session->userdata('userid') == '1694') || 
							($this->session->userdata('userid') == '37') ) {
			?>
				<li class="">
					<a href="<?php echo config_item('base_url') . 'siap.php'; ?>">
						<span class="badge bg-green">View Report</span>
					</a>
				</li>
			<?php } ?>

		</ul>
	</nav>
</div>
<script type="text/javascript">
	$(function() {
		$.fn.loadShortNotif = function() {
			var link = '/notifikasi/getShortNotification';
			$.getJSON(
				SITE_URL + link,
				'',
				function(response) {
					var html = '';
					$.each(response.data, function(index, record) {
						html += '<li>';
						if (record.jenisnotif == 'Pengajuan Form Daily Activity') {
							html += '<a onclick=$(this).detailApp();>';
						} else {
							html += '<a onclick=$(this).detailHist();>';
						}
						html += '<span>' + record.nama + '</span>';
						html += '<span class="message">' + record.jenisnotif + '</span>';
						html += '<span class="time_notif">' + record.tglnotif + '</span>';
						html += '</a>';
						html += '</li>';
					});

					html += '<li>';
					html += '<div class="text-center">';
					html += '<a onclick=$(this).lihatsemua();><strong>Lihat Semua</strong><i class="fa fa-angle-right"></i></a>';
					html += '</div>';
					html += '</li>';

					$('#id_panelnotifikasi').html(html);

					if (response.count > 0) {
						$('#id_count_readnotif').text(response.count);
					} else {
						$('#id_count_readnotif').hide();
						$('#id_count_readnotif').text(response.count);
					}
				}
			);
		};
		$.fn.loadShortNotif();
		$.fn.detailApp = function() {
			window.location = SITE_URL + '/listapprove/';
		};
		$.fn.detailHist = function() {
			window.location = SITE_URL + '/history/';
		};
		$.fn.lihatsemua = function() {
			window.location = SITE_URL + '/notifikasi/';
		};
		$('#id_btnnotif').on('click', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: SITE_URL + '/notifikasi/updateReadNotif',
				data: '',
				cache: false,
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						$.fn.loadShortNotif();
					}
				}
			});

		});
	});
</script>