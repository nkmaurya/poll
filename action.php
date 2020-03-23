<?php
use App\Helpers\RequestHelper;
use App\Controller\PollController;

$request = new RequestHelper();
$action = $request->getValueOf("action");

$poll = new PollController($request);

switch ($action) {
    case "add":
        $poll->add();
        break;
    case "edit":
        $poll->edit();
        break;
    case "delete":
        $poll->delete();
        break;
    case "poll":
        $poll->getTodaysPoll();
        break;
    default:
        $poll->list();
}
