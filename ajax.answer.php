<?php
require_once 'lib/index.php';
$jid = $_POST['jid'];
$ans = $_POST['ans'];

$validate = new Valitron\Validator(['j' => $jid, 'a' => $ans]);
$validate->rule('required', ['j', 'a']);
$validate->rule('integer', ['j']);

if ($validate->validate()) {
    $model = Answer::find_one($jid);
    $model->answer = $ans;
    $updated = $model->save();

    echo json_encode(['status' => $updated ? 'ok' : 'error']);
} else {
    echo json_encode(['status' => 'validate failed']);
}