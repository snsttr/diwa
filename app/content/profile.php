<?php
$isAdmin = (isset($_SESSION['user']['is_admin']) && 1 == $_SESSION['user']['is_admin']);

if(!empty($_GET['id'])) {
    $userId =  $_GET['id'];
}
else {
    error(404, 'User could not be found (No "id" provided).');
}

// find user in database
try {
    if(false !== $userId) {
        $user = $model->getUserData($userId);
        if (count($user) <= 0) {
            error(404, 'User could not be found (Wrong "id" provided)');
        }
    }
}
catch (Exception $ex) {
    error(500, 'Could not query given user from Database', $ex);
}

// find user's threads/posts
try {
    $posts = $model->getPostsByUser($userId);
}
catch(Exception $ex) {
    error(500, 'Could not query posts of given user from Database', $ex);
}

?>
<div class="row">
    <div class="col-lg-12">
        <h1>
            <?php echo $user[0]['username']; ?>'s Profile
            <?php
            if(isset($_SESSION['user']['id']) &&  $userId === $_SESSION['user']['id']) {
                echo '<span class="pull-right"><a href="?page=editprofile" class="btn btn-primary">Edit your profile</a></span>';
            }
            elseif($isAdmin) {
                echo '<span class="pull-right"><a href="?page=editprofile&id=' . $userId . '" class="btn btn-primary">Edit this profile</a></span>';
            }
            ?>
        </h1>
        <table class="table">
            <tbody>
            <tr>
                <td><strong>Username</strong></td>
                <td><?php echo $user[0]['username']; ?></td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td><?php echo ($user[0]['is_admin'] ? '<span class="label label-danger">Admin</span>' : '<span class="label label-default">User</span>'); ?></td>
            </tr>
            <tr>
                <td><strong>E-Mail</strong></td>
                <td><a href="mailto:<?php echo $user[0]['email']; ?>"><?php echo $user[0]['email']; ?></a></td>
            </tr>
            <tr>
                <td><strong>Location</strong></td>
                <td><?php echo $user[0]['country']; ?></td>
            </tr>
            <tr>
                <td><strong>Board Posts</strong></td>
                <td><?php echo count($posts); ?></td>
            </tr>
            </tbody>
        </table>
        <h2>User's Board Posts</h2>
        <?php
        if(count($posts) >= 0) {
            echo '<table class="table"><tbody>';
            foreach ($posts as $post) {
                $adminsOnly = (1 == $post['thread_admins_only']);
                $adminRestricted = (!$adminsOnly || $isAdmin);
                ?>
                <tr class="<?php echo ($adminRestricted ? 'clickable-row' : ''); ?>">
                    <td><?php echo ($adminsOnly ? icon('lock') : '') . ' <strong>' . ($adminRestricted ? '<a href="?page=thread&id=' . $post['thread_id'] . '">' . $post['thread_title'] . '</a>' : $post['thread_title']) . '</strong>'; ?></td>
                    <td><?php echo $post['post_timestamp']; ?></td>
                    <td><?php echo ($adminRestricted ? $post['post_text'] : '<em>for Admins only</em>'); ?></td>
                </tr>
                <?php
            }
            echo '</tbody></table>';
        }
        else {
            echo '<p>No Posts, yet.</p>';
        }
        ?>
    </div>
</div>