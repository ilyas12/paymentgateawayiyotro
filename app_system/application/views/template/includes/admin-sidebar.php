<!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU1 -->
            <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu hidden-sm hidden-xs" data-auto-scroll="true" data-slide-speed="200">
            
                 <?php  echo $this->access_management->generate_admin_sidemenu() ?>

                <li>
                    <a href="http://leewenyong.com/lms/sollers/" target="_blank" >
                            <span class="title">
                                        Learning 
                                    </span>
                    </a>
                </li>
                 <?php 
                    if($this->session->userdata("user_type") == "employee"){
                        echo '  <li>
                                    <a href="'.base_url('home_page').'">
                                        [[EMPLOYEE_OPRATION]]
                                    </a>
                                </li>';

                    }
                 ?>
			</ul>

           
            <!-- END SIDEBAR MENU1 -->
            <!-- BEGIN RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
            <ul class="page-sidebar-menu visible-sm visible-xs" data-slide-speed="200" data-auto-scroll="true">
                 <?php echo $this->access_management->generate_admin_sidemenu() ?>
                <li>
                    <a href="http://leewenyong.com/lms/sollers/" target="_blank" >
                            <span class="title">
                                        Learning 
                                    </span>
                    </a>
                </li>
                 <?php 
                    if($this->session->userdata("user_type") == "employee"){
                        echo '  <li>
                                    <a href="'.base_url('home_page').'">
                                        [[EMPLOYEE_OPRATION]]
                                    </a>
                                </li>';
                    }
                 ?>
            </ul>
            <!-- END RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->
