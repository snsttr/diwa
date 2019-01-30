<?php
// process post data
$errors = array();
if ('post' === strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST)) {
    // check if name has enough chars
    if (!isset($_POST['name']) || 3 > strlen($_POST['name'])) {
        $errors[] = 'Your name has to be at least 3 Characters long.';
    }

    // check if email address is valid
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid E-Mail-Adress.';
    }

    // check if at least 1 reciepient was selected
    if(!isset($_POST['recipients']) || 0 === count($_POST['recipients'])) {
        $errors[] = 'Please select at least one recipient.';
    }

    // check if message has enough chars
    if (!isset($_POST['message']) || 3 > strlen($_POST['message'])) {
        $errors[] = 'Your message has to be at least 3 Characters long.';
    }

    if(empty($errors)) {
        $useMailFunction = (isset($config['site']['use_mail_function']) && $config['site']['use_mail_function']);
        $sent = 0;
        // send Mails
        foreach ($_POST['recipients'] as $recipient) {
            // save mails to mail.log
            if(@file_put_contents(
                ROOT_PATH . '/logs/mail.log',
                '[=== ' . date('Y-m-d H:i:s') . ' ===]' . PHP_EOL . 'TO: ' . trim($recipient) . PHP_EOL . 'FROM: ' . $_POST['name'] . ' <' . $_POST['email'] . '>' . PHP_EOL . 'MESSAGE:' . PHP_EOL . $_POST['message'] . PHP_EOL . PHP_EOL,
                FILE_APPEND)) {
                $sent++;
            }
            // send mails if configured
            if($useMailFunction) {
                // send mail with mail-function
                if (@mail(trim($recipient), 'New Message from DIWA', $_POST['message'], 'From: ' . $_POST['name'] . ' <' . $_POST['email'] . '>')) {
                    $sent++;
                }
            }
        }
        if($useMailFunction && $sent === 0) {
            $errors[] = 'Your message could not be send to any of your recipients';
        }
        elseif($useMailFunction && $sent !== count($_POST['recipients'])) {
            // redirect with message
            redirect('?page=messagesent&message=' . urlencode('Your message could only be sent to ' . $sent . ' of the ' . count($_POST['recipients']) . ' recipients'));
        }
        else {
            // redirect
            redirect('?page=messagesent');
        }
    }
}

// get all admin Usernames & E-Mail-Adresses
try {
    $resultAdmins = $model->getAllAdmins();
    if(false === $resultAdmins || 0 === count($resultAdmins)) {
        error(500, 'Could not determine all admin accounts');
    }
}
catch(Exception $ex) {
    error(500, 'Could not query admins from Database', $ex);
}
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Contact an Admin</h1>
        <?php
        if(!empty($errors)) {
            echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
        }
        ?>
        <form action="?page=contact" method="post">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo (isset($_POST['name']) ? $_POST['name'] : ''); ?>" id="name">
            </div>
            <div class="form-group">
                <label for="email">Your E-Mail-Adress:</label>
                <input type="email" class="form-control" name="email" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>" id="email">
            </div>
            <div class="form-group">
                <p><strong>Recipients:</strong></p>
                <div class="btn-group">
                    <button type="button" class="btn btn-default select-all-admins">Select all</button>
                    <button type="button" class="btn btn-default unselect-all-admins">Unselect all</button>
                </div>
                <?php
                foreach ($resultAdmins as $admin) {
                    ?>
                    <div  class="checkbox">
                        <label><input type="checkbox" name="recipients[]" value="<?php echo $admin['email']; ?>" class="select-admin"<?php echo (isset($_POST['recipients']) && in_array($admin['email'], $_POST['recipients']) ? ' checked="checked"' : '') ?>> <?php echo $admin['username']; ?></label>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea class="form-control" rows="5" name="message" id="message"><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo icon('envelope'); ?> Send Message</button>
        </form>
    </div>
</div>