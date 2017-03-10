<?php
require_once 'inc.header.php';

//If session is started
if (isset($_SESSION['registered']) && $_SESSION['registered'] == true)
    header('Location: ' . $serverURL . '/evaluation.php');

function createAnswers($studentId, $setId)
{
    foreach (Question::where('question_set_id', $setId)->find_many() as $question) {
        $answer = Answer::create();
        $answer->student_id = $studentId;
        $answer->question_id = $question->id;
        $answer->save();
    }
    return true;
}

//If register form is submited, create session then redirect
if (isset($_POST['register'])) {
    $formErrors = false;

    $query = Student::where('email', $_POST['register']['email'])
        ->where('question_set_id', $_POST['register']['question_set_id']);
    $checkPeserta = $query->find_one();

    if ($query->count() == 0) {
        $validate = new Valitron\Validator([
            'name' => $_POST['register']['name'],
            'email' => $_POST['register']['email'],
            'question_set_id' => $_POST['register']['question_set_id']
        ]);
        $validate->rule('required', ['name', 'question_set_id']);
        $validate->rule('integer', 'question_set_id');
        $validate->rule('email', 'email');
        $validate->labels(['name' => 'Name', 'question_set_id' => 'Set Question', 'email' => 'Emel']);

        if ($validate->validate()) {
            $peserta = Student::create();
            $peserta->name = $_POST['register']['name'];
            $peserta->email = $_POST['register']['email'];
            $peserta->question_set_id = $_POST['register']['question_set_id'];

            if ($peserta->save()) {
                $_SESSION['registered'] = true;
                $_SESSION['admin'] = false;
                $_SESSION['userid'] = $peserta->id;
                $_SESSION['fullname'] = $peserta->name;
                $_SESSION['email'] = $peserta->email;
                $_SESSION['start_time'] = date("Y-m-d H:i:s");
                $_SESSION['first_register'] = true;

                if (createAnswers($peserta->id, $peserta->question_set_id))
                    header('Location: ' . $serverURL . '/evaluation.php');
            } else {
                echo "<script>console.log('Failed to add new participant!');</script>";
            }
        } else {
            $formErrors = $validate->errors();
        }
    } else if ($query->count() == 1) {
        $_SESSION['registered'] = true;
        $_SESSION['admin'] = false;
        $_SESSION['userid'] = $checkPeserta->id;
        $_SESSION['fullname'] = $checkPeserta->name;
        $_SESSION['email'] = $checkPeserta->email;
        $_SESSION['start_time'] = $checkPeserta->created;
        $_SESSION['first_register'] = false;
        header('Location: ' . $serverURL . '/evaluation.php');
    }
}
?>
    <div class="col-md-offset-2 col-md-8">
        <?php if ($isDoneAnswer): ?>
            <div class="alert alert-info col-md-12 col-sm-12 alert-icon fade in" role="alert">
                <div class="col-md-2 col-sm-2 icon-wrapper text-center">
                    <span class="fa fa-info fa-2x"></span>
                </div>
                <div class="col-md-10 col-sm-10">
                    <h4><strong>Makluman!</strong> Anda telah selesai menjawab semua soalan.</h4>
                </div>
            </div>
        <?php endif; ?>

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

        <form id="formRegistration" method="POST" novalidate="novalidate">
            <div class="panel form-element-padding">
                <div class="panel-heading text-center">
                    <h4>Maklumat Peserta</h4>
                </div>
                <div class="panel-body">
                    <div class="row form-group">
                        <label class="col-md-3 control-label text-right input-lg">Name
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" id="register[name]" name="register[name]"
                                   class="form-control input-lg" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-3 control-label text-right input-lg">Email
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" id="register[email]" name="register[email]"
                                   class="form-control input-lg" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-3 control-label text-right input-lg">
                            Set Question <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9 dropdown">
                            <select name="register[question_set_id]" class="form-control input-lg" required>
                                <option value="">-- Choose Set Question --</option>
                                <?php
                                foreach (QuestionSet::where('active', 'true')->find_many() as $setQuestion) {
                                    echo "<option value=\"{$setQuestion->id}\">{$setQuestion->name}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg">Hantar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php require_once 'inc.footer.php'; ?>