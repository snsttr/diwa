<?php

// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

// get threads
try {
    $result = $model->getAllThreads();
}
catch(Exception $ex) {
    error(500, 'Could not query threads from Database', $ex);
}

?>
<div class="row">
    <div class="col-lg-12">
        <h1>Board<a href="?page=newthread" class="btn btn-primary pull-right"><?php echo icon('plus'); ?> New Thread</a></h1>

        <?php if(false !== $result && 0 < count($result)) { ?>
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
                foreach($result as $thread) {
                    $adminsOnly = (1 == $thread['admins_only']);
                    $adminRestricted = ($adminsOnly && isset($_SESSION['user']['is_admin']) && 0 == $_SESSION['user']['is_admin']);
                    ?>
                    <tr class="<?php echo ($adminRestricted ? '' : 'clickable-row'); ?>">
                        <td><?php echo ($adminsOnly ? icon('lock') : '') . ' <strong>' . ($adminRestricted ? '' . $thread['title'] : '<a href="?page=thread&id=' . $thread['id'] . '">' . $thread['title'] . '</a>') . '</strong>'; ?></td>
                        <td><?php echo $thread['count_post']; ?></td>
                        <td><?php echo $thread['last_post']; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>