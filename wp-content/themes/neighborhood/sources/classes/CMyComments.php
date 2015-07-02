<?php

class CMyComments {

    // constructor
    function CMyComments() {
    }

    // return comments block
    function getComments($i) {
        // draw last 10 comments
        $sComments = '';
        $aComments = $GLOBALS['MySQL']->getAll("SELECT * FROM `s281_items_cmts` WHERE `c_item_id` = '{$i}' ORDER BY `c_when` DESC LIMIT 10");
        foreach ($aComments as $i => $aCmtsInfo) {
            $sWhen = date('F j, Y H:i', $aCmtsInfo['c_when']);
            $sComments .= <<<EOF
<div class="comment" id="{$aCmtsInfo['c_id']}">
    <p>Comment from {$aCmtsInfo['c_name']} <span>({$sWhen})</span>:</p>
    <p>{$aCmtsInfo['c_text']}</p>
</div>
EOF;
        }

        return <<<EOF
<div class="comments" id="comments">
    <h2>Comments</h2>
    <div id="comments_warning1" style="display:none">Don`t forget to fill both fields (Name and Comment)</div>
    <div id="comments_warning2" style="display:none">You can't post more than one comment per 10 minutes (spam protection)</div>
    <form onsubmit="return false;">
        <table>
            <tr><td class="label"><label>Your name: </label></td><td class="field"><input type="text" value="" title="Please enter your name" id="name" /></td></tr>
            <tr><td class="label"><label>Comment: </label></td><td class="field"><textarea name="text" id="text"></textarea></td></tr>
            <tr><td class="label">&nbsp;</td><td class="field"><button onclick="submitComment({$i}); return false;">Post comment</button></td></tr>
        </table>
    </form>
    <div id="comments_list">{$sComments}</div>
</div>
EOF;
    }

    function acceptComment() {
        $iItemId = (int)$_POST['id']; // prepare necessary information
        $sIp = $this->getVisitorIP();
        $sName = $GLOBALS['MySQL']->escape(strip_tags($_POST['name']));
        $sText = $GLOBALS['MySQL']->escape(strip_tags($_POST['text']));

        if ($sName && $sText) {
            // check - if there is any recent post from you or not
            $iOldId = $GLOBALS['MySQL']->getOne("SELECT `c_item_id` FROM `s281_items_cmts` WHERE `c_item_id` = '{$iItemId}' AND `c_ip` = '{$sIp}' AND `c_when` >= UNIX_TIMESTAMP() - 600 LIMIT 1");
            if (! $iOldId) {
                // if everything is fine - allow to add comment
                $GLOBALS['MySQL']->res("INSERT INTO `s281_items_cmts` SET `c_item_id` = '{$iItemId}', `c_ip` = '{$sIp}', `c_when` = UNIX_TIMESTAMP(), `c_name` = '{$sName}', `c_text` = '{$sText}'");
                $GLOBALS['MySQL']->res("UPDATE `s281_photos` SET `comments_count` = `comments_count` + 1 WHERE `id` = '{$iItemId}'");

                // and print out last 10 comments
                $sOut = '';
                $aComments = $GLOBALS['MySQL']->getAll("SELECT * FROM `s281_items_cmts` WHERE `c_item_id` = '{$iItemId}' ORDER BY `c_when` DESC LIMIT 10");
                foreach ($aComments as $i => $aCmtsInfo) {
                    $sWhen = date('F j, Y H:i', $aCmtsInfo['c_when']);
                    $sOut .= <<<EOF
<div class="comment" id="{$aCmtsInfo['c_id']}">
    <p>Comment from {$aCmtsInfo['c_name']} <span>({$sWhen})</span>:</p>
    <p>{$aCmtsInfo['c_text']}</p>
</div>
EOF;
                }
                return $sOut;
            }
        }
        return 1;
    }

    // get visitor IP
    function getVisitorIP() {
        $ip = "0.0.0.0";
        if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) ) {
            $ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
            $ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
        } elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR']))) {
            if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP']))) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        return $ip;
    }
}

$GLOBALS['MyComments'] = new CMyComments();

?>