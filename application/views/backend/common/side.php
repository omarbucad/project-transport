
<script type="text/javascript">
    $(document).on("click","#driver-menu-header",function(){
        $("#driver-menu").slideToggle();
        $("#driver-menu").removeClass("collapse");

        if($("#mechanic-menu").is(':visible')){
            $("#mechanic-menu").slideToggle();
        } 

    });

    $(document).on("click","#mechanic-menu-header",function(){
        $("#mechanic-menu").slideToggle();

        if($("#driver-menu").is(':visible')){
            $("#driver-menu").slideToggle();
        }
    });

    $(document).on("click","#reports-menu-header",function(){
        if(!$("#dropdown-element-report").hasClass('in')){
            if($("#driver-menu").is(':visible')){
                $("#driver-menu").attr("style","display:none");
            }
            if($("#mechanic-menu").is(':visible')){
                $("#meechanic-menu").attr("style","display:none");
            } 
        }        
    });

    $(document).on("mouseleave",".side-menu",function(){
        if($("#dropdown-element-report").hasClass('in')){
          $("#dropdown-element-report").removeClass('in');
          $("#dropdown-element-report").attr('aria-expanded',"false");
          $("#dropdown-element-report").attr("style","");
        }

        if($("#driver-menu").is(':visible')){
            $("#driver-menu").attr("style","display:none");
        }
        if($("#mechanic-menu").is(':visible')){
            $("#meechanic-menu").attr("style","display:none");
        } 
    });

</script>
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
                    <?php if($session_data->role != "SUPER ADMIN") :?>
                    <a href="<?php echo site_url('app/dashboard'); ?>">
                        <span class="icon fa fa-home"></span><span class="title">Home</span>
                    </a>
                    <?php else: ?>
                    <a href="<?php echo site_url('admin/dashboard'); ?>">
                        <span class="icon fa fa-home"></span><span class="title">Home</span>
                    </a>
                    <?php endif; ?>
                </li>
                <?php if($session_data->role == "SUPER ADMIN") : ?>
                <li class="<?php echo ($this->uri->segment(2) == 'accounts') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('admin/accounts'); ?>">
                        <span class="icon fa fa-users"></span><span class="title">Accounts</span>
                    </a>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'invoice') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('admin/invoice'); ?>">
                        <span class="icon fa fa-clipboard"></span><span class="title">Invoice</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if($session_data->role != "SUPER ADMIN") : ?>
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
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == 'report') ? "active" : "" ;?>">
                    <a data-toggle="collapse" href="#dropdown-element-report" id="reports-menu-header">
                        <span class="icon fa fa-book"></span><span class="title">Reports</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-element-report" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <li class="panel panel-default dropdown">
                                    <a href="#driver-menu" id="driver-menu-header">Driver</a>
                                    <div class="panel-collapse collapse" id="driver-menu">
                                        <ul class="nav navbar-nav " >
                                            <li><a href="<?php echo site_url("app/report/daily"); ?>">Daily</a></li>
                                            <li><a href="<?php echo site_url("app/report/weekly"); ?>">Weekly</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="panel panel-default dropdown">
                                    <a href="#mechanic-menu" id="mechanic-menu-header">Mechanic</a>
                                    <div class="panel-collapse collapse" id="mechanic-menu">
                                        <ul class="nav navbar-nav " >
                                            <li><a href="<?php echo site_url("app/report/mechanic/checklist"); ?>">Checklist</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="<?php echo ($this->uri->segment(2) == 'accounts') ? "active" : "" ;?>">
                    <a href="<?php echo site_url('app/accounts'); ?>">
                        <span class="icon fa fa-users"></span><span class="title">Accounts</span>
                    </a>
                </li>
                <li class="panel panel-default dropdown <?php echo ($this->uri->segment(2) == 'setup') ? "active" : "" ;?>">
                    <a data-toggle="collapse" href="#dropdown-element-setup">
                        <span class="icon fa fa-cog"></span><span class="title">Setup</span>
                    </a>
                    <!-- Dropdown level 1 -->
                    <div id="dropdown-element-setup" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul class="nav navbar-nav">
                                <?php if($this->session->userdata('user')->role == 'ADMIN') : ?>
                                    <li><a href="<?php echo site_url("app/setup/profile"); ?>">Profile</a></li>
                                    <li><a href="<?php echo site_url("app/setup/account/manage"); ?>">Account</a></li>
                                <?php endif;?>
                                
                                <li><a href="<?php echo site_url("app/setup/checklist"); ?>">Checklist</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="https://play.google.com/store" target="_blank">
                        <span class="icon fa fa-download"></span><span class="title">Download</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>