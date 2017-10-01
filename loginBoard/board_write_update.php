<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-13
 * Time: 오전 11:21
 */

    // 1.0 DB 연결 및 선택
    // 1.1 DB 연결하는 php파일 불러오기 -> 1번만 불러와도 OK
    @include('board_db_connect.php');

    // 유저 아이디, 유저 이름 확인
    $userId = $_SESSION['userIn'];

    $nameSql = "select user_name from userlist where user_id='".$userId."'";
    $query = mysql_query($nameSql);

    $name = mysql_fetch_row($query);

    $userName = $name[0];



    if (isset($_POST['confirm_id'])) {
        $confirm_id = $_POST['confirm_id'];
    }

    if (empty($confirm_id)) {
        //작성 일자와 시간 구성
        $date = date('Y-m-d H:i:s');
    }

    // 글 제목과 내용 받아오기
    $title = $_POST['title'];
    $contents = $_POST['contents'];

    // 넘어온 글 번호가 있을 경우 기존 글 수정
    if (isset($confirm_id)) {
        $sql = "update board set subject='{$title}', contents='{$contents}' where board_id=".$confirm_id;
        $msg = "수정";
    }
    // 넘어온 글 번호가 없을 경우 새로운 글 등록
    else {
        $sql = "insert into board (board_pid, user_id, user_name, subject, contents, hits, reg_date) values";
        $sql .= "(0, '".$userId."', '".$userName."', '".$title."', '".$contents."', 0, '".$date."')";
        $msg = "등록";
    }

    // 실행
    $result_query = mysql_query($sql);

    if ($msg == "수정") {
        $returnLink = "board_view.php?confirm_id=".$confirm_id;
    }
    else {
        // 작성 후 실행 화면
        // 방금 작성 or 수정한 글 실행 -> 방금 작업한 글의 변호 확인 필요
        $findIdSql = "select board_id from board order by board_id desc";
        $findIdResult_query = mysql_query($findIdSql);

        // board_id가 제일 큰 게 최신 글
        $findId = @mysql_fetch_row($findIdResult_query);

        $returnLink = "board_view.php?confirm_id=".$findId[0];
    }

    // 쿼리 실행 결과
    if ($result_query) {
        $notice = "글이 ".$msg."되었습니다.";
    }
    else {
        $notice = "글이 ".$msg."되지 않았습니다.";
    }
?>
<script>
    alert("<?php echo $notice?>");
    location.replace("<?php echo $returnLink?>");
</script>
