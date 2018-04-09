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
                <li class="<?php echo ($this->uri->segment(2) == 'dashboard' OR $this->uri->segment(2) == '') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('app/dashboard'); ?>">
                        <span class="icon fa fa-home"></span><span class="title">Home</span>
                    </a>
                </li>
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == 'vehicle') ? "active" : "" ;?>">
                    <a data-toggle="collapse" href="#dropdown-element-vechile">
                        <span class="icon fa fa-truck"></span><span class="title">Vehicle</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-element-vechile" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo site_url("app/vehicle/truck"); ?>">Vehicle</a></li>
                                <li><a href="<?php echo site_url("app/vehicle/trailer"); ?>">Trailer</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'reports') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('app/reports'); ?>">
                        <span class="icon fa fa-book"></span><span class="title">Reports</span>
                    </a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'accounts') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('app/accounts'); ?>">
                        <span class="icon fa fa-users"></span><span class="title">Accounts</span>
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
                                <li><a href="<?php echo site_url("app/setup/profile"); ?>">Profile</a></li>
                                <li><a href="<?php echo site_url("app/setup/settings"); ?>">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>