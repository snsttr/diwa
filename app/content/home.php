<div class="jumbotron">
    <div class="container"><h1>Welcome to DIWA!</h1>
        <p><strong>D</strong>eliberately <strong>I</strong>nsecure <strong>W</strong>eb <strong>A</strong>pplication</p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h2>Feel free to hack this site!</h2>
        <p>This site was made to be hacked. A lot of bugs and vulnerabilities can be exploited to learn how to hack a bad developed website. This
        should show every developer why it is important to care for website security.</p>
        <p>DIWA does not guide you through the different attacks or gives you exercises to fulfill. Just try to break everything ;-)</p>
        <?php if($config['site']['show_extended_homepage']) { ?>
            <h2>How insecure is DIWA?</h2>
            <p>DIWA is horribly insecure! The following vulnerabilities and attack vectors exist in this application:</p>
            <ul>
                <li><a href="https://www.owasp.org/index.php/SQL_Injection" target="_blank">SQL Injection</a></li>
                <li><a href="https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)" target="_blank">XSS (Corss-Site-Scripting)</a></li>
                <li><a href="https://www.owasp.org/index.php/Cross-Site_Request_Forgery_(CSRF)" target="_blank">CSRF (Cross-Site-Request-Forgery)</a></li>
                <li><a href="https://www.owasp.org/index.php/Session_hijacking_attack" target="_blank">Session Hijacking</a></li>
                <li><a href="https://www.owasp.org/index.php/Session_fixation" target="_blank">Session Fixation</a></li>
                <li><a href="https://www.owasp.org/index.php/Content_Spoofing" target="_blank">Content Spoofing</a></li>
                <li><a href="https://www.owasp.org/index.php/Top_10_2013-A7-Missing_Function_Level_Access_Control" target="_blank">Missing Function Level Access Control</a></li>
                <li><a href="https://www.owasp.org/index.php/Path_Traversal" target="_blank">Path Traversal</a></li>
                <li><a href="https://www.owasp.org/index.php/Top_10_2013-A6-Sensitive_Data_Exposure" target="_blank">Sensitive Data Exposure</a></li>
                <li><a href="https://www.owasp.org/index.php/Brute_force_attack" target="_blank">Brute Force Attacks</a></li>
            </ul>
            <h2>What can I do now?</h2>
            <p>Try to find out more about the possible attack Vectors shown above. Then just feel free to hack everything on this website you can find, e.g.:</p>
            <ul>
                <li>Brute Force attack the Invitation Code in the <a href="?page=register">Registration form</a></li>
                <li>Manipulate Path to <a href="?page=documentation">Documentation</a> files to see "hidden" oder "protected" Content</li>
                <li>Use SQL Injection to override the Log-In check</li>
                <li>Send Spam through the <a href="?page=contact">Contact Form</a></li>
                <li>...</li>
            </ul>
        <?php } ?>
    </div>
</div>