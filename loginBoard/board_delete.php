<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-18
 * Time: 오후 2:17
 */

    @include('board_db_connect.php');

    // 삭제할 글 번호 전달받기
    if (isset($_POST['confirm_id'])) {
        $confirm_id = $_POST['confirm_id'];
    }

    // 유저 아이디, 유저 이름 확인
    $userId = $_SESSION['userIn'];
    $nameSql = "select user_name from userlist where user_id='".$userId."'";
    $query = mysql_query($nameSql);

    $name = mysql_fetch_row($query);

    $userName = $name[0];

    // 자기 글만 삭제 가능
    $postInfoSql = "select user_id from board where confirm_id=".$confirm_id;
    $postInfo = mysql_fetch_row(mysql_query($postInfoSql));

    if ($postInfo[0] != $userId) {
        ?>
        <script>
            alert("<?php echo $userName?>님이 작성한 글이 아닙니다. 삭제할 수 없습니다.");
            location.replace("board_list.php");
        </script>
    <?php
    }
    else {
        // sql
        $sql = "delete from board where board_id=".$confirm_id;
        $result_query = mysql_query($sql);

        // board_list.php로 복귀
        if ($result_query) {
            $delNotice = "해당 글이 삭제되었습니다.";
            $delLink = "board_list.php";
        }
        else {
            $delNotice = "삭제할 글이 존재하지 않습니다.";
        }
    }


?>
<script>
    alert("<?php echo $delNotice?>");
    location.replace("<?php echo $delLink?>");
</script>
