<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="<?php echo getRootUrl();?>Addons/Checkin/View/Css/check.css">

<div class="<?php if($check['ischeck']==1){ ?>checked<?php } ?> box1 " id="checkdiv">
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">
            <i class="ico_calendar"></i>

        </div>

<div class="checkin_center">
        <?php if(($check["ischeck"]) == "1"): ?><div class="col-xs-4 col-md-4 col-lg-4 hidden-sm text-center" >
                <div>连签</div>
                <div><?php echo ($connum); ?></div>
            </div>

            <?php else: ?>
            <div class="col-xs-4  text-center" >
                <div><?php echo ($check["day"]); ?></div>
                <div><?php echo ($check["week"]); ?></div>

            </div><?php endif; ?>
    </div>

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php if($check['ischeck']==1){ ?>

            <span id="checkin" class="btn-sign">查看</span>

            <?php }else{ ?>

            <a href="javascript:void(0)" id="checkin" onclick="checkin()" class="btn-sign">签到</a>

            <?php } ?>
        </div>

    </div>
</div>
<div class="row" style="margin-right: 15px">
    <div class="col-xs-12 col-md-12">
        <div class="sign-wrap" style="display:none" id="checkdetail">

            <i class="arrow-y"></i>

            <div class="sign-box" style="text-align: center">

                <h3 id="checkinfo" style="font-size: 20px">
                    <?php if($check['ischeck']){ ?>
                    签到成功
                    <?php }else{ ?>
                    未签到
                    <?php } ?>
                </h3>

                <p>
                    连签<span id="con_num"><?php echo ($connum); ?></span>天，累签<span id="total_num"><?php echo ($totalnum); ?></span>天，超<?php echo ($over_rate); ?>用户
                </p>

            </div>
        </div>
    </div>
</div>


<script>
    var isshow = 1;
    $(function () {
        var ischeck = "<?php echo ($check['ischeck']); ?>";
        if (ischeck == "1") {
            $('#checkdetail').hover(function () {
                isshow = 2;
            }, function () {
                setTimeout(function () {
                    if (isshow == 1) {
                        $('#checkdetail').hide();
                    }
                    isshow = 1;
                }, 100);
            });

            $('#checkin').hover(function () {
                $('#checkdetail').show();
            }, function () {
                setTimeout(function () {
                    if (isshow == 1) {
                        $('#checkdetail').hide();
                    }
                    isshow = 1;
                }, 100);
            });
        }
    });

    function checkin() {
        $('#checkin').text('查看');
        $('#checkin').attr('onclick', '');
        $('#checkinfo').text('签到成功');

        $.post("<?php echo addons_url('Checkin://Checkin/check_in');?>", {}, function (res) {

                    if (res.status) {
                        toast.success('签到成功');
                        var connum = res.info.con_num;
                        var totalnum = res.info.total_num;
                        $('.checkin').html(res.info.html);
                        $('#con_num').text(connum);
                        $('#con_num_day').text(connum);
                        $('#total_num').text(totalnum);
                        $('.checkin_center div').html(' <div>连签</div><div>'+connum+'</div>')
                        $('#checkdetail').hover(function () {
                            isshow = 2;
                        }, function () {
                            setTimeout(function () {
                                if (isshow == 1) {
                                    $('#checkdetail').hide();
                                }
                                isshow = 1;
                            }, 100);
                        });
                        $('#checkin').hover(function () {
                            $('#checkdetail').show();
                        }, function () {
                            setTimeout(function () {
                                if (isshow == 1) {
                                    $('#checkdetail').hide();
                                }
                                isshow = 1;
                            }, 100);
                        });
                    }
                    //location.reload();
                }
        )
        ;

    }
</script>