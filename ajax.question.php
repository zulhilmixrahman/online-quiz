<?php
require_once 'lib/index.php';
$idSoalan = $_POST['qid'];
$idJawapan = $_POST['jid'];

$validate = new Valitron\Validator(['q' => $idSoalan, 'j' => $idJawapan]);
$validate->rule('required', ['q', 'j']);
$validate->rule('integer', ['q', 'j']);

if ($validate->validate()) {
    $soalan = Question::find_one($idSoalan);
    $jawapan = Answer::find_one($idJawapan);
//    Kint::dump($soalan->as_array());
    echo json_encode(['q' => $soalan->as_array(), 'a' => $jawapan->as_array()]);
} else {
    echo json_encode(['q' => 'UNKNOWN', 'a' => 'UNKNOWN']);
}