<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Inbox</a></li>
    <li><a href="#tab2">Outbox</a></li>
    <li><a href="#tab3">New</a></li>
</ul>

<div class="tabcontents inhoud">
    <?php
    if (isset($_GET['delete'])) {
        echo $user->deleteMessage(array(array(0 => $_GET['delete'])));
    }
    ?>
    <!-- file-sytem page tab1: Inbox-->
    <div id="tab1">
        <?php
            if (isset($_POST['delete_message'])) {
                array_pop($_POST);
                echo $user->deleteMessage($_POST);
            }
        ?>
        <form method="post">
            <table width="100%">
                <tr>
                    <td colspan="1" align="center" width="2%">
                        Options
                    </td>
                    <td colspan="2" align="center">
                        Title
                    </td>
                    <td colspan="2" align="center">
                        From
                    </td>
                    <td colspan="2" align="center">
                        Date
                    </td>
                </tr>
                <?php
                foreach ($user->getInbox() as $message) {
                    $from = $database->getUserInfoById($message->from_id)->username;
                    ?>
                    <tr>
                        <td align="center">
                            <input type="checkbox" name="messages[]" value="<?= $message->id; ?>">
                        </td>
                        <td>
                            <img src="files/images/icons/<?= ($message->status) ? 'email_open' : 'email'; ?>.png">
                        </td>
                        <td>
                            <a href="personal/message?id=<?= $message->id; ?>"><?= $message->subject; ?></a>
                        </td>
                        <td>
                            <img
                                src="files/images/icons/<?= ($database->userOnline($from)) ? 'status_online' : 'status_offline'; ?>.png">
                        </td>
                        <td>
                            <a href="personal/user-info?user=<?= $from; ?>"><?= $from; ?></a>
                        </td>
                        <td>
                            <img src="files/images/icons/clock.png">
                        </td>
                        <td>
                            <?= date("Y-m-d H:i", $message->date); ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="7">
                        <input type="submit" value="Delete" name="delete_message">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- file-sytem page tab2: Outbox -->
    <div id="tab2">
        <?php
        if (isset($_POST['delete_message_outbox'])) {
            array_pop($_POST);
            echo $user->deleteMessage($_POST, true);
        }
        ?>
        <form method="post">
            <table width="100%">
                <tr>
                    <td colspan="1" align="center" width="2%">
                        Options
                    </td>
                    <td colspan="2" align="center">
                        Title
                    </td>
                    <td colspan="2" align="center">
                        To
                    </td>
                    <td colspan="2" align="center">
                        Date
                    </td>
                </tr>
                <?php
                foreach ($user->getOutbox() as $message) {
                    $to = $database->getUserInfoById($message->to_id)->username;
                    ?>
                    <tr>
                        <td align="center">
                            <input type="checkbox" name="messages[]" value="<?= $message->id; ?>">
                        </td>
                        <td>
                            <img src="files/images/icons/<?= ($message->status) ? 'email_open' : 'email'; ?>.png">
                        </td>
                        <td>
                            <a href="personal/message?id=<?= $message->id; ?>"><?= $message->subject; ?></a>
                        </td>
                        <td>
                            <img src="files/images/icons/<?= ($database->userOnline($to)) ? 'status_online' : 'status_offline'; ?>.png">
                        </td>
                        <td>
                            <a href="personal/user-info?user=<?= $to; ?>"><?= $to; ?></a>
                        </td>
                        <td>
                            <img src="files/images/icons/clock.png">
                        </td>
                        <td>
                            <?= date("Y-m-d H:i", $message->date); ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="7">
                        <input type="submit" value="Delete out outbox" name="delete_message_outbox">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- file-sytem page tab1: New -->
    <div id="tab3">
        <?php
        if (isset($_POST['send_message'])) {
            echo $user->sendMessage($_POST['to'], $_POST['subject'], $_POST['message']);
        }
        ?>
        <form method="post">
            <table>
                <tr>
                    <td>
                        From:
                    </td>
                    <td>
                        <?=$user->info->username; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        To:
                    </td>
                    <td>
                        <input type="text" name="to" placeholder="Username" maxlength="30" value="<?=(isset($_GET['reply'])) ? $_GET['reply'] : ''; ?>" size="20">
                    </td>
                </tr>
                <tr>
                    <td>
                        Subject:
                    </td>
                    <td>
                        <input type="text" name="subject" value="<?=(isset($_GET['subject'])) ? "RE:".$_GET['subject'] : ''; ?>" size="63" maxlength="50">
                    </td>
                </tr>
                <tr>
                    <td>
                        Message:
                    </td>
                    <td>
                        <textarea name="message" style="width:100%"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Send message!" name="send_message">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>