 <script type="text/javascript">
    $(document).on('click' , '.notify-click' , function(){
        var url = '<?php echo site_url("app/setup/mark_all_read"); ?>';
        if($(this).hasClass("open")){
            var $me = $(this);
            $.ajax({
                url : url ,
                method : "GET",
                success : function(response){

                    var json = jQuery.parseJSON(response);
                    $me.html("<i class='fa fa-comment'></i>"+json.data);
                }
            });
        }
    });

    $(document).on('click' , 'a.read-notif' , function(){
        var url = '<?php echo site_url("app/setup/read_notif"); ?>';
        var href = $(this).data("href");
        var id = $(this).data("id");
        $.ajax({
            url : url ,
            data: {id: id},
            method : "POST",
            success : function(response){
                window.location.href = href;
            }
        });
    });
 </script>
<style type="text/css">
    .unread{
        background-color: lemonchiffon;
    }
</style>
 <nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-expand-toggle">
                <i class="fa fa-bars icon"></i>
            </button>
            <ol class="breadcrumb navbar-breadcrumb">
                <li class="active"><?php echo $page_name; ?></li>
            </ol>
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-th icon"></i>
            </button>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <button type="button" class="navbar-right-expand-toggle pull-right visible-xs">
                <i class="fa fa-times icon"></i>
            </button>
            <li class="dropdown danger">
                <a href="#" class="dropdown-toggle notify-click" data-toggle="dropdown" role="button" aria-expanded="false" ><i class="fa fa-comment"></i> <?php echo $notification_list->unread_count; ?></a>
                <ul class="dropdown-menu danger animated fadeInDown" style="width: 450px;">
                    <li class="title">
                        Notifications
                    </li>
                    <li>
                        <ul class="list-group notifications">
                            <?php if($notification_list->notifications) : ?>
                                <?php foreach($notification_list->notifications as $row) : ?>
                                    <a href="javascript:void(0);" data-href="<?php echo $row->url; ?>" data-id="<?php echo $row->id; ?>" class="read-notif">
                                        <li class="list-group-item  <?php echo ($row->isread == '1') ? '' : 'unread'; ?>">
                                            <?php echo $row->description; ?>
                                        </li>
                                    </a>
                                <?php endforeach; ?>
                                <a href="javascript:void(0);">
                                    <li class="list-group-item message">
                                        <a href="<?php echo site_url('app/setup/notifications')?>">View All Notifications</a>
                                    </li>
                                </a>
                            <?php else : ?>
                                <a href="javascript:void(0);">
                                    <li class="list-group-item message">
                                         No new notification
                                    </li>
                                </a>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $session_data->display_name; ?> <span class="caret"></span></a>
                <ul class="dropdown-menu animated fadeInDown">
                    <li class="profile-img">
                        <img src="<?php echo site_url("thumbs/images/user/".$session_data->image_path.'/300/300/'.$session_data->image_name); ?>" alt="<?php echo $session_data->image_name; ?>" class="profile-img" alt="Vehicle Checklist Logo">
                    </li>
                    <li>
                        <div class="profile-info">
                            <h4 class="username"><?php echo $session_data->display_name; ?></h4>
                            <p><?php echo $session_data->email_address; ?></p>
                            <div class="btn-group margin-bottom-2x" role="group">
                                <?php if(isset($session_data->expired) && $session_data->expired == false) : ?>
                                <?php if($session_data->role == "MANAGER" || $session_data->role == "MECHANIC"): ?>
                                    <a href="<?php echo site_url("app/accounts/edit/").$this->hash->encrypt($session_data->user_id); ?>" class="btn btn-default"><i class="fa fa-user"></i> Profile</a>
                                <?php elseif($session_data->role == "ADMIN") : ?>
                                    <a href="<?php echo site_url("app/setup/profile/").$this->hash->encrypt($session_data->user_id); ?>" class="btn btn-default"><i class="fa fa-user"></i> Profile</a>
                                <?php else: ?>

                                <?php endif; ?>
                                <?php endif; ?>
                                
                                <a href="<?php echo site_url("login/logout"); ?>" class="btn btn-default"><i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>