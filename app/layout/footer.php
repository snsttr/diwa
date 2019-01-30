<?php
define('FOOTER_TEMPLATE', 1);
?>
</div>

<?php if($config['site']['show_footer_info']) { ?>
    <hr/>
    <div class="container">
        <footer class="text-center">
            <div class="btn-group large">
                <a href="?page=installation" class="btn btn-default" data-toggle="tooltip" title="Reset DIWA's Database"><i class="fa fa-bomb" aria-hidden="true"></i></a>
                <a href="https://choosealicense.com/licenses/mit/" target="_blank" class="btn btn-default" data-toggle="tooltip" title="This Project is licensed under MIT License"><i class="fa fa-gavel" aria-hidden="true"></i></a>
                <a href="https://github.com/snsttr/diwa" target="_blank" class="btn btn-default" data-toggle="tooltip" title="DIWA's GitHub Repository"><i class="fa fa-github" aria-hidden="true"></i></a>
                <a href="https://github.com/snsttr/diwa/issues" target="_blank" class="btn btn-default" data-toggle="tooltip" title="Report a Bug or suggest an Improvement"><i class="fa fa-bug" aria-hidden="true"></i></a>
            </div>
        </footer>
    </div>
<?php } ?>

<script src="js/jquery.min.js" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="js/main.js"></script>

</body>
</html>