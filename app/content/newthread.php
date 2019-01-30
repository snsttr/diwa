<?php

// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

// process post data
try {
    $errors = array();
    if ('post' === strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST)) {
        // check if title has enough chars
        if(!isset($_POST['title']) || 3 > strlen($_POST['title'])) {
            $errors[] = 'Your title has to be at least 3 Characters long.';
        }

        // check if post has enough chars
        if(!isset($_POST['post']) || 3 > strlen($_POST['post'])) {
            $errors[] = 'Your post has to be at least 3 Characters long.';
        }

        // check if script-tags have been used
        if(1 === preg_match('$<script(.*?)>$is', $_POST['post'])) {
            $errors[] = 'Script-Tags are not allowed!';
        }

        if(empty($errors)) {
            // save thread
            $threadId = $model->createThread($_POST['title'], (isset($_POST['admins_only']) && '1' === $_POST['admins_only']));
            if (false === $threadId) {
                $errors[] = 'Your new thread could not be saved';
            } else {
                // save post
                if (!$model->createPost($threadId, $_SESSION['user_id'], $_POST['post'])) {
                    $errors[] = 'Your post could not be saved';
                } else {
                    // on success: redirect
                    redirect('?page=thread&id=' . $threadId . '&saved=1');
                }
            }
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not save new Thread to Database', $ex);
}

?>
<div class="row">
    <div class="col-lg-12">
        <h1>New Thread<a href="?page=board" class="btn btn-primary pull-right"><?php echo icon('list'); ?> Back to Board</a></h1>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>New Post</strong></div>
            <div class="panel-body">
                <?php
                if(!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
                }
                ?>
                <form action="?page=newthread" method="post">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>" id="title">
                    </div>
                    <div class="form-group">
                        <label for="post">Your Post:</label>
                        <textarea class="form-control" rows="5" name="post" id="post"><?php echo isset($_POST['post']) ? $_POST['post'] : ''; ?></textarea>
                    </div>
                    <?php if(1 === $_SESSION['user']['is_admin']) { ?>
                        <div  class="checkbox">
                            <label><input type="checkbox" name="admins_only" value="1"<?php echo (isset($_POST['admins_only']) && '1' === $_POST['admins_only'] ? ' checked="checked"' : '') ?>> This Thread is for <strong>Administrators only</strong></label>
                        </div>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary"><?php echo icon('ok'); ?> Save Post</button>
                </form>
            </div>
        </div>
    </div>
</div>