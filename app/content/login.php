<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php
            if(isset($_POST['username']) && isset($_POST['password'])) {
                try {
                    // login
                    if(login($_POST['username'], $_POST['password'])) {
                        redirect('/?page=loggedin');
                    }
                    else {
                        echo '<div class="alert alert-danger">Wrong Username or Password</div>';
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
