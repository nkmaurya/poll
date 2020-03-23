<?php
namespace App\Controller;

use App\Helpers\SessionHelper;
use App\Helpers\UtilityHelper;
use App\Model\PollModel;

/**
 * PollController
 */
class PollController extends BaseController
{    
    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * list
     *
     * @return void
     */
    public function list()
    {
        $poll = new PollModel();
        $result = $poll->getPoll();
        $this->request->requestData['result'] = $result;
        $this->renderView('web/admin/list.php', $this->request->requestData);
    }
    
    /**
     * add
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->requestData && $this->request->isPOST()) {
            $poll = new PollModel();
            $poll->insert($this->request->requestData);
            UtilityHelper::redirect("?action=list&success=1");
        }
        $session = new SessionHelper();
        $session->generateCSRFToken();
        $action = "add";
        $this->request->requestData['action'] = $action;
        $this->renderView('web/admin/add_edit.php', $this->request->requestData);
    }
    
    /**
     * edit
     *
     * @return void
     */
    public function edit()
    {
        try {
            $action = "edit";
            $rid = 0;
            if (!isset($this->request->requestData['rid']) || !(\decrypt($this->request->requestData['rid']))) {
                throw new \Exception("Invalid result id");
            }
            $poll = new PollModel();
            $rid = \decrypt($this->request->requestData['rid']);
            if ($this->request->requestData && $this->request->isPOST()) {
                $action = "edit";
                $poll->update($this->request->requestData, $rid);
                UtilityHelper::redirect("?action=list&success=1");
            } else {
                $session = new SessionHelper();
                $session->generateCSRFToken();
                $result = $poll->getPollOptions($rid);
                foreach ($result as $key => $row) {
                    $pollData['id'] = $row['id'];
                    $pollData['expiration_date'] = $row['expiration_date'];
                    $pollData['question'] = $row['question'];
                    $pollData['options'][] = array_diff_key($row, $pollData);
                }
                $this->request->requestData['rid'] = $this->request->requestData['rid'];
                $this->request->requestData['result'] = $pollData;
            }
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }

        $this->renderView('web/admin/add_edit.php', $this->request->requestData);
    }
    
    /**
     * getTodaysPoll
     *
     * @return void
     */
    public function getTodaysPoll()
    {
        $this->request->requestData['voted'] = false;
        if ($this->request->requestData && $this->request->isPOST() && $this->request->requestData['id']) {
            $this->request->requestData['user_cookie'] = $_COOKIE['user_cookie'];
            $this->request->requestData['id'] = decrypt($this->request->requestData['id']);
            $this->request->requestData['option_id'] = decrypt($this->request->requestData['option_id']);
            $poll = new PollModel();
            $result = $poll->checkVote($this->request->requestData);
            if (count($result)==0) {
                $poll->saveVote($this->request->requestData);
                $this->request->requestData['showResult'] = true;
            } else {
                $this->request->requestData['voted'] = true;
                $this->request->requestData['showResult'] = false;
            }

            $result = $poll->getPollOptions($this->request->requestData['id']);
            $vote = 0;
            foreach ($result as $key => $row) {
                $pollData['id'] = $row['question'];
                $pollData['expiration_date'] = $row['expiration_date'];
                $pollData['question'] = $row['question'];
                $pollData['totalVote'] = $vote += $row['vote_counter'];
                $pollData['options'][] = array_diff_key($row, $pollData);
            }
            $this->request->requestData['id'] = $this->request->requestData['id'];
            $this->request->requestData['result'] = $pollData;
        } else {
            if (!isset($_COOKIE['user_cookie']) || !$_COOKIE['user_cookie']) {
                setcookie("user_cookie", UtilityHelper::generateToken(), time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            $session = new SessionHelper();
            $session->generateCSRFToken();
            $poll = new PollModel();
            $todayPoll = $poll->getTodaysPoll();
            $result = [];
            if (count($todayPoll)>0) {
                $result = $poll->getPollOptions($todayPoll[0]['id']);
            }
            foreach ($result as $key => $row) {
                $pollData['id'] = $row['id'];
                $pollData['expiration_date'] = $row['expiration_date'];
                $pollData['question'] = $row['question'];
                $pollData['options'][] = array_diff_key($row, $pollData);
            }
            $this->request->requestData['action'] = "poll";
            $this->request->requestData['result'] = $pollData;
            $this->request->requestData['showResult'] = false;
        }
        $this->renderView('web/poll.php', $this->request->requestData);
    }
    
    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        $rid = 0;
        if (!isset($this->request->requestData['rid']) || !(\decrypt($this->request->requestData['rid']))) {
            throw new \Exception("Invalid result id");
        }
        $poll = new PollModel();
        $rid = \decrypt($this->request->requestData['rid']);
        $result = $poll->delete($rid);
        UtilityHelper::redirect("?action=list&success=1");
    }
}
