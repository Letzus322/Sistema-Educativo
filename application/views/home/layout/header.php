



<!-- Header Starts -->
	<header class="main-header">
	<!-- Nested Container Starts -->
	<!-- Top Bar Starts -->
		<div class="top-bar d-none d-md-block">
			<div class="container px-md-0">
			<div class="row">
				<div class="col-md-6 col-sm-12"><?php echo $cms_setting['working_hours']; ?></div>
				<div class="col-md-6 col-sm-12">
					<ul class="list-unstyled list-inline">
						<li class="list-inline-item"><a href="mailto:<?php echo $cms_setting['email']; ?>">
							<i class="far fa-envelope"></i> <?php echo $cms_setting['email']; ?>
						</a></li>
						<li class="list-inline-item"><i class="fas fa-phone-volume"></i> <?php echo $cms_setting['mobile_no']; ?></li>
					<?php if (!is_loggedin()) { ?>
						<li class="list-inline-item"><a href="<?php echo base_url('authentication') . "/index/" . $cms_setting['url_alias']; ?>"><i class="fas fa-user-lock"></i> Inicio de Sesión</a></li>
					<?php } else { ?>
						<li class="list-inline-item"><a href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-home"></i> Panel de Control</a></li>
					<?php } ?>
					</ul>
				</div>
			</div>
			</div>
		</div>
	<!-- Top Bar Ends -->
	<!-- Navbar Starts -->
		<div class="stricky" id="strickyMenu">
			<div class="container px-md-0">
			
		</div>
	</div>
	<!-- Navbar Ends -->
<!-- Nested Container Ends -->
</header>
<!-- Header Ends -->