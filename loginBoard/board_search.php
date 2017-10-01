<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-24
 * Time: 오후 3:47
 */

    @include_once('board_db_connect.php');

    // 검색 조건과 내용 추출
    $key = $_GET['keyword'];
    $option = $_GET['optionBox'];

    if (isset($key) && isset($option)) {
        $searchSql = $option." like '%".$key."%'";
    }
    else {
        exit;
    }

    // 유저 아이디, 유저 이름 확인
    $userId = $_SESSION['userIn'];
    $nameSql = "select user_name from userlist where user_id='".$userId."'";

    $name = mysql_fetch_row(mysql_query($nameSql));
    $userName = $name[0];

    // 페이지번호 및 한페이지 게시글 개수
    if (isset($_GET['page'])) {
        $pageNum = $_GET['page'];
    }
    else {
        $pageNum = 1;
    }

    if (isset($_GET['list'])) {
        $list = $_GET['list'];
    }
    else {
        $list = 10;
    }

    $limit = ($pageNum - 1) * $list;

    //섹션 (한 섹션에 보여질 페이지 개수)
    $oneSectionPost = 5;

    // 현재 리스트의 섹션 구하기
    $section = ceil($pageNum / $oneSectionPost);

    // 현재 블럭에서 시작 페이지와 마지막 페이지
    $section_start = (($section - 1) * $oneSectionPost) + 1;
    $section_end = $section_start + $oneSectionPost - 1;

    // 전체 게시글 개수 확인
    $allPostCount_sql = "select count(*) as pocnt from board where".$searchSql;
    $pp = mysql_query($allPostCount_sql);
    $allPost_query = @mysql_fetch_row($pp);

    $allPost = $allPost_query[0];

    if (isset($allPost)) {
        $empty = '글이 존재하지 않습니다.';
    }
    else {
        // 전체 페이지 수
        $allPage = ceil($allPost / $list);

        // 마지막 섹션의 경우, 마지막 페이지 수가 마지막
        if ($section_end > $allPage) {
            $section_end = $allPage;
        }

        // 처음 페이지
        if ($pageNum <= 1) {
            echo "";
        }
        else {
            echo "<font size=2><a href='board_list.php?page=1'>≤</a></font>";
        }

        // 이전 페이지
        if ($section <= 1) {
            echo "";
        }
        else {
            echo "<font size=2><a href='board_list.php?page=".($section_start - 1)."'>＜</a></font>";
        }

        // 페이지
        for ($i = $section_start ; $i <= $section_end ; $i++) {
            // 출력할 페이지와 현재 페이지가 같으면 링크X
            if ($pageNum == $i) {
                echo "<font size=2>".$i."</font>";
            }
            else {
                echo "<font size=2><a href='board_list.php?page=".$i."'>$i</a></font>";
            }
        }

        // 전체 섹션 수
        $allSection = ceil($allPage / $oneSectionPost);

        // 다음 페이지
        if ($section >= $allSection) {
            echo "";
        }
        else {
            echo "<font size=2><a href='board_list.php?page=".($section_end + 1)."'>＞</a></font>";
        }

        // 마지막 페이지
        if ($pageNum >= $allPage) {
            echo "";
        }
        else {
            echo "<font size=2><a href='board_list.php?page=".$allPage."'>≥</a></font>";
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>List</title>
    <style>
        body {
            position : relative;
            margin : 10%;
        }
        table {
            width : 720px;
        }
        th {
            padding : 5px 0;
            border-top : 2px solid #666;
            border-bottom : 2px solid #666;
        }
        td {
            padding : 8px 0;
            border-bottom : 1px solid #666;
        }
        .page_table {
            text-align : center;
            margin-top : 5px;
        }
        .page_table : input {
            border : 0;
            margin-right : 3px;
            background-color : white;
        }
    </style>
</head>
<body>
<form action="board_logout.php" method="POST">
    <p style="text-align : right;"><?php echo $userName?> 님&nbsp;&nbsp;&nbsp;<input type="submit" value="로그아웃"/></p>
</form>
<h3>자유 게시판</h3>
<table id="listTable">
    <tr>
        <th style="width : 60px; text-align : center;">번호</th>
        <th style="text-align : center;">제목</th>
        <th style="width : 100px; text-align : center;">작성자</th>
        <th style="width : 100px; text-align : center;">작성일</th>
        <th style="width : 40px; text-align : center;">조회</th>
    </tr>
    <?php
    // 댓글 여부 판단 SQL
    $sql = "select * from board where board_pid = 0 &&".$searchSql." order by reg_date desc LIMIT ".$limit.", ".$list;
    $result_query = mysql_query($sql);

    // 댓글 아닌 글의 개수 확인
    $allCount = @mysql_num_rows($result_query);

    // 글 개수 확인 후 개수만큼 리스트업
    for ($i = 0 ; $i < $allCount ; $i++) {
        // 작성일 체크
        // 당일 글일 경우 시간, 아닐 경우 날짜
        while ($result_row = mysql_fetch_assoc($result_query)) {
            $datetime = explode(' ', $result_row['reg_date']);

            $date = $datetime[0];
            $time = $datetime[1];

            if ($date == DATE('Y-m-d')) {
                $result_row['reg_date'] = $time;
            }
            else {
                $result_row['reg_date'] = $date;
            }

            $confirm_id = $result_row['board_id'];
            ?>
            <tr>
                <td style="width : 60px; text-align : center;"><?php echo $confirm_id?></td>
                <td><form action="board_view.php" method="POST">
                        <?php
                        if (isset($confirm_id)) {
                            echo "<input type='hidden' name='confirm_id' value='".$confirm_id."' />";
                        }
                        ?>
                        <input style="border : 0; background-color : white; font-size : medium;" type="submit" value="<?php echo $result_row['subject']?>" />
                    </form></td>
                <td style="width : 100px; text-align : center;"><?php echo $result_row['user_name']?></td>
                <td style="width : 100px; text-align : center;"><?php echo $result_row['reg_date']?></td>
                <td style="width : 40px; text-align : center;"><?php echo $result_row['hits']?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <td colspan="5" style="text-align : right; border-bottom : 2px solid #666">
            <form action="board_write.php" method="POST">
                <input type="submit" value="글쓰기" />
            </form>
        </td>
    </tr>
    <tr style="border-top : none;">
        <td colspan="5" style="text-align : right; border-bottom : 2px solid #666">
            <form action="board_list.php" method="POST">
                <input type="submit" value="목록" />
            </form>
        </td>
    </tr>
</table>
