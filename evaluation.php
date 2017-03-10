<?php
require_once 'inc.header.php';
$jid = isset($_GET['jid']) ? $_GET['jid'] : 0;

$q = Answer::where('student_id', $_SESSION['userid']);
$jumlahJawapan = $q->count();
$semuaJawapan = Answer::where('student_id', $_SESSION['userid'])->find_many();
$jawapanPertama = $q->find_one();
$soalan = Question::find_one($jawapanPertama->id);
$jawapan = null;
$firstRegisiter = $_SESSION['first_register'];
?>
    <div class="col-md-12">
        <div class="panel form-element-padding">
            <div class="panel-body">
                <!-- Nav-tabs -->
                <ul class="nav nav-tabs nav-tabs-v2" role="tablist">
                    <?php $questionNumber = 1;
                    $i = 1; ?>
                    <?php foreach ($semuaJawapan as $noOfQuestion): ?>
                        <?php $currentAnswer = $noOfQuestion->answer; ?>
                        <li role="presentation" class="<?= ($i == 1) ? 'active ' : '' ?>">
                            <a href="#tabs-question"
                               id="questionNumber-<?= $noOfQuestion->id ?>"
                               class="questionNumber
                                <?= (!$firstRegisiter && $currentAnswer != null) ? 'bg-lime' : '' ?>"
                               data-qid="<?= $noOfQuestion->question_id ?>"
                               data-jid="<?= $noOfQuestion->id ?>"
                               data-qno="<?= $i ?>"
                               role="tab" data-toggle="tab">
                                <?= $i++ ?> / <?= $jumlahJawapan ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li role="presentation">
                        <a href="#tabs-confirmation" id="tabs-number-confirmation" role="tab"
                           data-toggle="tab">Confirmation</a>
                    </li>
                </ul>
                <!-- ./Nav-tabs -->

                <!-- Tab-content Question -->
                <div class="tab-content tabs-content-v2">
                    <div id="tabs-question" role="tabpanel" class="tab-pane fade active in">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-offset-2 col-md-8"><h3>Question <span id="questionNo">1</span></h3>
                                </div>
                            </div>
                            <div class="row">
                                <form name="evaluateForm">
                                    <div class="col-md-offset-2 col-md-8">
                                        <input type="hidden" id="jawapan_id" name="jawapan_id"
                                               value="<?= $jawapanPertama->id ?>">
                                        <p id="questText"><?= $soalan->question ?></p><br/>
                                        <ul type="">
                                            <li>
                                                <label>
                                                    <input type="radio" name="jawapan[]" value="A" class="answer"
                                                        <?= $jawapanPertama->answer == 'A' ? 'checked' : '' ?>>
                                                    &nbsp;A:&nbsp;<span id="questA"><?= $soalan->option_a ?></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label>
                                                    <input type="radio" name="jawapan[]" value="B" class="answer"
                                                        <?= $jawapanPertama->answer == 'B' ? 'checked' : '' ?>>
                                                    &nbsp;B:&nbsp;<span id="questB"><?= $soalan->option_b ?></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label>
                                                    <input type="radio" name="jawapan[]" value="C" class="answer"
                                                        <?= $jawapanPertama->answer == 'C' ? 'checked' : '' ?>>
                                                    &nbsp;C:&nbsp;<span id="questC"><?= $soalan->option_c ?></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label>
                                                    <input type="radio" name="jawapan[]" value="D" class="answer"
                                                        <?= $jawapanPertama->answer == 'D' ? 'checked' : '' ?>>
                                                    &nbsp;D:&nbsp;<span id="questD"><?= $soalan->option_d ?></span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="tabs-confirmation" role="tabpanel" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <h3>
                                    Are you sure?<br/>
<!--                                    IF <strong>YES</strong>, Tekan butang "Hantar" untuk hantar jawapan anda.-->
                                </h3>
                                <button id="btnConfirmation" type="button" class="btn btn-success btn-lg">Confirm
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ./Tab-content Question -->

                <!-- Tab-content Confirmation -->
                <div class="tabs-content-v2">
                    <div class="row" style="padding: 20px;">
                        <div id="divNextButton" class="col-md-offset-2 col-md-8 text-right">
                            <button type="button" id="nexttab" class="btn btn-primary btn-lg">
                                Next <span class="fa fa-angle-double-right"></span>
                            </button>
                        </div>
                    </div>
<!--                    <div class="row" style="padding-bottom: 10px;">-->
<!--                        <div id="divNextButton" class="col-md-offset-2 col-md-8 text-left">-->
<!--                            <span class="text-primary" style="font-style: italic;">-->
<!--                                * Soalan bertanda-->
<!--                                <span class="bg-lime">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
<!--                                adalah soalan yang telah dijawab-->
<!--                            </span>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <!-- ./Tab-content Confirmation -->
            </div>
        </div>
    </div>

<?php require_once 'inc.footer.php'; ?>