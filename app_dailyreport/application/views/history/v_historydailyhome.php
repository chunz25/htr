<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>History Daily Report Activity</title>

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/build/css/custom.css" rel="stylesheet">
	<link href="<?php echo config_item('url_css'); ?>eservices/style.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
	<link href="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">


	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/nprogress/nprogress.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
	<script src="<?php echo config_item('url_js'); ?>pagination/jquery.twbsPagination.min.js"></script>

	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.buttons.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/pnotify/dist/pnotify.nonblock.js"></script>


	<script src="<?php echo config_item('url_js'); ?>validation/jquery.validate.js"></script>
	<script src="<?php echo config_item('url_js'); ?>validation/additional-methods.js"></script>
	<script src="<?php echo config_item('url_js'); ?>validation/jquery.validate.file.js"></script>

	<script src="<?php echo config_item('url_js'); ?>upload/bootstrap-filestyle.js"></script>
	<script src="<?php echo config_item('url_js'); ?>helper.js"></script>
	<link href="<?php echo config_item('url_js'); ?>loadmask/jquery.loadmask.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo config_item('url_js'); ?>loadmask/jquery.loadmask.min.js"></script>
	<link href="<?php echo config_item('url_js'); ?>spinner/css/gspinner.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo config_item('url_js'); ?>spinner/js/g-spinner.js"></script>

	<link href="<?php echo config_item('url_js'); ?>datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo config_item('url_js'); ?>datepicker/dist/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo config_item('url_js'); ?>notifalert.js"></script>
	<script type="text/javascript">
		var BASE_URL = '<?php echo base_url(); ?>';
		var SITE_URL = '<?php echo site_url(); ?>';
	</script>
</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col leftmenu">
				<?php $this->load->view("v_content_left.php"); ?>
			</div>

			<!-- top navigation -->
			<div class="top_nav">
				<?php $this->load->view("v_content_top.php"); ?>
			</div>
			<!-- /top navigation -->

			<!-- page content -->
			<div class="right_col" role="main">
				<?php $this->load->view("history/v_historydailyhome_content.php"); ?>
			</div>
			<!-- /page content -->

			<!-- footer content -->
			<footer>
				<div class="pull-right">
					<?php echo config_item('footer'); ?>
				</div>
				<div class="clearfix"></div>
			</footer>
			<!-- /footer content -->

		</div>
	</div>

	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/skycons/skycons.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/build/js/custom.js"></script>
	<script src="<?php echo config_item('url_template'); ?>gentelella/vendors/moment/min/moment.min.js"></script>
</body>

</html>