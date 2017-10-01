<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-21
 * Time: 오전 9:22
 */

    session_start();

    // DB연결
    @include('board_db_connect.php');

    // ID와 PASSWD 받아오기
    // ID나 PASSWD 중 하나를 입력하지 않으면 경고창 생성
    if (!empty($_POST['userId']) && !empty($_POST['userPassword'])) {
        $userId = $_POST['userId'];
        $userPwd = $_POST['userPassword'];
    }
    else {
        ?>
        <script>
            alert("아이디 또는 비밀번호를 다시 확인하세요.");
            history.back();
        </script>
    <?php
    }

    // 입력받은 id와 passwd DB 검색
    $sql = "select password, user_name from userlist where user_id='".$userId."'";
    $result_query = mysql_query($sql);

    if ($result_query) {
        $userInfo = mysql_fetch_row($result_query);

        // 해당 ID와 passwd가 일치한지 여부 확인
        if ($userInfo[0] == $userPwd) {
            $_SESSION['userIn'] = $userId;

            $loginNotice = "로그인이 완료되었습니다.";
            $link = "board_list.php";

        }
        else {
            $loginNotice = "등록되지 않은 아이디이거나, 아이디 또는 비밀번호를 잘못 입력하셨습니다.";
            $link = "board_login.php";
        }
    }
    else {
        $loginNotice = "등록되지 않은 아이디이거나, 아이디 또는 비밀번호를 잘못 입력하셨습니다.";
        $link = "board_login.php"  ;
    }

?>
<script>
    alert("<?php echo $loginNotice?>");
    location.replace("<?php echo $link?>");
</script>
