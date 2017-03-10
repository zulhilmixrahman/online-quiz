<?php
require_once 'inc.header.php';

if (!isset($_SESSION['admin']) || isset($_SESSION['admin']) && $_SESSION['admin'] == false)
    header("Location: index.php");

if (isset($_GET['id']) && isset($_GET['d'])) {
    $id = intval($_GET['id']);
    $delete = intval($_GET['d']);
    if ($delete === 1 && is_numeric($id)) {
        $delete = QuestionSet::find_one($id);
        if ($delete->delete()) {
            header("Location: admin.setquestion.php");
        }
    }
}
?>
    <div class="col-md-12 top-20 padding-0">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"><h3>Senarai Set Soalan</h3></div>
                <div class="panel-body">
                    <div class="responsive-table">
                        <div class="row">
                            <div class="col-sm-6 help-block text-primary"
                                 style="padding-bottom: 5px; font-style: italic;">
                                ** Pastikan hanya 1 set soalan yang berstatus aktif untuk penilaian peserta
                            </div>
                            <div class="col-sm-6" style="padding-bottom: 5px;">
                                <span class="pull-right">
                                    <a href="admin.setquestion.form.php" class="btn btn-success" data-toggle="tooltip"
                                       title="Tambah Set Soalan Baru">Tambah Set Soalan</a>
                                </span>
                            </div>
                        </div>
                        <table id="datatables" class="table table-striped table-bordered" width="100%"
                               cellspacing="0">
                            <thead>
                            <tr>
                                <th class="text-center col-md-1">#</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Status</th>
                                <th class="text-center col-md-3"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $bil = 1; ?>
                            <?php foreach (QuestionSet::find_many() as $set): ?>
                                <tr>
                                    <td class="text-center"><?= $bil++ ?></td>
                                    <td class="text-left"><?= $set->name ?></td>
                                    <td class="text-center"><?= ($set->active == 'true') ? 'Active' : 'Disable' ?></td>
                                    <td class="text-center">
                                        <a href="admin.question.php?set=<?= $set->id ?>"
                                           class="btn btn-sm btn-primary" data-toggle="tooltip" title="Senarai Soalan">
                                            <span class="fa fa-list"></span>
                                        </a>
                                        <a href="admin.setquestion.form.php?id=<?= $set->id ?>"
                                           class="btn btn-sm btn-warning" data-toggle="tooltip" title="Kemaskini">
                                            <span class="fa fa-edit"></span>
                                        </a>
                                        <a href="admin.setquestion.php?id=<?= $set->id ?>&d=1"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Adakah anda pasti untuk hapuskan set soalan ini?');"
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