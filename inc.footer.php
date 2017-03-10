</div>
<!-- end: content -->
</div>
<!-- end: wrapper -->
<!-- Ajax Loader -->
<div id="ajaxLoader"></div>
<!-- end: Ajax Loader -->
<!-- Javascript -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="asset/js/jquery.ui.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>
<!-- plugins -->
<script src="asset/js/plugins/moment.min.js"></script>
<script src="asset/js/plugins/jquery.validate.min.js"></script>
<script src="asset/datatables/jquery.dataTables.min.js"></script>
<script src="asset/datatables/dataTables.buttons.min.js"></script>
<script src="asset/datatables/buttons.html5.min.js"></script>
<script src="asset/datatables/jszip.min.js"></script>
<script src="asset/datatables/pdfmake.min.js"></script>
<script src="asset/datatables/vfs_fonts.js"></script>
<script src="asset/js/plugins/jquery.nicescroll.js"></script>
<script src="asset/flipclock/compiled/flipclock.js"></script>
<script src="bower_components/summernote/dist/summernote.js"></script>
<!-- custom -->
<script src="asset/js/main.js"></script>
<?php $seconds = strtotime($_SESSION['start_time'] . ' +1 hour') - strtotime(date('Y-m-d H:i:s')); ?>
<script type="text/javascript">
    $(document).ajaxStart(function () {
        $('#ajaxLoader').show();
    }).ajaxStop(function () {
        $('#ajaxLoader').hide();
    });

    $(document).ready(function () {
        /** Countdown Clock */
        var isAdmin = '<?= (isset($_SESSION['admin']) && $_SESSION['admin'] == true) ? 'true' : 'false' ?>';
        var seconds = '<?= $seconds ?>';
        var isSession = '<?= isset($_SESSION['registered']) ? 'true' : 'false' ?>';
        var clock, countup;

        if (isSession == 'true' && isAdmin == 'false' && seconds != '' && seconds <= 0) {
//            alert("Masa menjawab telah tamat!");
//            window.location.href = "logout.php";
        } else {
            if (isSession == 'true' && isAdmin == 'false') {
//                var clock = $('#clock').FlipClock(<?//= $seconds ?>//, {
//                    clockFace: 'MinuteCounter',
//                    countdown: true
//                });
//
//                countup = setInterval(function () {
//                    if (clock.getTime().time == 0) {
//                        setTimeout(function () {
//                            clock.stop();
//                            alert("Masa menjawab telah tamat!");
//                            window.location.href = "logout.php";
//                            clearInterval(countup);
//                        }, 1000);
//                    }
//                }, 2000);
            }
        }
        /** ./Countdown Clock */

        /** jQuery Validation */
        $("#formRegistration").validate({
            errorElement: "em",
            errorPlacement: function (error, element) {
                $(element.parent("div").parent("div").addClass("has-error"));
                error.appendTo(element.parent("div").addClass("text-danger"));
            },
            success: function (label) {
                $(label.parent("div").parent("div").removeClass("has-error"));
            },
            rules: {
                'register[name]': {
                    required: true,
                    minlength: 5
                },
                'register[email]': {
                    required: true,
                    email: true,
                },
                'register[question_set_id]': {
                    required: true
                }
            }
        });
        /** ./jQuery Validation */

        $('#nexttab').on('click', function (e) {
            e.preventDefault();
            setTimeout(function () {
                var toggleTab = $('.nav-tabs li').filter('.active').next('li').find('a[data-toggle="tab"]');
                toggleTab.tab('show');
                $('#questionNo').html(toggleTab.data('qno'));
                loadQuestion(toggleTab.data('qid'), toggleTab.data('jid'));
            }, 200);
        });

        $('.questionNumber').click(function () {
            $('#divNextButton').show();
        })

        $('#tabs-number-confirmation').click(function () {
            $('#divNextButton').hide();
        })

        $('#datatables').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 30, -1],
                ['10 row', '30 row', 'Show all']
            ],
            buttons: ['pageLength', {
                extend: 'excelHtml5',
                text: 'Export to XLSX'
            }]
        });

        $('.questionNumber').click(function (event) {
            event.preventDefault();
            $('#questionNo').html($(this).data('qno'));
            loadQuestion($(this).data('qid'), $(this).data('jid'));
        });

        $('.answer').click(function () {
            var idAnswer = $('#jawapan_id').val();
            var answer = $('input:radio[name="jawapan[]"]:checked').val();

            $.ajax({
                method: "POST",
                url: "ajax.answer.php",
                data: {jid: idAnswer, ans: answer},
                dataType: "json",
                success: function (result) {
                    if (result.status == 'ok')
                        $('#questionNumber-' + idAnswer).addClass('bg-lime');
                }
            });
        });

        $('#btnConfirmation').click(function () {
            alert("Terima kasih dan Semoga Berjaya untuk berkhidmat.");
            window.location.href = "logout.php";
        });

        $('#questionText').summernote({});
    });

    function loadQuestion(questionId, answerId) {
        $('.answer').prop("checked", false);
        $.ajax({
            method: "POST",
            url: "ajax.question.php",
            data: {qid: questionId, jid: answerId},
            dataType: "json",
            success: function (result) {
                $('#questText').text(result.q.question).html();
                $('#questA').text(result.q.option_a).html();
                $('#questB').text(result.q.option_b).html();
                $('#questC').text(result.q.option_c).html();
                $('#questD').text(result.q.option_d).html();
                $('#jawapan_id').val(result.a.question_id);
                $('input:radio[name="jawapan[]"]').val([result.a.answer]);
            }
        });
    }
</script>
<!-- ./Javascript -->
</body>
</html>