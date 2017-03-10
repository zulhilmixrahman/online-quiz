<?php
require_once 'inc.header.php';

if (!isset($_SESSION['admin']) || isset($_SESSION['admin']) && $_SESSION['admin'] == false)
    header("Location: index.php");

function getResult($userId, $setQuestion)
{
    $mark = 0;
    $questionSet = Question::where('question_set_id', $setQuestion)->find_many();
    $questionCount = Question::where('question_set_id', $setQuestion)->count();

    foreach ($questionSet as $q):
        $answerSet = Answer::where('student_id', $userId)->where('question_id', $q->id)->find_one();

        if ($answerSet->answer == $q->correct_answer)
            $mark++;
    endforeach;

    $percent = getPercent($mark, $questionCount);
    $grade = getGrade($percent);
    return ['mark' => $mark, 'question' => $questionCount, 'percent' => $percent, 'grade' => $grade];
}

function getPercent($mark, $total)
{
    return number_format((($mark / $total) * 100), 2);
}

function getGrade($percent)
{
    if ($percent <= 100 && $percent >= 80) {
        $grade = ['code' => 'A', 'note' => 'Cemerlang', 'color' => 'bg-light-green'];
    } else if ($percent <= 79 && $percent >= 70) {
        $grade = ['code' => 'B', 'note' => 'Baik', 'color' => 'bg-lime'];
    } else if ($percent <= 69 && $percent >= 60) {
        $grade = ['code' => 'C', 'note' => 'Memuaskan', 'color' => 'bg-amber'];
    } else if ($percent <= 59 && $percent >= 50) {
        $grade = ['code' => 'D', 'note' => 'Mencapai Tahap Minimum', 'color' => 'bg-orange'];
    } else if ($percent <= 49 && $percent >= 40) {
        $grade = ['code' => 'E', 'note' => 'Belum Mencapai Tahap Minimum', 'color' => 'bg-dark-orange'];
    } else if ($percent >= 0 && $percent <= 39) {
        $grade = ['code' => 'G', 'note' => 'Gagal', 'color' => 'bg-red'];
    } else {
        $grade = ['code' => 'UNKNOWN', 'note' => '', 'color' => ''];
    }
    return $grade;
}

?>
    <div class="col-md-12 top-20 padding-0">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"><h3>Result</h3></div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <table id="datatables" class="table table-striped table-bordered" width="100%"
                               cellspacing="0" data-order='[[4,"desc"]]'>
                            <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Mark</th>
                                <th class="text-center">Percent (%)</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (Student::find_many() as $participant): ?>
                                <?php $result = getResult($participant->id, $participant->question_set_id); ?>
                                <tr>
                                    <td class="text-left"><?= $participant->name ?></td>
                                    <td class="text-center">
                                        <?= $result['mark'] ?>/<?= $result['question'] ?>
                                    </td>
                                    <td class="text-center <?= $result['grade']['color'] ?>">
                                        <?= $result['percent'] ?>%
                                    </td>
                                    <td class="text-center <?= $result['grade']['color'] ?>">
                                        <?= $result['grade']['code'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?= date('d/m/Y, H:i A', strtotime($participant->created)) ?>
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

<?php require_once 'inc.footer.php'; ?>