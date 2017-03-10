<?php
require_once 'inc.header.php';

if (!isset($_SESSION['admin']) || isset($_SESSION['admin']) && $_SESSION['admin'] == false)
    header("Location: index.php");

if (!isset($_GET['set']))
    header("Location: admin.setquestion.php");

$set = intval(strip_tags($_GET['set']));
$id = null;
if (isset($_GET['id'])) {
    $id = intval(strip_tags($_GET['id']));
}

if ($id <> null) {
    $question = Question::find_one($id);
//    $question = $questionObj->getQuestion($id);
} else
    $question = null;

if (isset($_POST['q'])) {
    $validate = new Valitron\Validator($_POST['q']);
    $validate->rule('required', ['soalan_text', 'soalan_a', 'soalan_b', 'soalan_c', 'soalan_d', 'jawapan_betul'])
        ->message('<b><u>{field}</u></b> tidak boleh dibiarkan kosong.');
    $validate->labels([
        'soalan_text' => 'Soalan',
        'soalan_a' => 'Pilihan A',
        'soalan_b' => 'Pilihan B',
        'soalan_c' => 'Pilihan C',
        'soalan_d' => 'Pilihan D',
        'jawapan_betul' => 'Jawapan Betul'
    ]);

    if ($validate->validate()) {
        $save = false;
        if ($question <> null) {
            //$save = $questionObj->updateQuestion($id, $_POST['q']);
            $create = Question::find_one($id);
            $create->question = $_POST['q']['soalan_text'];
            $create->option_a = $_POST['q']['soalan_a'];
            $create->option_b = $_POST['q']['soalan_b'];
            $create->option_c = $_POST['q']['soalan_c'];
            $create->option_d = $_POST['q']['soalan_d'];
            $create->correct_answer = $_POST['q']['jawapan_betul'];
            $create->save();
        } else {
//            $save = $questionObj->insertQuestion($_POST['q']);
            $update = Question::create();
            $update->question = $_POST['q']['soalan_text'];
            $update->option_a = $_POST['q']['soalan_a'];
            $update->option_b = $_POST['q']['soalan_b'];
            $update->option_c = $_POST['q']['soalan_c'];
            $update->option_d = $_POST['q']['soalan_d'];
            $update->correct_answer = $_POST['q']['jawapan_betul'];
            $update->save();
        }

        if ($save) {
            if ($id <> null) {
                $question = Question::find_one($id);
//                $question = $questionObj->getQuestion($id);
            } else
                header("Location: admin.question.php?set=" . $set);
        }
    } else {
        $formErrors = $validate->errors();
    }
}
?>
    <div class="col-md-12 top-20 padding-0">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"><h3><?= ($question <> null) ? 'Kemaskini' : 'Tambah' ?> Soalan</h3></div>
                <div class="panel-body">

                    <?php if ($formErrors): ?>
                        <div class="alert alert-danger col-md-12 col-sm-12 alert-icon fade in" role="alert">
                            <div class="col-md-2 col-sm-2 icon-wrapper text-center">
                                <span class="fa fa-flash fa-2x"></span>
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <h4><strong>Ralat!</strong> Terdapat ralat seperti berikut</h4>
                                <?php echo '<ol>';
                                foreach ($formErrors as $errors):
                                    foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach;
                                endforeach;
                                echo '<ol/>'; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Soalan</label>
                            <div class="col-sm-10">
                                <textarea id="questionText" name="q[soalan_text]"
                                ><?= isset($_POST['q']) ? $_POST['q']['soalan_text'] : $question->question ?></textarea>
                                <input type="hidden" name="q[id_set]" value="<?= $set ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Pilihan A</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="q[soalan_a]"
                                ><?= isset($_POST['q']) ? $_POST['q']['soalan_a'] : $question->option_a ?></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Pilihan B</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="q[soalan_b]"
                                ><?= isset($_POST['q']) ? $_POST['q']['soalan_b'] : $question->option_b ?></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Pilihan C</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="q[soalan_c]"
                                ><?= isset($_POST['q']) ? $_POST['q']['soalan_c'] : $question->option_c ?></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Pilihan D</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="q[soalan_d]"
                                ><?= isset($_POST['q']) ? $_POST['q']['soalan_d'] : $question->option_d ?></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Jawapan Betul</label>
                            <div class="col-sm-10">
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[jawapan_betul]" value="A"
                                        <?= ((isset($_POST['q']) && $_POST['q']['jawapan_betul'] == 'A') || $question->correct_answer == 'A') ? 'checked' : '' ?>>A
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[jawapan_betul]" value="B"
                                        <?= ((isset($_POST['q']) && $_POST['q']['jawapan_betul'] == 'B') || $question->correct_answer == 'B') ? 'checked' : '' ?>>B
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[jawapan_betul]" value="C"
                                        <?= ((isset($_POST['q']) && $_POST['q']['jawapan_betul'] == 'C') || $question->correct_answer == 'C') ? 'checked' : '' ?>>C
                                </label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[jawapan_betul]" value="D"
                                        <?= ((isset($_POST['q']) && $_POST['q']['jawapan_betul'] == 'D') || $question->correct_answer == 'D') ? 'checked' : '' ?>>D
                                </label>
                            </div>
                        </div>
                        <div class="row form-group text-center">
                            <a href="admin.question.php?set=<?= $set ?>" class="btn btn-warning btn-lg">Kembali</a>
                            <button type="submit" class="btn btn-success btn-lg">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'inc.footer.php';
