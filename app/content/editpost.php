<?php

// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

// get post data
try {
    $result = $model->getPost($_GET['id']);
    if(!$result) {
        redirect('?page=board');
    }
    $post = $result[0];
}
catch(Exception $ex) {
    error(500, 'Could not query given Post from Database', $ex);
}

// process post data
try {
    $errors = array();
    if ('post' === strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST)) {
        if(!isset($_POST['post']) || 3 > strlen($_POST['post'])) {
            $errors[] = 'Your post has to be at least 3 Characters long.';
        }
        else {

            // check if script-tags have been used
            if(1 === preg_match('$<script(.*?)>$is', $_POST['post'])) {
                $errors[] = 'Script-Tags are not allowed!';
            }
            else {
                // save edited post
                if (!$model->editPost($_GET['id'], $_POST['post'])) {
                    $errors[] = 'Your post could not be edited';
                } else {
                    // on success: redirect
                    redirect('?page=thread&id=' . $post['thread_id'] . '&edited=1');
                }
            }
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not save Post to Database', $ex);
}

?>
<div class="row">
    <div class="col-lg-12">
        <h1>Edit Post<a href="?page=thread&id=<?php echo $post['thread_id']; ?>" class="btn btn-primary pull-right">Back to Thread</a></h1>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Edit Post</strong></div>
            <div class="panel-body">
                <?php
                if(!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
                }
                ?>
                <form action="?page=editpost&id=<?php echo $_GET['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="post">Your Post:</label>
                        <textarea class="form-control" rows="5" name="post" id="post"><?php echo isset($_POST['post']) ? $_POST['post'] : $post['text']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Edit Post</button>
                </form>
            </div>
        </div>
    </div>
</div>