<ul class="tabs" id="tab"  data-persist="true">
    <li><a href="#tab1">Inbox</a></li>
    <li><a href="#tab2">Outbox</a></li>
    <li><a href="#tab3">New</a></li>
</ul>

<div class="tabcontents inhoud">
    <!-- file-sytem page tab1: Inbox-->
    <div id="tab1">
        <?php
            foreach($user->getInbox() as $message) {
                echo $message->id;
            }
        ?>
    </div>

    <!-- file-sytem page tab2: Outbox -->
    <div id="tab2">

    </div>

    <!-- file-sytem page tab1: New -->
    <div id="tab3">

    </div>
</div>