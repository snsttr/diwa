<?php

$loggedIn = isLoggedIn();
$isAdmin = (isset($_SESSION['user']['is_admin']) && 1 == $_SESSION['user']['is_admin']);
$reviewMode = (isset($_GET['review']) && '1' == $_GET['review']);

// process review actions (approve / delete)
try {
    $error = false;
    if(isset($_GET['approve']) && !empty($_GET['approve'])) {
        // approve file in DB
        $allowGuests = '';
        if(isset($_GET['guests']) && '1' === $_GET['guests']) {
            $allowGuests = ', allow_guests = 1';
        }
        ;
        if($model->approveDownload($_GET['approve'], (isset($pAllowGuests) && '1' === $pAllowGuests))) {
            // redirect
            redirect('?page=downloads&review=1&approved=1');
        }
        else {
            $error = 'The File could not be published.';
        }
    }
    elseif(isset($_GET['delete']) && !empty($_GET['delete'])) {
        // delete from DB
        if($model->removeDownload($_GET['delete'])) {
            // redirect
            redirect('?page=downloads' . ($reviewMode ? '&review=1' : '') . '&deleted=1');
        }
        else {
            $error = 'The File could not be deleted.';
        }
    }
}
catch(Exception $ex) {
    error(500, 'Could not execute given (approve/delete) action', $ex);
}

// show downloads
try {
    $result = $model->getDownloads(($reviewMode ? 0 : 1));
}
catch(Exception $ex) {
    error(500, 'Could not query downloads from Database', $ex);
}

?>

<div class="row">
    <div class="col-lg-12">
        <h1>Downloads
            <?php
            if($loggedIn) {
                if ($isAdmin) {
                    ?>
                    <div class="pull-right btn-group">
                        <?php if ($reviewMode) { ?>
                            <a href="?page=downloads" class="btn btn-default"><?php echo icon('download-alt'); ?> Switch to Download Mode</a>
                        <?php } else { ?>
                            <a href="?page=downloads&review=1" class="btn btn-default"><?php echo icon('eye-open'); ?> Switch to Review Mode</a>
                        <?php } ?>
                        <a href="?page=upload" class="btn btn-primary"><?php echo icon('cloud-upload'); ?> Upload a File</a>
                    </div>
                <?php } else { ?>

                    <a href="?page=upload" class="btn btn-primary pull-right">Recommend a File</a>
                    <?php
                }
            }?>
        </h1>
        <?php
        if(!$loggedIn) {
            echo '<div class="alert alert-warning">Please log-in to see all Downloads.</div>';
        }

        if($reviewMode) {
            echo '<div class="alert alert-warning">You are in review mode where you can approve or delete new files.</div>';
        }

        if(false !== $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        elseif(isset($_GET['approved']) && 1 == $_GET['approved']) {
            echo '<div class="alert alert-success">The File has been approved and published.</div>';
        }
        elseif(isset($_GET['deleted']) && 1 == $_GET['deleted']) {
            echo '<div class="alert alert-success">The File has been deleted.</div>';
        }
        ?>
    </div>
</div>
<?php
$count = 0;
foreach ($result as $download) {
    if(1 == $download['allow_guests'] || $loggedIn) {
        if($count === 0) {
            echo '<div class="row">';
        }
        ?>
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo (1 == $download['allow_guests'] ? '' : icon('user', 'User only Download')); ?> <strong><?php echo $download['title']; ?></strong></div>
                <div class="panel-body"><p><?php echo $download['description']; ?></p></div>
                <div class="panel-footer text-center">
                    <div class="btn-group">
                        <a href="download.php?file=<?php echo $download['file']; ?>" class="btn btn-default"><?php echo icon('download-alt'); ?> <strong><?php echo $download['file']; ?></strong></a>
                        <?php if($isAdmin) { ?>
                            <a href="?page=downloads<?php echo ($reviewMode ? '&review=1' : ''); ?>&delete=<?php echo $download['id']; ?>"class="btn btn-danger remove-file" title="Delete the file"><?php echo icon('remove'); ?> Delete</a>
                            <?php if ($reviewMode) { ?>
                                <div class="btn-group">
                                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"><?php echo icon('ok'); ?> Approve <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="?page=downloads&review=1&approve=<?php echo $download['id']; ?>"><?php echo icon('user'); ?> To Users Only</a></li>
                                        <li><a href="?page=downloads&review=1&approve=<?php echo $download['id']; ?>&guests=1"><?php echo icon('ok'); ?> To Users & Guests</a></li>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if($count === 1) {
            echo '</div>';
            $count = 0;
        }
        else {
            $count++;
        }
    }
}
?>