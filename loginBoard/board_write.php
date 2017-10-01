<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-13
 * Time: 오전 10:43
 */

    // 1.0 DB 연결 및 선택
    // 1.1 DB 연결하는 php파일 불러오기 -> 1번만 불러와도 OK
    @include_once('board_db_connect.php');

    // 2.0 글 번호 확인
    // 2.1 새로운 글인지, 기존 글 수정인지 확인 필요
    if (isset($_POST['confirm_id'])) {
        $confirm_id = $_POST['confirm_id'];
    }

    // 2.2 수정일 경우, 수정할 기존 글의 confirm_id 필요
    if (isset($confirm_id)) {
        // 수정은 자신의 글만 수정할 수 있음
        $postInfoSql = "select user_id from board where board_id=".$confirm_id;
        $PostInfo = mysql_fetch_row(mysql_query($postInfoSql));

        if ($PostInfo[0] == $_SESSION['userIn']) {
            $sql = "select subject, contents from board where board_id=".$confirm_id;

            $result_query = mysql_query($sql);
            $findInfo = mysql_fetch_assoc($result_query);
        }
        else {
            // 유저 아이디, 유저 이름 확인
            $userId = $_SESSION['userIn'];
            $nameSql = "select user_name from userlist where user_id='".$userId."'";
            $query = mysql_query($nameSql);

            $name = mysql_fetch_row($query);

            $userName = $name[0];

            ?>
            <script>
                alert("<?php echo $userName?>님이 작성한 글이 아닙니다. 수정할 수 없습니다.");
                location.replace("board_list.php")
            </script>
        <?php

        }

    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Write</title>
        <style>
            body {
                position : relative;
                margin : 10%;
            }
            th {
                width : 100px;
                padding : 5px;
                text-align : right;
                vertical-align : top;
            }
            td {
                width : 620px;
                padding : 5px;
            }
            #btnBox {
                text-align : center;
            }
        </style>
    </head>
    <body>
        <h3>글 쓰기</h3>
        <div id="board_write">
            <form action="board_write_update.php" method="POST">
                <?php
                    if (isset($confirm_id)) {
                        echo "<input type='hidden' name='confirm_id' value='".$confirm_id."'/>";
                    }
                ?>
                <table style="width : 720px;">
                    <tr>
                        <th>제목</th>
                        <td><input style="width : 550px;" type="text" name="title" id="title"
                            value="<?php echo isset($findInfo['subject'])?$findInfo['subject']:null ?>"/></td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td><textarea style="width : 550px; height : 300px;" name="contents" id="contents"><?php echo isset($findInfo['contents'])?$findInfo['contents']:null?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" id="btnBox">
                            <input type="submit" value="<?php echo isset($confirm_id)?'수정':'작성'?>" />
                            <input type="button" value="취소" onclick="pageBack()" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <script>
            function pageBack() {
                history.back();
            }
        </script>
    </body>
</html>
