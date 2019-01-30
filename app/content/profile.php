<?php
$userId = false;
if(isset($_GET['user_id'])) {
    $userId =  $_GET['user_id'];
}

// find user in database
try {
    if(false !== $userId) {
        $user = $model->getUserData($userId);
        if (count($user) <= 0) {
            $userId = false;
        }
    }
}
catch (Exception $ex) {
    error(500, 'Could not query given user from Database', $ex);
}

if(false === $userId) {
    error(404, 'User could not be found');
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
        <h1><?php echo $user[0]['username']; ?>'s Profile<?php echo ($user[0]['is_admin'] ? '<span class="label label-danger pull-right">Admin</span>' : '<span class="label label-default pull-right">User</span>'); ?></h1>
        <table class="table">
            <tbody>
            <tr>
                <td><strong>Username</strong></td>
                <td><?php echo $user[0]['username']; ?></td>
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
                $adminRestricted = ($adminsOnly && isset($_SESSION['user']['is_admin']) && 0 == $_SESSION['user']['is_admin']);
                ?>
                <tr class="<?php echo ($adminRestricted ? '' : 'clickable-row'); ?>">
                    <td><?php echo ($adminsOnly ? icon('lock') : '') . ' <strong>' . ($adminRestricted ? '' . $post['thread_title'] : '<a href="?page=thread&id=' . $post['thread_id'] . '">' . $post['thread_title'] . '</a>') . '</strong>'; ?></td>
                    <td><?php echo $post['post_timestamp']; ?></td>
                    <td><?php echo ($adminRestricted ? '<em>for Admins only</em>' :  $post['post_text']); ?></td>
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