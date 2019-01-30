<?php

// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

$isAdmin = (isset($_SESSION['user']['is_admin']) && 1 == $_SESSION['user']['is_admin']);

// process post & upload
try {
    // check data
    $errors = array();
    if ('post' === strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST)) {
        // check if title has enough chars
        if (!isset($_POST['title']) || 3 > strlen($_POST['title'])) {
            $errors[] = 'Your title has to be at least 3 Characters long.';
        }

        // check if post has enough chars
        if (!isset($_POST['description']) || 3 > strlen($_POST['description'])) {
            $errors[] = 'Your description has to be at least 3 Characters long.';
        }

        if(!isset($_FILES['file']['name']) || empty($_FILES['file']['name'])) {
            $errors[] = 'You have to choose a file.';
        }

        if(empty($errors)) {
            // try to upload the file
            $uploaddir = ROOT_PATH . '/files/';
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $errors[] = 'The file could not be uploaded: ' . getUploadError($_FILES['file']['error']);
            } else {
                $allowGuests = (isset($_POST['guests']) && '1' === $_POST['guests']);
                if(!$model->createDownload($allowGuests, $isAdmin, $_POST['title'], $_POST['description'], basename($_FILES['file']['name']))) {
                    $errors[] = 'The file could not be written to the database.';
                    // delete file
                    @unlink($uploadfile);
                }
                else {
                    redirect('?page=upload&saved=1');
                }
            }
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not save Upload', $ex);
}

?>

<div class="row">
    <div class="col-lg-12">
        <h1><?php echo ($isAdmin ? 'Upload a File' : 'Recommend a File'); ?><a href="?page=downloads" class="btn btn-primary pull-right"><?php echo icon('download-alt'); ?> Back to Downloads</a></h1>
        <?php
        if(!$isAdmin) {
            echo '<div class="alert alert-warning">An administrator has to review your download before it is listed on the downloads page.</div>';
        }
        else {
            echo '<div class="alert alert-warning">Since you are an admin your download will appear on the site right after you uploaded it.</div>';
        }

        if(!empty($errors)) {
            echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
        }
        elseif(isset($_GET['saved']) && 1 == $_GET['saved']) {
            echo '<div class="alert alert-success">Your Upload has been saved ' . ($isAdmin ? ' and published' : 'and soon will be reviewed by an Admin') . '.</div>';
        }
        ?>
        <form action="?page=upload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>" id="title">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" rows="5" name="description" id="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="file">File (max. 10MB):</label>
                <input type="file" name="file" id="file">
            </div>
            <?php if($isAdmin) { ?>
                <div  class="checkbox">
                    <label><input type="checkbox" name="guests" value="1"<?php echo (isset($_POST['guests']) && '1' === $_POST['guests'] ? ' checked="checked"' : '') ?>> <strong>Guests</strong> can also download this file.</label>
                </div>
            <?php } ?>
            <button type="submit" class="btn btn-primary"><?php echo icon('cloud-upload'); ?> Upload File</button>
        </form>
    </div>
</div>