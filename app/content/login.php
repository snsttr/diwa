<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php
            if(isset($_POST['login']) && isset($_POST['password'])) {
                try {
                    // login
                    if(login($_POST['login'], $_POST['password'])) {
                        redirect('?page=loggedin');
                    }
                    else {
                        echo '<div class="alert alert-danger">Wrong E-Mail-Address or Password</div>';
                    }
                }
                catch (Exception $ex) {
                    error(500, 'Exception during login: ' . $ex->getMessage());
                }
            }
            ?>
        </div>
    </div>
</div>
