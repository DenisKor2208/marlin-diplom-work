<?php $this->layout('templates/template', ['title' => 'Document']) ?>

<?php $this->push('head') ?>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="../css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="../css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="../css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="../css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="../css/fa-brands.css">
<?php $this->end() ?>

<?php $this->insert('navbar', ['auth' => $auth]); ?>

<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
        </h1>
    </div>

    <?php echo flash()->display(); ?>

    <form action="/page-edit" method="post">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Общая информация</h2>
                        </div>
                        <div class="panel-content">
                            <!-- username -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Имя</label>
                                <input type="hidden" id="simpleinput" class="form-control" name="id" value="<?php echo $userData[0]['id']?>">
                                <input type="text" id="simpleinput" class="form-control" name="username" value="<?php echo $userData[0]['username']?>">
                            </div>

                            <!-- title -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Место работы</label>
                                <input type="text" id="simpleinput" class="form-control" name="user_work" value="<?php echo $userData[0]['user_work']?>">
                            </div>

                            <!-- tel -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Номер телефона</label>
                                <input type="text" id="simpleinput" class="form-control" name="user_phone" value="<?php echo $userData[0]['user_phone']?>">
                            </div>

                            <!-- address -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Адрес</label>
                                <input type="text" id="simpleinput" class="form-control" name="user_address" value="<?php echo $userData[0]['user_address']?>">
                            </div>

                            <!-- role -->
                            <?php if ($auth->hasRole(\Delight\Auth\Role::ADMIN)): ?>
                                <div class="form-group">
                                    <label class="form-label" for="example-select">Выберите роль</label>
                                    <select class="form-control" id="example-select" name="user_role">
                                        <? foreach($roles as $key_status => $description_status):?>
                                            <? if($key_status == $userData[0]['roles_mask']){
                                                echo "<option value=". $key_status ." selected>" . $description_status . "</option>";
                                            } else {
                                                echo "<option value=". $key_status .">" . $description_status . "</option>";
                                            }
                                        endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning">Редактировать</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<?php $this->push('scripts') ?>
    <script src="../js/vendors.bundle.js"></script>
    <script src="../js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
            {
                if (this.value == 'grid')
                {
                    $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                    $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                    $('#js-contacts .js-expand-btn').addClass('d-none');
                    $('#js-contacts .card-body + .card-body').addClass('show');

                }
                else if (this.value == 'table')
                {
                    $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                    $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                    $('#js-contacts .js-expand-btn').removeClass('d-none');
                    $('#js-contacts .card-body + .card-body').removeClass('show');
                }

            });

            //initialize filter
            initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
<?php $this->end() ?>

