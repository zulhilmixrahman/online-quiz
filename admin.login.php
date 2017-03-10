<?php
require_once 'inc.header.php';

if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    header("Location: admin.result.php");
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $login = Admin::where('username', $_POST['username'])->where('password', $_POST['password'])->find_one();

    if (!$login) {
    } else {
        $_SESSION['registered'] = true;
        $_SESSION['admin'] = true;
        $_SESSION['userid'] = $login->id;
        $_SESSION['fullname'] = $login->username;
        $_SESSION['username'] = $login->username;
        header("Location: admin.result.php");
    }
}

?>
    <form class="form-signin" method="POST">
        <div class="panel periodic-login">
            <div class="panel-body text-center">
                <p class="atomic-mass">Login Admin JPP</p>
                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input type="text" name="username" class="form-text" required>
                    <span class="bar"></span>
                    <label>Username</label>
                </div>
                <div class="form-group form-animate-text" style="margin-top:40px !important;">
                    <input type="password" name="password" class="form-text" required>
                    <span class="bar"></span>
                    <label>Password</label>
                </div>
                <button type="submit" class="btn col-md-12">Login</button>
            </div>
        </div>
    </form>
<?php
require_once 'inc.footer.php';