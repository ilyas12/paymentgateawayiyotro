<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>AR Renovation</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="" name="description"/>
	<meta content="" name="author"/>


	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/plugins/uniform/css/uniform.default.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') ?>" rel="stylesheet" type="text/css"/>

	<!-- BEGIN PAGE LEVEL STYLES -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/global/plugins/select2/select2.css') ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') ?>"/>
	<!-- END PAGE LEVEL STYLES -->

	<!-- BEGIN THEME STYLES -->
	<link href="<?php echo base_url('public/assets/global/css/components.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/global/css/plugins.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/admin/layout/css/layout.css') ?>" rel="stylesheet" type="text/css"/>
	<link id="style_color" href="<?php echo base_url('public/assets/admin/layout/css/themes/default.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/admin/pages/css/login-soft.css') ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('public/assets/admin/layout/css/custom.css') ?>" rel="stylesheet" type="text/css"/>

	<!-- END THEME STYLES -->
	<link rel="shortcut icon" href="favicon.ico"/>
	<script src="<?php echo base_url('public/assets/global/plugins/jquery-1.11.0.min.js') ?>" type="text/javascript"></script>
</head>

<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="index.html">
	<img src="<?php echo base_url("public/assets/admin/layout/img/logo-big.png");?>" alt=""/>
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" method="post">
		<h3 class="form-title">Login to your account</h3>
		<?php if(isset($msg)){?>
		<div class="alert alert-danger">
			<button class="close" data-close="alert"></button>
			<?php  echo $msg; ?>
		</div>
		<?php } ?>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="user_name"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"/>
			</div>
		</div>
		<div class="form-actions">
			<input type="submit" name="btnSubmit" value="Login" class="btn blue pull-right"> 
			<!--<button type="submit" class="btn blue pull-right">
			Login <i class="m-icon-swapright m-icon-white"></i>
			</button>-->
		</div>
	</form>
	<!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 2014 &copy; AR ZAP Studio.
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo base_url('public/assets/global/plugins/jquery-migrate-1.2.1.min.js') ?>" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo base_url('public/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery.blockui.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/jquery.cokie.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/uniform/jquery.uniform.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>" type="text/javascript"></script>

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/backstretch/jquery.backstretch.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/assets/global/plugins/select2/select2.min.js') ?>"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url('public/assets/global/scripts/metronic.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/admin/layout/scripts/layout.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('public/assets/admin/pages/scripts/login-soft.js') ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {
    
Layout.init(); // init current layout
  Login.init();
       // init background slide images
       $.backstretch([
        "<?php echo base_url('public/assets/admin/pages/media/bg/1.jpg') ?>",
        "<?php echo base_url('public/assets/admin/pages/media/bg/2.jpg') ?>",
        "<?php echo base_url('public/assets/admin/pages/media/bg/3.jpg') ?>",
        "<?php echo base_url('public/assets/admin/pages/media/bg/4.jpg') ?>"
        ], {
          fade: 1000,
          duration: 8000
    }
    );
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>