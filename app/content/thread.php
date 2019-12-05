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
        if(!isset($_POST['post']) || 3 > strlen($_POST['post'])) {
            $errors[] = 'Your post has to be at least 3 Characters long.';
        }
        else {
            // check if script-tags have been used
            if(1 === preg_match('$<script(.*?)>$is', $_POST['post'])) {
                $errors[] = 'Script-Tags are not allowed!';
            }
            else {
                // save Post
                if (!$model->createPost($_GET['id'], $_SESSION['user_id'], $_POST['post'])) {
                    $errors[] = 'Your post could not be saved';
                } else {
                    // on success: redirect
                    redirect('?page=thread&id=' . $_GET['id'] . '&saved=1');
                }
            }
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not save new Thread to Database', $ex);
}


try {
    // get thread info
    $resultThread = $model->getThread($_GET['id']);
    if(false === $resultThread || 0 === count($resultThread)) {
        include CONTENT_PATH . '/404.php';
        return;
    }
    $thread = $resultThread[0];

    // get posts
    $resultPosts = $model->getPosts($_GET['id']);
}
catch(Exception $ex) {
    error(500, 'Could not query given Thread and Posts from Database: ', $ex);
}

$adminsOnly = (1 == $thread['admins_only']);

?>

<div class="row">
    <div class="col-lg-12">
        <h1><?php echo ($adminsOnly ? icon('lock', 'This Thread is only viewable for admins') : ''); ?>Thread "<?php echo $thread['title']; ?>"<a href="?page=board" class="btn btn-primary pull-right"><?php echo icon('list'); ?> Back to Board</a></h1>
        <?php
        if(!empty($errors)) {
            echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
        }
        elseif(isset($_GET['saved']) && 1 == $_GET['saved']) {
            echo '<div class="alert alert-success">Your post has been saved.</div>';
        }
        elseif(isset($_GET['edited']) && 1 == $_GET['edited']) {
            echo '<div class="alert alert-success">Your post has been edited.</div>';
        }
        foreach ($resultPosts as $post) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a name="post-<?php echo $post['id']; ?>"></a>
                    User: <strong><a href="/?page=profile&id=<?php echo $post['user_id']; ?>"><?php echo $post['username']; ?></a></strong> <span class="pull-right"><?php echo $post['timestamp']; ?></span>
                </div>
                <div class="panel-body">
                    <p><?php echo nl2br($post['text']); ?></p>
                </div>
                <?php if($_SESSION['user_id'] == $post['user_id']) { ?>
                <div class="panel-footer">
                    <a href="?page=editpost&id=<?php echo $post['id']; ?>">Edit this Post</a>
                </div>
                <?php } ?>
            </div>
            <?php
        }
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>New Post</strong></div>
            <div class="panel-body">
                <form action="?page=thread&id=<?php echo $_GET['id']; ?>" method="post">
                    <div class="form-group">
                        <textarea class="form-control" rows="5" name="post"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Post</button>
                </form>
            </div>
        </div>
    </div>
</div>