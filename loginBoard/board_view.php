<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-13
 * Time: 오전 11:56
 */

    @include('board_db_connect.php');

    // 확인할 글 번호 전달받기
    //if (isset($_GET['confirm_id'])) {
        $confirm_id = $_POST['confirm_id'];
    //}

    // 조회수
    if (!empty($confirm_id) && empty($_COOKIE['board'.$confirm_id])) {
        $hitsSql = "update board set hits=(hits + 1) where board_id=".$confirm_id;
        $hitsResult_query = mysql_query($hitsSql);

        if (empty($hitsResult_query)) {
            ?>
            <script>
                alert("오류가 발생했습니다.");
            </script>
            <?php
        }
        else {
            setcookie('board'.$confirm_id, TRUE, time() + (60 * 60 * 24));
        }
    }

    // 해당 번호의 글 정보 DB에서 가져오기
    $sql = "select user_name, subject, contents, hits, reg_date from board where board_id=".$confirm_id;
    $result_query = mysql_query($sql);

    $findInfo = @mysql_fetch_assoc($result_query);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>자유게시판</title>
        <style>
            body {
                margin : 10%;
            }
            .btn_table {
                margin-left : 580px;
            }
            .cmt_table {
                width : 720px;
            }
            .cmt_input {
                border : 0;
                background-color : white;
            }
            .cmt_input_table {
                border-top  : 1px solid #666;
                border-bottom : 1px solid #666;
                margin-top : 5px;
                margin-bottom : 5px;
            }
        </style>
    </head>
    <body>
        <h3>자유게시판</h3>
        <div>
            <h3 style="border-bottom : 2px solid #333; width : 720px;"><?php echo $findInfo['subject']?></h3>
            <div style="margin : 10px 0;">
                <span>작성자 : <?php echo $findInfo['user_name']?></span>
                <span>작성일 : <?php echo $findInfo['reg_date']?></span>
                <span>조회 : <?php echo $findInfo['hits']?></span>
            </div>
            <div style="margin : 10px 0;"><?php echo $findInfo['contents']?></div>
        </div>
        <table class="btn_table">
            <tr>
                <td><form action="board_write.php" method="POST">
                        <?php
                            if (isset($confirm_id)) {
                                echo "<input type='hidden' name='confirm_id' value='".$confirm_id."' />";
                            }
                        ?>
                        <input type="submit" value="수정" />
                </form></td>
                <td><form action="board_delete.php" method="POST">
                        <?php
                            if (isset($confirm_id)) {
                                echo "<input type='hidden' name='confirm_id' value='".$confirm_id."' />";
                            }
                        ?>
                        <input type="submit" value="삭제" />
                </form></td>
                <td><form action="board_list.php" method="POST">
                        <input type="submit" value="목록" />
                </form></td>
            </tr>
        </table>
        <table class="cmt_input_table" cellspacing="0" cellpadding="5">
            <tr>
                <form>
                    <td><input style="width : 640px; height : 65px;" type="text" id="comment" name="comment" value="" /></td>
                    <td><input style="width : 70px; height : 70px;" type="button" value="덧글입력" onclick="cmtWrite(<?php echo $confirm_id?>)" /></td>
                </form>
            </tr>
        </table>
        <table class="cmt_table" cellspacing="0" id="cmtTable">
            <?php
                $sql_comment = "select * from board where board_pid=".$confirm_id." order by reg_date";
                $comResult_query = mysql_query($sql_comment);

                $sql_pidCount = "select count(*) as cmt from board where board_pid=".$confirm_id;

                $cntResult_query = mysql_query($sql_pidCount);
                $pid_allCount = @mysql_num_rows($comResult_query);

                for ($k = 0 ; $k < $pid_allCount ; $k++) {
                    while ($row = mysql_fetch_assoc($comResult_query)) {

                        echo "<tr>";
                        echo "<td rowspan='2' style='border-bottom : 1px solid #666; width : 70px; height : 50px;'>".$row['user_name']."</td>";
                        echo "<td rowspan='2' style='border-bottom : 1px solid #666; width : 510px;'>".$row['contents']."</td>";
                        echo "<td style='font-size : small;'>".$row['reg_date']."</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='2' style='border-bottom : 1px solid #666; text-align : right;'>";
                        echo "<input class='cmt_input' type='button' value='수정' onclick='cmtChange(this.value)' />";
                        echo "<input class='cmt_input' type='button' value='삭제' onclick='cmtChange(this.value)' />";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </table>
        <br />
        <table>

        </table>
        <script>
            function cmtWrite (argId) {
                var xmlObj = new XMLHttpRequest();

                var cmt = document.getElementById("comment");

                xmlObj.onreadystatechange = function () {
                    if ((xmlObj.readyState == 4) && (xmlObj.status == 200)) {
                        /*document.getElementById("cmtTable").innerHTML = xmlObj.responseText;
                        cmt.value ="";*/

                        var cmtT = document.getElementById("cmtTable");

                        <?php
                        $sql_comment = "select * from board where board_pid=".$confirm_id." order by reg_date";
                        $comResult_query = mysql_query($sql_comment);

                        $pid_allCount = @mysql_num_rows($comResult_query);

                        while ($row = mysql_fetch_assoc($comResult_query)) {

                            echo "<tr>";
                            echo "<td rowspan='2' style='border-bottom : 1px solid #666; width : 70px; height : 50px;'>".$row['user_name']."</td>";
                            echo "<td rowspan='2' style='border-bottom : 1px solid #666; width : 510px;'>".$row['contents']."</td>";
                            echo "<td style='font-size : small;'>".$row['reg_date']."</td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td colspan='2' style='border-bottom : 1px solid #666; text-align : right;'>";
                            echo "<input class='cmt_input' type='button' value='수정' onclick='cmtChange(this.value)' />";
                            echo "<input class='cmt_input' type='button' value='삭제' onclick='cmtChange(this.value)' />";
                            echo "</td>";
                            echo "</tr>";
                        }

                        ?>

                    }
                };

                param = "mode='덧글입력'&confirm_id=" + argId + "&comment=" + cmt.value;

                var link = "board_view_update.php";
                xmlObj.open('POST', link, true);
                xmlObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
                xmlObj.send(param);
            }

            function cmtChange (argValue) {
                switch (argValue) {
                    case "수정":

                        break;
                    case "삭제":
                        <?php

                        ?>
                        break;
                }
            }
        </script>
    </body>
</html>
