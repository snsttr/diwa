<div class="row">
    <div class="col-lg-12">
        <?php
        // delete session data
        unset($_SESSION);
        $_SESSION = array();
        session_destroy();

        // redirect
        redirect('./');
        ?>
    </div>
</div>