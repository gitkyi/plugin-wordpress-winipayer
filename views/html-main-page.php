<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel='stylesheet prefetch'
    href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>

<style>
    .nav>li>a:focus,
    .nav>li>a:hover {
        background: transparent !important;
    }

    .design-process-section .text-align-center {
        line-height: 25px;
        margin-bottom: 12px;
    }

    .design-process-content {
        border: 1px solid #e9e9e9;
        position: relative;
        padding: 1px 0 0px 18px;
    }

    .design-process-content img {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        max-height: 100%;
    }

    a:active,
    a:hover,
    a:focus {
        box-shadow: 0 0 0 0px #fff !important
    }

    .design-process-content h3 {
        margin-bottom: 16px;
    }

    .design-process-content p {
        line-height: 26px;
        margin-bottom: 12px;
    }

    .process-model {
        list-style: none;
        padding: 0;
        position: relative;
        max-width: 600px;
        margin: 20px auto 26px;
        border: none;
        z-index: 0;
    }

    .process-model li::after {
        background: #e5e5e5 none repeat scroll 0 0;
        bottom: 0;
        content: "";
        display: block;
        height: 4px;
        margin: 0 auto;
        position: absolute;
        right: -30px;
        top: 33px;
        width: 85%;
        z-index: -1;
    }

    .process-model li.visited::after {
        background: #337ab7;
    }

    /* #337ab7 */
    .process-model li:last-child::after {
        width: 0;
    }

    .process-model li {
        display: inline-block;
        width: 18%;
        text-align: center;
        float: none;
    }

    .nav-tabs.process-model>li.active>a,
    .nav-tabs.process-model>li.active>a:hover,
    .nav-tabs.process-model>li.active>a:focus,
    .process-model li a:hover,
    .process-model li a:focus {
        border: none;
        background: transparent;

    }

    .process-model li a {
        padding: 0;
        border: none;
        color: #606060;
    }

    .process-model li.active,
    .process-model li.visited {
        color: #337ab7;
    }

    .process-model li.active a,
    .process-model li.active a:hover,
    .process-model li.active a:focus,
    .process-model li.visited a,
    .process-model li.visited a:hover,
    .process-model li.visited a:focus {
        color: #337ab7;
    }

    .process-model li.active p,
    .process-model li.visited p {
        font-weight: 600;
        color: #337ab7;
    }

    .process-model li i {
        display: block;
        height: 68px;
        width: 68px;
        text-align: center;
        margin: 0 auto;
        background: #f5f6f7;
        border: 2px solid #e5e5e5;
        line-height: 65px;
        font-size: 30px;
        border-radius: 50%;
    }

    .process-model li.active i,
    .process-model li.visited i {
        background: #fff;
        border-color: #337ab7;
    }

    .process-model li p {
        font-size: 14px;
        margin-top: 11px;
    }

    .process-model.contact-us-tab li.visited a,
    .process-model.contact-us-tab li.visited p {
        color: #606060 !important;
        font-weight: normal
    }

    .process-model.contact-us-tab li::after {
        display: none;
    }

    .process-model.contact-us-tab li.visited i {
        border-color: #e5e5e5;
    }



    @media screen and (max-width: 560px) {
        .more-icon-preocess.process-model li span {
            font-size: 23px;
            height: 50px;
            line-height: 46px;
            width: 50px;
        }

        .more-icon-preocess.process-model li::after {
            top: 24px;
        }
    }

    @media screen and (max-width: 380px) {
        .process-model.more-icon-preocess li {
            width: 16%;
        }

        .more-icon-preocess.process-model li span {
            font-size: 16px;
            height: 35px;
            line-height: 32px;
            width: 35px;
        }

        .more-icon-preocess.process-model li p {
            font-size: 8px;
        }

        .more-icon-preocess.process-model li::after {
            top: 18px;
        }

        .process-model.more-icon-preocess {
            text-align: center;
        }
    }
</style>
<script>
    // script for tab steps
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var href = $(e.target).attr('href');
        var $curr = $(".process-model  a[href='" + href + "']").parent();

        $('.process-model li').removeClass();

        $curr.addClass("active");
        $curr.prevAll().addClass("visited");
    });
// end  script for tab steps
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <!-- design process steps-->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs process-model more-icon-preocess" role="tablist">
                <li role="presentation" class="active" style="margin-left:25%"><a href="#home" aria-controls="home"
                        role="tab" data-toggle="tab"><i class="fa fa-home" aria-hidden="true"></i>
                        <p>Acceuil</p>
                    </a></li>
            </ul>
            <!-- end design process steps-->
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                    <div class="design-process-content">
                        <div class="row">
                            <div class="col-md-3 offset-1">
                                <div class="card text-white bg-primary mb-3">
                                    <a
                                        href="<?php echo admin_url('admin.php?page=wc-settings&tab=checkout&section=9668d260-3f24-4603-b254-7e7a65aab0e4') ?>">
                                        <div class="card-body" style="padding:0px !important">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <h3 class="text-white">Paramêtre</h3>
                                                </div>
                                                <div class="col-md-3">
                                                    <img src="../wp-content/plugins/winipayer/assets/images/reglage.png"
                                                        alt=""
                                                        srcset="../wp-content/plugins/winipayer/assets/images/reglage.png">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <a href="http://dddd">
                                        <div class="card-body" style="padding:0px !important">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <h3 class="text-white">Documentation</h3>
                                                </div>
                                                <div class="col-md-3">
                                                    <img src="../wp-content/plugins/winipayer/assets/images/documentation.png"
                                                        alt=""
                                                        srcset="../wp-content/plugins/winipayer/assets/images/documentation.png">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <a href="http://dddd">
                                        <div class="card-body" style="padding:0px !important">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <h3 class="text-white">Support Technique</h3>
                                                </div>
                                                <div class="col-md-3">
                                                    <img src="../wp-content/plugins/winipayer/assets/images/question.png"
                                                        alt=""
                                                        srcset="../wp-content/plugins/winipayer/assets/images/question.png">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>