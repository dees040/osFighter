<table>
    <tr>
        <td>
        <h1>Login</h1>
        <?php
            /**
             * User not logged in, display the login form.
             * If user has already tried to login, but errors were
             * found, display the total number of errors.
             * If errors occurred, they will be displayed.
             */
            if ($form->num_errors > 0) {
               echo $form->num_errors." error(s) found";
            }
        ?>
        <form action="core/process.php" method="POST">
            <table align="left" border="0" cellspacing="0" cellpadding="3">
                <tr>
                    <td>
                        Username:
                    </td>
                    <td>
                        <input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>">
                    </td>
                    <td>
                        <?php echo $form->error("user"); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Password:
                    </td>
                    <td>
                        <input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>">
                    </td>
                    <td>
                        <?php echo $form->error("pass"); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left">
                        <input type="checkbox" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>>
                        Remember me &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="sublogin" value="1">
                        <input type="submit" value="Login">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="left">
                        <br>[<a href="forgotpass.php">Forgot Password?</a>]
                    </td>
                    <td align="right"></td>
                </tr>
                <tr>
                    <td colspan="2" align="left">
                        <br>Not registered? <a href="register.php">Sign-Up!</a>
                    </td>
                </tr>
            </table>
        </form>
        </td>
    </tr>
</table>