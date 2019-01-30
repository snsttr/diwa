<div class="row">
    <div class="col-lg-12">
        <?php
        if(isset($_POST['email']) && isset($_POST['password'])) {
            try {
                // login
                if($result = $model->userSignIn($_POST['email'], $_POST['password'], $config['system']['hashing_algorithm'])) {
                    if(false !== $result && 0 < count($result)) {
                        // delete session data
                        session_unset();

                        // save user to session
                        $_SESSION['user_id'] = $result[0]['id'];
                    }
                    redirect('?page=loggedin');
                }
                else {
                    echo '<div class="alert alert-danger">Wrong E-Mail-Address or Password</div>';
                }
            }
            catch (Exception $ex) {
                error(500, 'Exception during login', $ex);
            }
        }
        ?>
    </div>
</div>