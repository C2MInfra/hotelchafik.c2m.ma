<nav class="navbar fixed-top">
    <a href="#" class="menu-button d-none d-md-block">
        <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
            <rect x="0.48" y="0.5" width="7" height="1" />
            <rect x="0.48" y="7.5" width="7" height="1" />
            <rect x="0.48" y="15.5" width="7" height="1" />
        </svg>
        <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
            <rect x="1.56" y="0.5" width="16" height="1" />
            <rect x="1.56" y="7.5" width="16" height="1" />
            <rect x="1.56" y="15.5" width="16" height="1" />
        </svg>
    </a>

    <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
            <rect x="0.5" y="0.5" width="25" height="1" />
            <rect x="0.5" y="7.5" width="25" height="1" />
            <rect x="0.5" y="15.5" width="25" height="1" />
        </svg>
    </a>
    <!-- <button class="header-icon btn btn-empty  d-sm-inline-block" type="button" id="Pullnav" >
                <i class="iconsmind-Arrow-LeftinCircle" style="font-size: 20px"></i>
            </button> -->

    <!-- <button class="header-icon btn btn-empty  d-sm-inline-block "  type="button" id="Pushnav" >
                <i class="iconsmind-Arrow-RightinCircle" style="font-size: 20px"></i>
                
                </button> -->
    <!--  <div class="search" data-search-path="Layouts.Search03d2.html?q=">
        <input placeholder="Search...">
        <span class="search-icon">
            <i class="simple-icon-magnifier"></i>
        </span>
    </div> -->

    <a class="navbar-logo" href="javascript:void(0)">

        <span class=""><img class="logo d-none d-xs-block" src="<?php echo BASE_URL?>asset/img/logo.png" alt=""></span>
        <span class=""><img class="logo-mobile d-block d-xs-none" src="<?php echo BASE_URL?>asset/img/logo.png"
                alt=""></span>
    </a>
    <div class="ml-auto  d-flex align-items-center">
        <div class="header-icons d-inline-block align-middle">

            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-grid"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right  position-absolute" id="iconMenuDropdown">

                    <li>
                        <a href="javascript:void(0)" class="icon-menu-item expoert">
                            <i class="glyph-icon iconsmind-Data-Save d-block"></i> Exporter BD
                        </a>

                        <a href="http://www.c2m.ma/contact.php" class="icon-menu-item" target="_blanck">
                            <i class="glyph-icon iconsmind-Support d-block"></i> Aide
                        </a>

                    </li>
                </div>
            </div>
            <div class="position-relative d-inline-block ">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-bell"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown">

                    <?php
                    $reg_v = new reg_vente;
                    $vente = new vente();
                    $data_reg_v = $reg_v->alertReg();
                    $i = 0;
                    foreach($data_reg_v as $drv) :
                    if($drv->dif <= 5 && $drv->dif > -5) :
                    ?>
                    <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                        <a href="#">
                            <img src="<?php echo BASE_URL?>asset/img/profile-pic-l.jpg" alt="Notification Image"
                                class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">
                        </a>
                        <div class="pl-3 pr-2">
                            <a href="#">
                                <p class="font-weight-medium mb-1">Date échéance
                                    De <span
                                        class="badge badge-pill badge-danger mb-1"><?php echo $drv->mode_reg ?></span>
                                    Client
                                    <b style="font-size: 15px">[<?php echo $drv->nom; ?>]</b> </p>
                                <p class="text-muted mb-0 text-small"><?php echo $drv->date_validation; ?></p>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php $i++; endforeach; ?>
                    <?php
                    $reg_a = new reg_achat();
                    $achat = new achat();
                    $data_reg_a = $reg_a->alertReg();
                    $i = 0;
                    foreach($data_reg_a as $drv) :
                    if($drv->dif <= 5 && $drv->dif > -5) :
                    ?>
                    <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                        <a href="#">
                            <img src="<?php echo BASE_URL?>asset/img/profile-pic-l.jpg" alt="Notification Image"
                                class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle">
                        </a>
                        <div class="pl-3 pr-2">
                            <a href="#">
                                <p class="font-weight-medium mb-1">Date échéance
                                    De <span class="badge badge-pill badge-danger mb-1"> Fournisseur
                                    </span>[<?php echo $drv->raison_sociale; ?>] </p>
                                <p class="text-muted mb-0 text-small"><?php echo $drv->date_validation; ?></ </p>
                            </a>
                        </div>


                    </div>
                    <?php endif; ?>
                    <?php $i++; endforeach; ?>

                </div>
            </div>
            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                <i class="simple-icon-size-fullscreen"></i>
                <i class="simple-icon-size-actual"></i>
            </button>
        </div>

        <div class="position-relative d-inline-block">
            <button class="header-icon btn btn-empty" type="button" id="avatarButton" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="user-avatar d-inline-block ml-3" style="overflow:hidden; width:36px; height:36px;">
                    <img src='<?php echo BASE_URL . '/asset/img/user.png'?>'
                        style="object-fit: cover;width: 100%;height: 100%;">
                </div>
            </button>
            <div class="dropdown-menu dropdown-menu-right pt-0 mt-1 position-absolute" id="avatarDropdown"
                style="width:220px">
                <li>
                    <div class="p-2 d-flex align-items-center" style="background:#e9ebf5">
                        <div class="user-avatar mr-2" style="overflow:hidden; width:26px; height:26px;">
                            <img src='<?php echo BASE_URL . '/asset/img/user.png'?>'
                                style="object-fit: cover;width: 100%;height: 100%;">
                        </div>
                        <div>
                            <h6 class="mb-0"><?php echo auth::user()['nom'] ?></h6>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="p-2 d-flex align-items-center">
                        <a class="header-icon btn btn-empty  d-flex align-items-center p-0"
                            href="<?php echo BASE_URL."logout.php" ?>" style="font-weight: 600; color: #ea3a5d;">
                            <div class="user-avatar mr-2" style="overflow:hidden; width:26px; height:26px;">
                                <i class="simple-icon-login"></i>
                            </div>
                            <div>
                                Déconnexion
                            </div>
                        </a>
                    </div>
                </li>

                <!-- <li>
                    <pre>
                        <?php //print_r(auth::user()) ?>
                    </pre>
                </li> -->
            </div>
        </div>
    </div>
    </div>
    
</nav>