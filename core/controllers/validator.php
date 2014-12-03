<?php

class Validator
{
    private $retval;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        global $useractions, $admin, $forum;

        foreach($_POST as $key => $val) {
            switch ($key) {
                case 'deposit':
                    $this->setReturningValue('deposit', $useractions->deposit($_POST['money_value']));
                    break;
                case 'withdraw':
                    $this->setReturningValue('withdraw', $useractions->withdraw($_POST['money_value']));
                    break;
                case 'buy_ticket':
                    $this->setReturningValue('buy_ticket', $useractions->buyTicket($_POST['location']));
                    break;
                case 'heal':
                    $this->setReturningValue('heal', $useractions->healInHospital());
                    break;
                case 'buy_house':
                    $this->setReturningValue('buy_house', $useractions->buyHouse($_POST['house-id']));
                    break;
                case 'buy_free':
                    $this->setReturningValue('buy_free', $useractions->buyFree($_POST['person']));
                    break;
                case 'number_shop':
                    $this->setReturningValue('number_shop', $useractions->buyFromShop($_POST));
                    break;
                case 'delete_message':
                    array_pop($_POST);
                    $this->setReturningValue('delete_message', $useractions->deleteMessage($_POST));
                    break;
                case 'delete_message_outbox':
                    array_pop($_POST);
                    $this->setReturningValue('delete_message_outbox', $useractions->deleteMessage($_POST, true));
                    break;
                case 'send_message':
                    $this->setReturningValue('send_message', $useractions->sendMessage($_POST['to'], $_POST['subject'], $_POST['message']));
                    break;
                case 'pimp_ho':
                    $this->setReturningValue('pimp_ho', $useractions->pimpHo());
                    break;
                case 'put_from_street':
                    $this->setReturningValue('put_from_street', $useractions->putHoFromStreet());
                    break;
                case 'get_ho_cash':
                    $this->setReturningValue('get_ho_cash', $useractions->getHoCash());
                    break;
                case 'new_transaction':
                    $this->setReturningValue('new_transaction', $useractions->createTransaction($_POST['transaction_amount'], $_POST['transaction_to']));
                    break;
                case 'deposit_family':
                    $this->setReturningValue('deposit_family', $useractions->depositFamily($_POST['money_value']));
                    break;
                case 'withdraw_family':
                    $this->setReturningValue('withdraw_family', $useractions->withdrawFamily($_POST['money_value']));
                    break;
                case 'update_family_message':
                    $this->setReturningValue('update_family_message', $useractions->updateFamilyMessage($_POST['family_message']));
                    break;
                case 'ban_ip':
                    $this->setReturningValue('ban_ip', $admin->banIp($_POST['ip']));
                    break;
                case 'create_topic':
                    $this->setReturningValue('create_topic', $forum->createTopic($_POST['topic_title'], $_POST['topic_content']));
                    break;
                case 'new_reaction':
                    $this->setReturningValue('new_reaction', $forum->createReaction($_POST['reaction_content']));
                    break;
                case 'new_forum':
                    $this->setReturningValue('new_forum', $forum->createForum($_POST['title'], $_POST['desc']));
                    break;
            }
        }

        foreach($_GET as $key => $val) {
            switch ($key) {
                case 'message_id':
                    $this->setReturningValue('message_id', $useractions->setMessageStatus());
                    break;
                case 'delete':
                    $this->setReturningValue('delete', $useractions->deleteMessage(array('messages' => array(0 => $_GET['delete']))));
                    break;
            }
        }
    }

    private function setReturningValue($name, $value)
    {
        $this->retval = array(
            $name  => $value
        );
    }

    public function getVal($name)
    {
        return $this->retval[$name];
    }

}