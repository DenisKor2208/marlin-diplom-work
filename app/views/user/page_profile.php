<?php $this->layout('templates/template', ['title' => 'Профиль пользователя']) ?>

<?php $this->push('head') ?>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="../css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="../css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="../css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="../css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="../css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="../css/fa-regular.css">
<?php $this->end() ?>

<?php $this->insert('navbar', ['auth' => $auth]); ?>

<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> <?php echo $userData[0]['username']?>
        </h1>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xl-6 m-auto">
            <!-- profile summary -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">
                            <img src="../img/avatar/<?php echo $userData[0]['user_avatar']?>" class="rounded-circle shadow-2 img-thumbnail" alt="">
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                <?php echo $userData[0]['username']?>
                                <small class="text-muted mb-0"><?php echo $userData[0]['user_address']?></small>
                            </h5>
                            <div class="mt-4 text-center demo">
                                <a href="<?php echo $userData[0]['user_instagram_link']?>" class="fs-xl" style="color:#C13584">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="<?php echo $userData[0]['user_vk_link']?>" class="fs-xl" style="color:#4680C2">
                                    <i class="fab fa-vk"></i>
                                </a>
                                <a href="<?php echo $userData[0]['user_telegram_link']?>" class="fs-xl" style="color:#0088cc">
                                    <i class="fab fa-telegram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 text-center">
                            <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i><?php echo $userData[0]['user_phone']?></a>
                            <a href="mailto:oliver.kopyov@marlin.ru" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i><?php echo $userData[0]['email']?></a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i> <?php echo $userData[0]['user_address']?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $this->push('scripts') ?>
    <script src="../js/vendors.bundle.js"></script>
    <script src="../js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
<?php $this->end() ?>

