<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $config['app_name']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="shortcut icon" href="<?php echo config_item('url_image'); ?>old_logo.png" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo config_item('url_logo'); ?>" />
	<link rel="stylesheet" href="<?php echo $config['url_ext']; ?>css/ext-all.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>absensi/style.css" />
	<link rel="stylesheet" href="<?php echo $config['url_css']; ?>absensi/icons.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $config['url_css']; ?>font-awesome/css/font-awesome.min.css">

	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>ext-all.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_ext']; ?>helper.js"></script>

	<script type='text/javascript' src="<?php echo $config['view_absensi']; ?>absensi.js"></script>
	<script type='text/javascript' src="<?php echo $config['view_absensi']; ?>packages/packages.js"></script>

	<script type='text/javascript' src="<?php echo $config['url_js']; ?>jquery-1.9.1.js"></script>
	<script type="text/javascript" src="<?php echo $config['url_js']; ?>moment/min/moment.min.js"></script>

	<script type="text/javascript">
		Settings = Ext.decode('<?php echo json_encode($config); ?>');
		// console.log(Settings);
		var hak = '';
		var first_menu = 'absensi';

		function logoutapp() {
			window.location = "<?php echo config_item('url_logout'); ?>/logout";
		}

		function myFunction() {
			document.getElementById("myDropdown").classList.toggle("show");
		}

		window.onclick = function(event) {
			if (!event.target.matches('.dropbtn')) {
				var dropdowns = document.getElementsByClassName("dropdown-content");
				var i;
				for (i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show')) {
						openDropdown.classList.remove('show');
					}
				}
			}
		}

		packages();
		Ext.onReady(function() {
			Ext.QuickTips.init();
				var halabsensi = '<li><a href="#absensi">Absensi Pegawai</a></li>';
				var logo = '<div class="logoleft"><a href="' + Settings.BASE_URL + '"><img src="' + Settings.BASE_URL + 'setting/eci/old_logo.png' + '"></a></div>';


			Ext.create('Ext.container.Viewport', {
				layout: 'border',
				padding: '0 0 0 0',
				renderTo: Ext.getBody(),
				items: [{
						region: 'north',
						layout: 'anchor',
						border: false,
						bodyPadding: 0,
						items: [{
							xtype: 'panel',
							border: 0,
							layout: 'fit',
							height: 95,
							html: '<div class="header">' +
								'<div class="div_center">' +
								logo +
								'<div class="left">' +
								'<ul id="menu" class="menudropdown">' +
								halabsensi +
								'<li></li>' +
								'</ul>' +
								'</div>' +
								'<div class="right">' +
								'<ul class="eastmenu">' +
								'<li>' +
								'</li>' +
								'<li>' +
								'<div class="listuser">' +
								Settings.nama + ' </br><span class="cllogout" onclick="logoutapp()" style="cursor:pointer;">Logout</span>' +
								'</div>' +
								'</li>' +
								'</ul>' +
								'</div>' +
								'</div>' +
								'<div class="div_bottom">' +
								'</div>' +
								'</div>',
						}]
					},
					{
						id: 'center',
						layout: 'fit',
						region: 'center',
						bodyPadding: 0,
						padding: 0,
						border: false,
						loader: Ext.create('Ext.Component', {
							loader: {},
							border: false,
							renderTo: Ext.getBody()
						})
					},
					{
						region: 'south',
						bodyPadding: 3,
						border: false,
						minHeight: 30,
						style: 'background-color:#ededed;text-align: right;',
						html: Settings.footer
					}
				],
				listeners: {
					afterrender: function() {
						dispatch = function(token) {
							var tokens = token.split('&');
							var m = tokens[0];
							var act = tokens[1];
							var params = tokens[2];

							if (Ext.isEmpty(m)) {
								m = first_menu;
							}

							var type = '';
							if (!Ext.isEmpty(act)) {
								type = act.toLowerCase();
								var require = 'ABSENSI.modules.' + m + '.' + act;
							} else {
								type = m;
								var require = 'ABSENSI.modules.' + type + '.App';
							}

							Ext.require(require, function() {
								Ext.getCmp('center').removeAll();
								Ext.getCmp('center').add({
									xtype: type,
									layout: 'fit',
									menu: m,
									params: params,
								});
								Ext.getCmp('center').doLayout();
							});
							Ext.getCmp('center').doLayout();
						}

						Ext.History.init(function() {
							var hashTag = document.location.hash;
							var tag = hashTag.replace("#", "");
							dispatch(tag);
						});
						Ext.History.on('change', dispatch);
					}
				}
			});
		});
	</script>

</head>

<body>
</body>

</html>
<style>
	.header {
		height: 100px;
		width: 100%;
		background-color: #ededed;
	}

	.header .div_center {
		height: 60px;
	}

	.div_center .logoleft {
		width: 150px;
		float: left;
	}

	.div_center .logoleft img {
		margin-top: -6px;
		width: 65%;
	}

	.div_center .left {
		width: 60%;
		float: left;
	}

	.menudropdown {
		font-family: Arial, Verdana;
		font-size: 14px;
		margin: 0;
		padding: 0;
		list-style: none;
		float: left;
	}

	.menudropdown>li {
		display: block;
		position: relative;
		float: left;
		padding: 20px 5px 20px 5px;
	}

	.menudropdown>li .submenudropdown {
		display: none;
	}

	.menudropdown>li a {
		text-decoration: none;
		color: #5a738e;
		background: #ededed;
		margin-left: 1px;
	}

	.menudropdown>li a:hover,
	.menudropdown>li .active {
		border-bottom: 4px solid #F68A1D;
		margin-top: 5px;
		color: #5a738e;
	}

	.menudropdown li:hover .submenudropdown {
		display: block;
		position: absolute;
		padding-left: 0px;
	}

	.menudropdown>li:hover>li {
		float: none;
		font-size: 14px;
		width: 250px;
	}

	.menudropdown>li:hover a {
		color: #5a738e;
	}

	.menudropdown>li:hover>li a:hover {
		background: #0f3d8d;
	}

	.div_center .right {
		float: right;
	}

	.eastmenu {
		display: block;
		background: #ededed;
		list-style: none;
		margin: 0;
		padding-top: 7px;
		float: left;
	}

	.eastmenu li {
		float: left;
		font: 13px helvetica;
		font-weight: bold;
		margin: 3px 0;
	}

	.eastmenu li a {
		color: #5a738e;
		text-decoration: none;
		cursor: pointer;
		font-size: 10px;
	}

	.eastmenu li a:hover {
		text-decoration: none;
		cursor: pointer;
	}

	.eastmenu .listuser {
		width: 150px;
		margin-left: 5px;
		margin-right: 5px;
		text-align: right;
	}

	/*Notifikasi*/
	.eastmenu .notification {
		padding-top: 15px;
		font-size: 16px;
	}

	.dropbtn {
		background-color: transparent;
		/* Blue background */
		color: #5a738e;
		font-size: 16px;
		/* Set a font size */
		border: none;
		/* Remove borders */
		cursor: pointer;
		/* Mouse pointer on hover */
	}

	.dropbtn:hover,
	.dropbtn:focus {
		border-bottom: 4px solid #F68A1D;
		color: #5a738e;
	}

	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content {
		cursor: auto;
		display: none;
		position: fixed;
		margin-top: 5px;
		font-size: 12px;
		background-color: #ededed;
		min-width: 120px;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		padding: 11px 16px;
		margin: 0;
		padding: 0;
		z-index: 2;
	}

	.dropdown-content a {
		color: black;
		padding: 12px 16px;
		display: block;
		min-width: 140px;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
	}

	.dropdown a:hover {
		background-color: #ddd;
	}

	.show {
		display: block;
	}

	.eastmenu>li .msg-list {
		list-style: none;
		padding: 0;
		margin: 0;
		margin-top: 5px;
	}

	.badge {
		font-size: 10px;
		font-weight: normal;
		line-height: 7px;
		padding: 6px 6px;
		position: absolute;
		left: 12px;
		top: -15px;
		border-radius: 50%;
	}

	.bg-green {
		background: #F68A1D !important;
		border: 1px solid #F68A1D !important;
		color: #fff
	}

	/*End*/

	.header .div_bottom {
		height: 35px;
		background-color: #022d73;
	}

	.div_bottom .submenu {
		list-style: none;
		float: left;
		margin-left: -35px;
		margin-top: 3px;
		display: none;
	}

	.submenu li {
		min-width: 70px;
		padding: 5px 5px 7px 5px;
		float: left;
		margin-left: 3px;
		color: #fff;
		text-align: center;
	}

	.submenu>li a {
		text-decoration: none;
		color: #fff;
	}

	.submenu>li a:hover,
	.submenu>li .active {
		border-bottom: 4px solid #F68A1D;
		color: #fff;
	}
</style>