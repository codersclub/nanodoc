<?php require_once 'setup_functions.php'; ?>

<?php require_once 'header.php'; ?>

    <div class="jumbotron config">
        <h1>Welcome to NanoDoc</h1>

<?php if (file_exists('../config.php')) { ?>
    
    <?php if(empty($_GET['step'])) { ?>
        <p>Configuration file already existes. Proceed to installation</p>
        <a class="btn btn-primary btn-lg" href="install.php?step=1">Installation</a>
    <?php } else { ?>

    <?php 
        switch ($_GET['step']) {

            case '1': ?>
                <form id="install-form" action="install.php?step=2" method="post">
                    <table class="table">
                        <tr>
                            <th>Site Title <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_title"></td>
                            <td>Choose a Title for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Username <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_user"></td>
                            <td>Choose a username for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Password <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_pass"></td>
                            <td>Choose a password for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Email <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_user_email"></td>
                            <td>Your email address.</td>
                        </tr>
                    </table>
                    <span>* required fields</span>
                    <button class="btn btn-primary btn-lg" type="submit">Install Nanodoc</button>
                </form>
                <?php break; 

            case '2':
                install_nanodoc();
                break;
        } // end switch?>

    <?php } // end if empty($_GET['step']) ?>

<?php } else { // end if file_existed ?>

            <p>There is not a configuration file for your NanoDoc. You must create one. Please click on Create Config</p>
            <a class="btn btn-primary btn-lg" href="setup.php?step=1">Create Config</a>
<?php } ?>
    
        </div> <!-- close welcome div -->

    </div>

<?php require_once 'footer.php'; ?>