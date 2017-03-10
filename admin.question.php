<?php
require_once 'inc.header.php';

if (!isset($_SESSION['admin']) || isset($_SESSION['admin']) && $_SESSION['admin'] == false)
    header("Location: index.php");

if (!isset($_GET['set']))
    header("Location: admin.setquestion.php");

$set = intval(strip_tags($_GET['set']));
$setQuestion = QuestionSet::find_one($set);

if (isset($_GET['id']) && isset($_GET['d'])) {
    $id = intval($_GET['id']);
    $delete = intval($_GET['d']);
    if ($delete === 1 && is_numeric($id)) {
        $delete = Question::find_one($id);
        if ($delete->delete()) {
            header("Location: admin.question.php?set=" . $set);
        }
    }
}
?>
    <div class="col-md-12 top-20 padding-0">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"><h3>Senarai Soalan: <?= $setQuestion->name ?></h3></div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <div class="row">
                            <div class="col-sm-12" style="padding-bottom: 5px;">
                                <span class="pull-right">
                                    <a href="admin.question.form.php?set=<?= $set ?>"
                                       class="btn btn-success" data-toggle="tooltip"
                                       title="Tambah Soalan Baru">Tambah Soalan</a>
                                </span>
                            </div>
                        </div>
                        <table id="datatables" class="table table-striped table-bordered" width="100%"
                               cellspacing="0">
                            <thead>
                            <tr>
                                <th class="text-center col-md-1">#</th>
                                <th class="text-center col-md-4">Soalan</th>
                                <th class="text-center">Pilihan A</th>
                                <th class="text-center">Pilihan B</th>
                                <th class="text-center">Pilihan C</th>
                                <th class="text-center">Pilihan D</th>
                                <th class="text-center col-md-2"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $bil = 1; ?>
                            <?php foreach (Question::where('question_set_id', $set)->find_many() as $question): ?>
                                <tr>
                                    <td class="text-center"><?= $bil++ ?></td>
                                    <td class="text-left"><strong><?= htmlspecialchars($question->question) ?></strong>
                                    </td>
                                    <td class="text-left <?= ($question->correct_answer == 'A') ? 'bg-info' : '' ?>">
                                        <?= htmlspecialchars($question->option_a) ?>
                                    </td>
                                    <td class="text-left <?= ($question->correct_answer == 'B') ? 'bg-info' : '' ?>">
                                        <?= htmlspecialchars($question->option_b) ?>
                                    </td>
                                    <td class="text-left <?= ($question->correct_answer == 'C') ? 'bg-info' : '' ?>">
                                        <?= htmlspecialchars($question->option_c) ?>
                                    </td>
                                    <td class="text-left <?= ($question->correct_answer == 'D') ? 'bg-info' : '' ?>">
                                        <?= htmlspecialchars($question->option_d) ?>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <a href="admin.question.form.php?set=<?= $set ?>&id=<?= $question->id ?>"
                                           class="btn btn-sm btn-warning" data-toggle="tooltip" title="Kemaskini">
                                            <span class="fa fa-edit"></span>
                                        </a>
                                        <a href="admin.question.php?set=<?= $set ?>&id=<?= $question->id ?>&d=1"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Adakah anda pasti untuk hapuskan soalan ini?');"
                                           data-toggle="tooltip" title="Hapus">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require_once 'inc.footer.php';