<?php
require_once 'inc.header.php';

if (!isset($_SESSION['admin']) || isset($_SESSION['admin']) && $_SESSION['admin'] == false)
    header("Location: index.php");

$id = null;
if (isset($_GET['id'])) {
    $id = intval(strip_tags($_GET['id']));
}

if ($id <> null) {
//    $set = $setQuestionObj->getSet($id);
    $set = QuestionSet::find_one($id);
}else
    $set = null;

if (isset($_POST['q'])) {
    $validate = new Valitron\Validator($_POST['q']);
    $validate->rule('required', 'name')
        ->message('<b><u>{field}</u></b> tidak boleh dibiarkan kosong.')
        ->label('Soalan');
    $validate->rule('required', 'active')
        ->message('<b><u>{field}</u></b> tidak boleh dibiarkan kosong.')
        ->label('Status');

    $save = false;
    if ($validate->validate()) {
        if ($set <> null) {
//            $save = $setQuestionObj->update($id, $_POST['q']);
            $update = QuestionSet::find_one($id);
            $update->name = $_POST['q']['name'];
            $update->active = $_POST['q']['active'];
            $update->save();
        } else {
//            $save = $setQuestionObj->insert($_POST['q']);
            $create = QuestionSet::create;
            $create->name = $_POST['q']['name'];
            $create->active = $_POST['q']['active'];
            $create->save();
        }
    } else {
        $formErrors = $validate->errors();
    }

    if ($save) {
        header("Location: admin.setquestion.php");
    }
}
?>
    <div class="col-md-12 top-20 padding-0">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"><h3><?= ($set <> null) ? 'Kemaskini' : 'Tambah' ?> Set</h3></div>
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
                                <input type="text" name="q[name]" class="form-control input-lg"
                                       value="<?= isset($_POST['q']) ? $_POST['q']['name'] : $set->name ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10">
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[active]" value="true"
                                        <?= ((isset($_POST['q']) && $_POST['q']['active'] == 'true') || $set->active == 'true') ? 'checked' : '' ?>
                                    >Aktif
                                </label>
                                <label class="radio-inline input-lg">
                                    <input type="radio" name="q[active]" value="false"
                                        <?= ((isset($_POST['q']) && $_POST['q']['active'] == 'false') || $set->active == 'false') ? 'checked' : '' ?>
                                    l>Tidak
                                    Aktif
                                </label>
                            </div>
                        </div>
                        <div class="row form-group text-center">
                            <a href="admin.setquestion.php" class="btn btn-warning btn-lg">Kembali</a>
                            <button type="submit" class="btn btn-success btn-lg">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'inc.footer.php';
