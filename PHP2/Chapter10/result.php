﻿<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" 
content="text/html; charset=utf-8" />
<title>Market To Home</title>
<style type="text/css">
<!--
a {
	color: #3399FF
}
.topmenu {
	font-family: Arial, Helvetica, sans-serif;
	font-style: normal;
	color: #FFFFFF;
}
.style1 {color: #CC6600}
-->
</style>
<script langauge=javascript>
	function searchpage(page)
	{
		document.search.action=page;
		document.search.submit();
	}
</script>
</head>

<body bottomMargin=0 leftMargin=0 topMargin=0 rightMargin=0>
<div align="center">
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="3" valign="top"><?php require("top.php");?></td>
    </tr>
    <tr>
      <td width="200"  valign="top"><?php require("left.php");?></td>
      <td width="548"  valign="top">
		<?php
        
		$keyword ="";
		$category = "";
		$author="";
		$publisher="";
		$readertype="";
		$language = "";
		$minprice = "";
		$maxprice="";
		
		$whereclause= "";
        $searchpage = "";
		if(isset($_POST["searchfrom"]))
			 $searchpage = $_POST["searchfrom"];
        switch($searchpage)
        {
            case "quick":
                $keyword = $_POST["keyword"];
                if($keyword !="")
                {
                    $whereclause = " and (ItemName like '%";
                    $whereclause.=replace($keyword);
                    $whereclause.="%' or Description like '%";
                    $whereclause.=replace($keyword)."%') ";
                }
                $category=$_POST["category"];
                if($category !="")
                    $whereclause.= " and CategoryId='".$category."' ";
                    
                break;
            case "basic":
                $keyword = $_POST["keyword"];
                if($keyword !="")
                {
                    $whereclause = " and (ItemName like '%";
                    $whereclause.=replace($keyword);
                    $whereclause.="%' or Description like '%";
                    $whereclause.=replace($keyword)."%') ";
                }
                $category=$_POST["category"];
                if($category !="")
                    $whereclause.= " and CategoryId='".$category."' ";
                    
                $author=$_POST["author"];
                if($author !="")
                    $whereclause.= " and AuthorId='".$author."' ";
                    
                $publisher=$_POST["publisher"];
                if($publisher !="")
                    $whereclause.= " and PublisherId='".$publisher."' ";
                break;
            case "advanced":
                $keyword = $_POST["keyword"];
                if($keyword !="")
                {
                    $whereclause = " and (ItemName like '%";
                    $whereclause.=replace($keyword);
                    $whereclause.="%' or Description like '%";
                    $whereclause.=replace($keyword)."%') ";
                }
                $category=$_POST["category"];
                if($category !="")
                    $whereclause.= " and CategoryId='".$category."' ";
                    
                $author=$_POST["author"];
                if($author !="")
                    $whereclause.= " and AuthorId='".$author."' ";
                    
                $publisher=$_POST["publisher"];
                if($publisher !="")
                    $whereclause.= " and PublisherId='".$publisher."' ";
                    
                $readertype=$_POST["readertype"];
                if($readertype !="")
                    $whereclause.= " and ReaderId='".$readertype."' ";
                    
                $language=$_POST["language"];
                if($language !="")
                    $whereclause.= " and LanguageId='".$language."' ";
                
                $minprice=$_POST["minprice"];
                if($minprice !="")
                    $whereclause.= " and Price >='".$minprice."' ";
                
                $maxprice=$_POST["maxprice"];
                if($maxprice !="")
                    $whereclause.= " and Price <='".$maxprice."' ";
                        
                break;
        }
        ?>
        <br>
        KẾT QUẢ TÌM KIẾM
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
        <?php
        require("connection.ini");
		
		//varaibles
		$pageSize = 4;
		$pageCount = 2;
		$currentPage = 1;
		
		$totalRecords = 0;
		$totalPages = 0;
		$currentRange = 1;
		
		if(isset($_GET["currentpage"]))
			$currentPage = $_GET["currentpage"];
		if( $currentPage<=0 || $currentPage =="" || !is_numeric ($currentPage))
			$currentPage = 1;
		
		//echo  "<br>currentPage: ".$currentPage;
		
		//get total records
		
		$select = "select count(*) as Numbers from Items where 1=1 ".$whereclause;
		$result = mysql_query($select, $connect);
		if($row = mysql_fetch_array($result)) 
			$totalRecords=$row["Numbers"];
		//echo "<br>totalRecords: ".$totalRecords;
		
		//calculate pages
		$totalPages = int_divide ($totalRecords, $pageSize);
		if($totalRecords % $pageSize>0) $totalPages++;
		//echo "<br>totalPages: ". $totalPages;
		
		//segment
		$currentRange = int_divide($currentPage, $pageCount);
		if($currentPage % $pageCount>0)
			$currentRange++;
		//echo "<br>segment: ". $currentRange;
		
		//create navigation link
		$startpage = $currentRange*$pageCount-1;
		$endpage = $startpage+$pageCount-1;
		
		if($endpage>$totalPages) $endpage=$totalPages;
		//echo "<br>startpage: ". $startpage;
		//echo "<br>endpage: ". $endpage;
		
		$navigation = "";
		$i = 0;
		
		
		//previous
		if($startpage>1)
		{
			$navigation.="<a href=\"javascript:searchpage('result.php?currentpage=1');\">First</a> &nbsp;" ;
			$navigation.="<a href='\"javascript:searchpage('result.php?currentpage=".($startpage-1)."')\">Previoust</a> &nbsp;" ;
		}
		
		//numeric
		for($i=$startpage; $i<=$endpage ; $i++)	
		{
			$navigation.="<a href=\"javascript:searchpage('result.php?currentpage=".$i."');\">".$i."</a> &nbsp;";		 
		}
		//next
		if($totalPages>$endpage)
		{
			$navigation.="<a href=\"javascript:searchpage('result.php?currentpage=".($i)."');\">Next</a> &nbsp;";
			$navigation.="<a href=\"javascript:searchpage('result.php?currentpage=".$totalPages."');\">Last</a> &nbsp;";
		}

		$startRecord = ($currentPage-1)*$pageSize;
		
		//$select = "select * from Items where 1=1 ".$whereclause
        $select = "select * from Items where 1=1 ".$whereclause ." limit ".$startRecord.",".$pageSize ;
        //echo $select;
        $result = mysql_query($select, $connect);
        $rows = 0;
        if($result!=null)
        {
            $rows = mysql_num_rows($result);
        	if( $rows>0)
			{
            $i=0;
            while($row = mysql_fetch_array($result))
             {
                if($i==0)
                {
                    echo "<td valign=top width='50%' >";
                    echo "<table width='100%' border='0'";
                    echo " cellspacing='2' cellpadding='2'>";
                }
                if( int_divide($rows,2)==$i)
                {
                    echo "</table></td>";
                    echo "<td  valign=top width='50%' >";
                    echo "<table width='100%' border='0' ";
                    echo "cellspacing='2' cellpadding='2'>";
                }
                echo "<tr><td rowspan=4 valign='top'>";
                echo "<img width=80px height=100px src='books/";
                echo $row["ItemId"].".jpg'></td>";
                echo "<td valign='top'><a href='details.php?id=";
                echo $row["ItemId"]. "'>";
                echo $row["ItemName"] ; 
                echo "</a></td></tr>";
                echo "<tr><td valign='top'>Price: ";
                echo $row["Price"] . "</td></tr>";
                echo "<tr><td valign='top'>Size: ";
                echo $row["Size"] . "</td></tr>";
                echo "<tr><td valign='top'>Weight: ";
                echo $row["Weight"] . "</td></tr>";
                $i++;
            }  
           echo "</table></td></tr>";
            echo "<tr><td valign='top'><b>";
			echo "<form name=search method=post ";
			echo "action='result.php'>";
			echo $navigation;
			echo "<input name=searchfrom value='";
			echo $searchpage."' type='hidden'>";
			echo "<input name=keyword value='";
			echo $keyword."' type=hidden>";
			echo "<input name=category value='";
			echo $category."' type=hidden>";
			echo "<input name=publisher value='";
			echo $publisher."' type=hidden>";
			echo "<input name=author value='";
			echo $author."' type=hidden>";
			echo "<input name=readertype value='";
			echo $readertype."' type=hidden>";
			echo "<input name=language value='";
			echo $language."' type=hidden>";
			echo "<input name=minprice value='";
			echo $minprice."' type=hidden>";
			echo "<input name=maxprice value='";
			echo $maxprice."' type=hidden>";
			echo "</form>";
            echo "Số mẩu tin tìm thấy: ".$totalRecords."</b></td></tr>";
        }
        else
            echo "<td>Không tìm thấy mẩu tin thoả tiêu chí tìm kiếm.</td></tr>";
        }
        require("closeconnection.ini");
        ?>
          <!--end body-->
          </table>
      </td>
      <td width="242"  valign="top"><?php require("right.php");?></td>
    </tr>
    <tr>
      <td colspan="3"  valign="top"><?php include("bottom.htm");?></td>
    </tr>
  </table>
</div>
</body>
</html>

<?php
function int_divide($x, $y) {
    if ($x == 0) return 0;
    if ($y == 0) return FALSE;
    $result = $x/$y;
    $pos = strpos($result, '.');
    if (!$pos) {
        return $result;
    } else {
        return (int) substr($result, 0, $pos);
    }
} 
function replace($strtoreplace)
{
	return str_replace("'","''",$strtoreplace);
}
?>