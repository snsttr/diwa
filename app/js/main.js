$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});
});

$('.remove-user').click(function () {
    return window.confirm('Do you really want to delete this User?');
});

$('.remove-file').click(function () {
    return window.confirm('Do you really want to delete this File?');
});

$('.select-all-admins').click(function () {
    $('.select-admin').prop('checked', true);
});

$('.unselect-all-admins').click(function () {
    $('.select-admin').prop('checked', false);
});