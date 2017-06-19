<?php
// make sure only registered users can enter this site
if(!isset($_SESSION['user_id']) || 1 !== $_SESSION['user']['is_admin']) {
    echo getForbiddenMessage('This Site is for Administrators only.');
    return;
}

$deleted = null;

// delete user
try {
    if (isset($_GET['remove'])) {
        if ($_GET['remove'] !== $_SESSION['user_id']) {
            $sql = 'DELETE FROM ' . $config['database']['prefix'] . 'users WHERE id = ' . $_GET['remove'];
            $result = $db->exec($sql);
            $deleted = $result;
        } else {
            $deleted = false;
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not delete given User from Database: ' . $ex->getMessage());
}

// get all users
try {
    $sql = 'SELECT id, username, email, country, is_admin FROM ' . $config['database']['prefix'] . 'users';
    $result = $db->query($sql);
}
catch(Exception $ex) {
    error(500, 'Could not query Users from Database: ' . $ex->getMessage());
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>Administration</h1>

            <?php
            if($deleted === true) {
                echo '<div class="alert alert-success">The user has been deleted.</div>';
            }
            elseif($deleted === false) {
                echo '<div class="alert alert-danger">The user could not be deleted.</div>';
            }
            ?>

            <?php if($user = $result->fetchArray()) { ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>E-Mail</th>
                        <th>Country</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <?php do { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['country']; ?></td>
                            <td><?php echo ('1' === $user['is_admin'] ? icon('ok') : ''); ?></td>
                            <td>
                                <a href="/?page=profile&user_id=<?php echo $user['id']; ?>" class="btn btn-default" title="Edit User"><?php echo icon('edit'); ?> Edit</a>
                                <a href="/?page=users&remove=<?php echo $user['id']; ?>" class="btn btn-default remove-user" title="Remove User"><?php echo icon('remove'); ?> Remove</a>
                            </td>
                        </tr>
                    <?php } while (($user = $result->fetchArray())); ?>
                </table>
            <?php } ?>

        </div>
    </div>
</div>