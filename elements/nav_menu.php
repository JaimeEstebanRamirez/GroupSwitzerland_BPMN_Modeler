<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="<?php echo BASE_URL; ?>fileManager.php"><?php echo SITE_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <?php if($userLoggedIn == 1){ ?>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'])) == 'modeler.php'?'active':''; ?>">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>modeler.php">Modeler</a>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'])) == 'fileManager.php'?'active':''; ?>">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>fileManager.php">BPMN Files</a>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'])) == 'index.php'?'active':''; ?>">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link lgout" href="<?php echo BASE_URL; ?>userAccount.php?logoutSubmit=1">Logout</a>
                </li>
                <?php }else{ ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>">Login</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>