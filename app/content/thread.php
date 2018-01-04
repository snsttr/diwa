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
        if(!isset($_POST['post']) || 10 > strlen($_POST['post'])) {
            $errors[] = 'Your post has to be at least 10 Characters long.';
        }
        else {
            $sql = 'INSERT INTO ' . $config['database']['prefix'] . 'posts (thread_id, user_id, text) VALUES (' . $_GET['id'] . ', ' . $_SESSION['user_id'] . ', \'' . $_POST['post'] . '\')';
            $resultSave = $db->exec($sql);
            if (!$resultSave) {
                $errors[] = 'Your post could not be saved';
            } else {
                // on success: redirect
                redirect('?page=thread&id=' . $_GET['id'] . '&saved=1');
            }
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not save new Thread to Database: ' . $ex->getMessage());
}


try {
    // get thread info
    $sql = '
        SELECT
          t.id AS id,
          t.title AS title,
          t.admins_only AS admins_only,
          MAX(p.timestamp) AS last_post,
          COUNT(*) AS count_post
        FROM
          ' . $config['database']['prefix'] . 'threads t,
          ' . $config['database']['prefix'] . 'posts p
        WHERE
          t.id = ' . $_GET['id'] . ' AND
          p.thread_id = t.id
        GROUP BY
          t.id';

    $resultThread = $db->query($sql);
    if(!$thread = $resultThread->fetchArray()) {
        include __DIR__ . '/404.php';
        return;
    }

    // get posts
    $sql = '
        SELECT
          p.*,
          u.username
        FROM
          ' . $config['database']['prefix'] . 'posts p,
          ' . $config['database']['prefix'] . 'users u
        WHERE
            p.thread_id = ' . $_GET['id'] . ' AND
            p.user_id = u.id
        ORDER BY
            p.id ASC
    ';
    $resultPosts = $db->query($sql);
}
catch(Exception $ex) {
    error(500, 'Could not query given Thread and Posts from Database: ' . $ex->getMessage());
}

$adminsOnly = (1 == $thread['admins_only']);

?>

<div class="container">
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
            while ($post = $resultPosts->fetchArray()) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><a name="post-<?php echo $post['id']; ?>"></a><p>User: <strong><?php echo $post['username']; ?></strong> <?php echo ($_SESSION['user_id'] == $post['user_id'] ? '(<a href="?page=editpost&id=' . $post['id'] . '">Edit</a>)' : '') ?><span class="pull-right"><?php echo $post['timestamp']; ?></span></p></div>
                    <div class="panel-body">
                        <p><?php echo nl2br($post['text']); ?></p>
                    </div>
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
</div>