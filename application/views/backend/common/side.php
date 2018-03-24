<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <div class="icon fa fa-truck"></div>
                    <div class="title"><?php echo $application_name; ?></div>
                </a>
                <button type="button" class="navbar-expand-toggle pull-right visible-xs">
                    <i class="fa fa-times icon"></i>
                </button>
            </div>
            <ul class="nav navbar-nav">
                <li class="<?php echo ($this->uri->segment(2) == 'welcome' OR $this->uri->segment(2) == '') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('app/welcome'); ?>">
                        <span class="icon fa fa-home"></span><span class="title">Home</span>
                    </a>
                </li>
            
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == 'setup') ? "active" : "" ;?>">
                    <a data-toggle="collapse" href="#dropdown-element-setup">
                        <span class="icon fa fa-slack"></span><span class="title">Setup</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-element-setup" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo site_url("app/setup/general"); ?>">General</a></li>
                                <li><a href="<?php echo site_url("app/setup/account/manage"); ?>">Account</a></li>
                                <li><a href="<?php echo site_url("app/setup/outlets-and-registers"); ?>">Outlets And Registers</a></li>
                                <li><a href="<?php echo site_url("app/setup/sales-taxes"); ?>">Sales Taxes</a></li>
                                <li><a href="<?php echo site_url("app/setup/users"); ?>">Users</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>