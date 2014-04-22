<table width="100%" border="0" cellspacing="2" cellpadding="2" class="mod_list">
    <tr>
        <td width="35%">Username:</td>
        <td width="6%" align=center><img src="files/images/icons/user.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=profile&x='.$data['id'].'"><?=$user->info->username; ?></a></td>
    </tr>
    <tr>
        <td width="35%">Health:</td>
        <td width="6%" align=center><img src="files/images/icons/heart.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=hospital"><?=$user->stats->health; ?>%</a></td>
    </tr>
    <tr>
        <td width="35%">Power:</td>
        <td width="6%" align=center><img src="files/images/icons/star.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=shop"><?=$user->stats->power; ?></a></td>
    </tr>
    <tr>
        <td width="35%">Money (cash):</td>
        <td width="6%" align=center><img src="files/images/icons/money.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=bank">&euro; <?=$user->stats->money; ?></a></td>
    </tr>
    <tr>
        <td width="35%">Money (bank):</td>
        <td width="6%" align=center><img src="files/images/icons/bank.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=bank">&euro; <?=$user->stats->bank; ?></a></td>
    </tr>
    <tr>
        <td width="35%">Rank:</td>
        <td width="6%" align=center><img src="files/images/icons/lightning.png" border="0px"></td>
        <td width="69%"><?=$info['ranks'][$user->stats->rank]; ?></td>
    </tr>
    <tr>
        <td width="35%">Credits:</td>
        <td width="6%" align=center><img src="files/images/icons/coins.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=buycredits">-</a></td>
    </tr>
    <tr>
        <td width="35%">VIP:</td>
        <td width="6%" align=center><img src="files/images/icons/credit-card-green.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=buycredits">-</a></td>
    </tr>
    <tr>
        <td width="35%">Rank process:</td>
        <td width="6%" align=center><img src="files/images/icons/wand.png" border="0px"></td>
        <td width="69%"><?=$user->stats->rank_process; ?>%</td>
    </tr>

    <tr>
        <td width="35%">City:</td>
        <td width="6%" align=center><img src="files/images/icons/globe.png" border="0px"></td>
        <td width="69%"><a href="index.php?p=station"><?=$info['cities'][$user->stats->city]; ?></a></td>
    </tr>
    <tr>
        <td width="35%">Family:</td>
        <td width="6%" align=center><img src="files/images/icons/drive_user.png" border="0px"></td>
        <td width="69%"><?=$user->family->name; ?></td>
    </tr>
    <tr>
        <td width="35%">Veilig:</td>
        <td width="6%" align=center><img src="files/images/icons/plus-shield.png" border="0px"></td>
        <td width="69%">-</td>
    </tr>
    <tr>
        <td width="35%">Bescherming:</td>
        <td width="6%" align=center><img src="files/images/icons/tick-shield.png" border="0px"></td>
        <td width="69%">-</td>
    </tr>
    <tr>
        <td width="35%">Mijn secret link:</td>
        <td width="6%" align=center><img src="files/images/icons/ruby_link.png" border="0px"></td>
        <td width="69%"><a href="#">-</a></td>
    </tr>
    <tr>
        <td width="35%">Secret link info:</td>
        <td width="6%" align=center><img src="files/images/icons/information.png" border="0px"></td>
        <td width="69%">When somebody clicks your secret link, you get: 500 cash, 1000 bank and 1 killer extra.</td>
    </tr>

</table>