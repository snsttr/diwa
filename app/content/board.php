<?php

// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

// get threads
try {
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
          p.thread_id = t.id
        GROUP BY
          t.id
        ORDER BY
          last_post DESC;';
    $result = $db->query($sql);
}
catch(Exception $ex) {
    error(500, 'Could not query threads from Database: ' . $ex->getMessage());
}

?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>Board<a href="/?page=newthread" class="btn btn-primary pull-right"><?php echo icon('plus'); ?> New Thread</a></h1>

            <?php if($thread = $result->fetchArray()) { ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th># of Posts</th>
                        <th>Last Post</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    do {
                        $adminsOnly = (1 == $thread['admins_only']);
                        $adminRestricted = ($adminsOnly && 0 == $_SESSION['user']['is_admin']);
                        ?>
                        <tr>
                            <td><?php echo ($adminsOnly ? icon('lock') : icon('user')) . ' <strong>' . ($adminRestricted ? '' . $thread['title'] : '<a href="/?page=thread&id=' . $thread['id'] . '">' . $thread['title'] . '</a>') . '</strong>'; ?></td>
                            <td><?php echo $thread['count_post']; ?></td>
                            <td><?php echo $thread['last_post']; ?></td>
                        </tr>
                        <?php
                    } while ($thread = $result->fetchArray());
                    ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>