<?php

class Model {

    private $db = null;
    private $prefix = null;

    public function __construct($pDatabase, $pPrefix) {
        $this->db = $pDatabase;
        $this->prefix = $pPrefix;
    }

    // ============================
    // USERS
    // ============================

    public function userSignIn($pEmail, $pPassword, $pHashingAlgorithm) {
        try {
            $sql = 'SELECT * FROM ' . $this->prefix . 'users WHERE email = \'' . $pEmail . '\' AND password = \'' . hash($pHashingAlgorithm, $pPassword) . '\';';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function isUserEmailInUse($pEmail) {
        try {
            $sql = 'SELECT * FROM ' . $this->prefix . 'users WHERE email = \'' . $pEmail . '\'';
            $result = $this->db->query($sql)->fetchAll();
            return (false !== $result && 0 < count($result));
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function isUsernameInUse($pUsername) {
        try {
            $sql = 'SELECT * FROM ' . $this->prefix . 'users WHERE username = \'' . $pUsername . '\'';
            $result = $this->db->query($sql)->fetchAll();
            return (false !== $result && 0 < count($result));
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function createUser($pUsername, $pPassword, $pEmail, $pCountry, $pHashingAlgorithm) {
        try {
            $sql = 'INSERT INTO ' . $this->prefix . 'users (username, password, email, country, is_admin) VALUES (\'' . $pUsername . '\', \'' . hash($pHashingAlgorithm, $pPassword) . '\', \'' . $pEmail . '\', \'' . $pCountry . '\', 0)';
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function getUserData($pUserId) {
        try {
            $sql = 'SELECT * FROM ' . $this->prefix . 'users WHERE id = ' . $pUserId;
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function editUser($pUserId, $pEmail, $pCountry, $pChangePassword, $pChangeAdmin) {
        try {
            // change password?
            $passwordSql = '';
            if(null !== $pChangePassword) {
                $passwordSql = ', password = \'' . $pChangePassword . '\'';
            }

            // change admin status?
            $adminSql = '';
            if(null !== $pChangeAdmin) {
                $adminSql = ', is_admin = ' . $pChangeAdmin;
            }

            $sql = 'UPDATE ' . $this->prefix . 'users SET email = \'' . $pEmail . '\', country = \'' . $pCountry . '\'' . $passwordSql . $adminSql . ' WHERE id = ' . $pUserId;
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function getAllUsers() {
        try {
            $sql = 'SELECT id, username, email, country, is_admin FROM ' . $this->prefix . 'users';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function getAllAdmins() {
        try {
            $sql = 'SELECT id, username, email, country, is_admin FROM ' . $this->prefix . 'users WHERE is_admin = 1';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function removeUser($pUserId) {
        try {
            $sql = 'DELETE FROM ' . $this->prefix . 'users WHERE id = ' . $pUserId;
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    // ============================
    // DOWNLOADS
    // ============================

    public function getDownloads($pApproved) {
        try {
            $sql = 'SELECT * FROM ' . $this->prefix . 'downloads WHERE approved = ' . ($pApproved ? 1 : 0);
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function createDownload($pAllowGuests, $pApproved, $pTitle, $pDescription, $pFile) {
        try {
            $sql = 'INSERT INTO ' . $this->prefix . 'downloads (allow_guests, approved, title, description, file) VALUES (' . ($pAllowGuests ? 1 : 0) . ', ' . ($pApproved ? 1 : 0) . ', \'' . $pTitle . '\', \'' . $pDescription . '\', \'' . $pFile . '\')';
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function approveDownload($pId, $pAllowGuests) {
        try {
            $sql = 'UPDATE ' . $this->prefix . 'downloads SET approved = 1, allow_guests = ' . ($pAllowGuests ? 1 : 0) . ' WHERE id = ' . $pId;
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    public function removeDownload($pId) {
        try {
            $sql = 'DELETE FROM ' . $this->prefix . 'downloads WHERE id = ' . $pId;
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }

    }

    // ============================
    // BOARD
    // ============================

    function getAllThreads() {
        try {
            $sql = '
                SELECT
                  t.id AS id,
                  t.title AS title,
                  t.admins_only AS admins_only,
                  MAX(p.timestamp) AS last_post,
                  COUNT(*) AS count_post
                FROM
                  ' . $this->prefix . 'threads t,
                  ' . $this->prefix . 'posts p
                WHERE
                  p.thread_id = t.id
                GROUP BY
                  t.id
                ORDER BY
                  last_post DESC;';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function getThread($pThreadId) {
        try {
            $sql = '
                SELECT
                  t.id AS id,
                  t.title AS title,
                  t.admins_only AS admins_only,
                  MAX(p.timestamp) AS last_post,
                  COUNT(*) AS count_post
                FROM
                  ' . $this->prefix . 'threads t,
                  ' . $this->prefix . 'posts p
                WHERE
                  t.id = ' . $pThreadId . ' AND
                  p.thread_id = t.id
                GROUP BY
                  t.id';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function getPosts($pThreadId) {
        try {
            $sql = '
                SELECT
                  p.*,
                  u.username
                FROM
                  ' . $this->prefix . 'posts p,
                  ' . $this->prefix . 'users u
                WHERE
                    p.thread_id = ' . $pThreadId . ' AND
                    p.user_id = u.id
                ORDER BY
                    p.id ASC';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function createThread($pTitle, $pAdminsOnly) {
        try {
            $sql = 'INSERT INTO ' . $this->prefix . 'threads (title, admins_only) VALUES (\'' . $pTitle .'\', ' . ($pAdminsOnly ? 1 : 0) . ')';
            $result = $this->db->exec($sql);
            if($result) {
                return $this->db->lastInsertId();
            }
            return false;
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function createPost($pThreadId, $pUserId, $pText) {
        try {
            $sql = 'INSERT INTO ' . $this->prefix . 'posts (thread_id, user_id, text) VALUES (' . $pThreadId . ', ' . $pUserId . ', \'' . $pText . '\')';
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function getPost($pPostId) {
        try {
            $sql = '
                SELECT
                  *
                FROM
                  ' . $this->prefix . 'posts
                WHERE
                  id = ' . $pPostId . ';';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function editPost($pPostId, $pPost) {
        try {
            $sql = 'UPDATE ' . $this->prefix . 'posts SET text = \'' . $_POST['post'] . '\' WHERE id = ' . $_GET['id'];
            $result = $this->db->exec($sql);
            return ($result !== 0);
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }

    function getPostsByUser($pUserId) {
        try {
            $sql = '
                SELECT
                  ' . $this->prefix . 'threads.id AS thread_id,
                  ' . $this->prefix . 'threads.title AS thread_title,
                  ' . $this->prefix . 'threads.admins_only AS thread_admins_only,
                  ' . $this->prefix . 'posts.id AS post_id,
                  ' . $this->prefix . 'posts.timestamp AS post_timestamp,
                  ' . $this->prefix . 'posts.text AS post_text
                FROM
                  ' . $this->prefix . 'posts,
                  ' . $this->prefix . 'threads
                WHERE
                  ' . $this->prefix . 'posts.user_id = ' . $pUserId . ' AND
                  ' . $this->prefix . 'posts.thread_id = ' . $this->prefix . 'threads.id
                ORDER BY
                  ' . $this->prefix . 'posts.timestamp DESC;';
            return $this->db->query($sql)->fetchAll();
        }
        catch(Exception $ex) {
            error(500, 'Query could not be executed', $ex);
        }
    }
}