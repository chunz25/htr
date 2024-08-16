<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<title>Maintenance | <?php echo config_item('instansi_long_name'); ?></title>

	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/build/css/custom.min.css" rel="stylesheet">

	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">

	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="login">




	<img src="<?php echo config_item('url_image'); ?>image1.png" class="center">
	<!-- <marquee width="60%" direction="left" height="100px">
		This is a sample scrolling text that has scrolls texts to left.
	</marquee> -->
	<!-- <marquee behavior="scroll" direction="left" scrollamount="13">
		<h3 style="text-align: center" class="center">Website Sedang Maintenance, Info Lebih Lanjut Mohon Hubungi Bagian HRD</h3>
	</marquee> -->
	<marquee behavior="scroll" direction="left" scrollamount="13">
		<h3 style="text-align: center" class="center">Website Under Maintenance, More Info Please Contact HR </h3>
	</marquee>




</body>
<script type="text/javascript">
	$(document).ready(function() {
		$('#id_username').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				document.getElementById('id_password').focus();
			}
		});
		$('#id_password').bind('keyup', function(event) {
			if (event.keyCode == 13) {
				do_login();
			}
		});
	});

	function do_login() {
		$('#id_pesan').removeClass();
		$('#id_pesan').addClass('pesan-wait');
		$('#id_pesan').html("Sedang Verifikasi Username dan Password ...");
		// document.location.href="<?php echo config_item('url_siap'); ?>";
		// document.location.href="<?php echo config_item('url_portal'); ?>";


		$.post('<?php echo site_url("login/check_login"); ?>', $("#form_login").serialize(), function(data, status) {
			if (status == 'success') {
				var obj = jQuery.parseJSON(data);
				if (obj.success == true) {
					$('#id_pesan').addClass('pesan-success');
					$('#id_pesan').html("Login Sukses, Loading Aplikasi...");
					document.location.href = "<?php echo config_item('url_portal'); ?>";
				} else {
					$('#id_pesan').addClass('pesan-failure');
					$('#id_pesan').html("Username dan Password Tidak Sesuai.");
				}
			} else {
				$('#id_pesan').addClass('pesan-failure');
				$('#id_pesan').html("Maaf, ada kesalahan dalam pengiriman data.");
			}
		});
	}
</script>

</html>

<style>
	.center {
		margin-top: 30px;
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 50%;
	}

	.north-layout {
		min-height: 120px;
	}

	.north-layout .left-panel {
		text-align: center;
	}

	.north-layout .right-panel {
		padding-top: 40px;
		font-size: 12px;
	}

	.center-layout {
		height: 490px;
	}

	.center-layout .left-panel {}

	.center-layout .left-panel .panel-logo {
		text-align: center;
		margin-top: 10%;
	}

	.center-layout .right-panel {}

	.center-layout .right-panel .form-login {
		padding: 10px 10px 10px 10px;
		margin-top: 25%;
	}

	.pesan {
		line-height: 50px;
		display: none;
	}

	.pesan-wait {
		line-height: 50px;
	}

	.pesan-success {
		line-height: 50px;
		color: #1abb9c;
	}

	.pesan-failure {
		line-height: 50px;
		color: #c7254e;
	}

	.south-layout {
		height: 40px;
		text-align: center;
	}

	.span-footer {
		color: #0E7BBE;
		font-weight: bold;
	}
</style>