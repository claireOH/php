<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-14
 * Time: 오전 11:10
 */

    @include('board_db_connect.php');

    // 유저 아이디, 유저 이름 확인
    $userId = $_SESSION['userIn'];
    $nameSql = "select user_name from userlist where user_id='".$userId."'";

    $name = mysql_fetch_row(mysql_query($nameSql));
    $userName = $name[0];

    // 댓글달 글번호 및 댓글 내용 전송
    $confirm_id = $_POST['confirm_id'];
    $comment = $_POST['comment'];

    // 댓글 작성 일시
    $date = date('Y-m-d H:i:s');

    // DB 삽입
    $sql = "insert into board (board_pid, user_id, user_name, subject, contents, hits, reg_date) values";
    $sql .= "(".$confirm_id.", '".$userId."', '".$userName."', 'comment', '".$comment."', 0, '".$date."')";

    $result_query = mysql_query($sql);

    // DB 등록 성공 시
    if ($result_query) {
        // 해당 글의 댓글 모두 선택
        $sql_comment = "select * from board where board_pid=".$confirm_id." order by reg_date";
        $comResult_query = mysql_query($sql_comment);

        // 댓글 개수
        $sql_pidCount = "select count(*) as cmt from board where board_pid=".$confirm_id;

        $cntResult_query = mysql_query($sql_pidCount);
        $pid_allCount = mysql_num_rows($comResult_query);

        for ($k = 0 ; $k < $pid_allCount ; $k++) {
            while ($row = mysql_fetch_assoc($comResult_query)) {


            }
        }
    }

?>