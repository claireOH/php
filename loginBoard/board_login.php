<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-18
 * Time: 오후 3:37
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Login</title>
        <style>
            body {
                position : relative;
                margin : 10%;
            }
        </style>
    </head>
    <body>
        <h3>로그인</h3>
        <div>
            <form action="board_login_update.php" method="POST">
                <table>
                    <tr>
                        <td style="width : 50px; font-size : x-small; color : dodgerblue;">ID 입력</td>
                    </tr>
                    <tr>
                        <td style="width : 100px; border-bottom : 1px solid dodgerblue;"><input style="border : none; " type="text" value="" id="userId" name="userId" /></td>
                    </tr>
                    <tr>
                        <td style="height : 50px; font-size : x-small; color : dodgerblue;">비밀번호 입력</td>
                    </tr>
                    <tr>
                        <td style="width : 100px; border-bottom : 1px solid dodgerblue;"><input style="border : none;" type="password" value="" id="userPassword" name="userPassword" /></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="text-align : center;"><input type="submit" value="로그인" /></td>
                        <td style="text-align : center;"><input type="button" value="회원가입" onclick="siteJoin()"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
    <script>
        function siteJoin() {
            location.replace("board_join.php");
        }
    </script>
</html>
