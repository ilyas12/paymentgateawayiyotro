<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo base_url("dashboard"); ?>">
                <div class="row logo-default" style="color: #999999;" >L12</div>
            </a>
            <div class="menu-toggler sidebar-toggler">
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN HORIZANTAL MENU -->
        <!-- DOC: Remove "hor-menu-light" class to have a horizontal menu with theme background instead of white background -->
        <!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) in the responsive menu below along with sidebar menu. So the horizontal menu has 2 seperate versions -->

        <!-- END HEADER SEARCH BOX -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>
        <div style="position:absolute;top:15px;margin-left:50%;margin-right:50%;width:400px;color:white">
        	<?php echo $current_company_data['name']; ?>
        </div>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                        <span class="username username-hide-on-mobile">Lang</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        $current_url = current_url();
                        $addon = strpos($current_url, '?') !== FALSE ? "&" : "?";
                        foreach ($languages as $language) {
                            ?>
                            <li>
                                <a href="<?php echo $current_url . $addon . "langSelection=" . $language['abbrev']?>"><?php echo $language['name']?> </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">
                    <span class="username username-hide-on-mobile">
                    <?php
                        $adminData = $this->session->userData("user_data");
                        if(isset($adminData['username'])){
                            echo $adminData["username"];
                        }else{
                            echo $adminData["code"]." - ".$adminData["full_name"];
                        } 
                    ?> 
                    </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:void(0)" id="btn_chg_pwd">
                                <i class="icon-lock"></i>Change Password </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url("AuthController/logout/admin"); ?>">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>

                <!-- END USER LOGIN DROPDOWN -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->