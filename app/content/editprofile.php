<?php
// make sure only registered users can enter this site
if(!isset($_SESSION['user_id'])) {
    echo getForbiddenMessage();
    return;
}

$adminMode = false;

$userId = $_SESSION['user_id'];
if(isset($_GET['id'])) {
    $adminMode = true;
    $userId = $_GET['id'];
}

// get user's data
try {
    $result = $model->getUserData($userId);
    if(false === $result || 0 >= count($result)) {
        error(500, 'Could not find given User in Database');
    }
    $userData = $result[0];
}
catch(Exception $ex) {
    error(500, 'Could not query given user from Database', $ex);
}

$errors = array();

// process post data
if('post' === strtolower($_SERVER['REQUEST_METHOD']) && isset($_POST)) {
    $changePassword = null;
    // change password if entered
    if(!empty($_POST['password'])) {
        if ($_POST['password'] === $_POST['password-repeat']) {
            $changePassword = hash($config['system']['hashing_algorithm'], $_POST['password']);
        }
        else {
            $errors[] = 'The passwords do not match';
        }
    }

    // Save Changes
    if(empty($errors)){
        // change profile info
        try {
            if($model->editUser($userId, $_POST['email'], $_POST['country'], $changePassword, ($adminMode && $userId !== $_SESSION['user_id']  ? (1 == $_POST['is_admin'] ? 1 : 0) : null))) {
                // on success: redirect
                redirect('?page=editprofile&saved=1' . ($adminMode ? '&id=' . $userId : ''));
            }
            else {
                $errors[] = ($adminMode ? 'The' : 'Your') . ' profile could not be updated.';
            }
        } catch (Exception $ex) {
            error(500, ($adminMode ? 'The' : 'Your') . ' profile could not be updated', $ex);
        }
    }
}

// list of all countries
$countryList = array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegowina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, the Democratic Republic of the', 'Cook Islands', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France Metropolitan', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard and Mc Donald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran (Islamic Republic of)', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao, People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia, The Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia (Slovak Republic)', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'Spain', 'Sri Lanka', 'St. Helena', 'St. Pierre and Miquelon', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen Islands', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (U.S.)', 'Wallis and Futuna Islands', 'Western Sahara', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe');
?>

<div class="row">
    <div class="col-lg-12">
        <h2><?php echo ($adminMode ? $userData['username'] . '\'s Profile <a href="?page=users" class="btn btn-default pull-right">Back to Administration</a>' : 'Your Profile') ?></h2>
        <?php
        if(!empty($errors)) {
            echo '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
        }
        elseif(isset($_GET['saved']) && 1 == $_GET['saved']) {
            echo '<div class="alert alert-success">' . ($adminMode ? 'The' : 'Your') . ' profile was updated.</div>';
        }
        ?>
        <form method="post" action="?page=editprofile<?php echo ($adminMode ? '&id=' . $userId : '') ?>">
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" name="email" value="<?php echo $userData['email']; ?>" id="email">
            </div>
            <div class="form-group">
                <label for="country">Location:</label>
                <select name="country" class="form-control" id="country">
                    <?php
                    foreach ($countryList as $country) {
                        echo '<option value="' . $country . '"' . ($country == $userData['country'] ? ' selected="selected"' : '') . '>' . $country . '</option>';
                    }
                    ?>
                </select>
            </div>
            <hr/>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="password-repeat">Repeat Password:</label>
                <input type="password" class="form-control" name="password-repeat" id="password-repeat">
            </div>
            <?php if($adminMode) { ?>
                <hr/>
                <div  class="checkbox">
                    <label><input type="checkbox" name="is_admin" value="1"<?php echo (1 == $userData['is_admin'] ? ' checked="checked"' : '') . ($userId === $_SESSION['user_id'] ? ' disabled="disabled"' : '') ?>> This User is an <strong>Administrator</strong></label>
                </div>
                <hr/>
            <?php } ?>
            <button type="submit" class="btn btn-primary"><?php echo icon('ok'); ?> Save</button>
        </form>
    </div>
</div>