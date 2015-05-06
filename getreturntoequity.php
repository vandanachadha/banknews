<?php
/* get the data for the Return to Equity Ratio chart 
	query:
		net_income = income_totals->net_income
		equity = equity_totals->total_capital
		Ratio = net_income * 100 / equity
   author: 76East
   last modified on: 150506 for rounding of y-axis
*/
   include("db_connect.php");
   $q=$_GET["q"];
   $t=$_GET["d"];
     $array=array();
      $banks=explode(",",$q);
      $dates=explode(",",$t);
	  $countdates=count($dates);
	  $countbanks=count($banks); 
	 
	     if($countbanks > '1' && $countdates=='1' ){
		 for($bank=0;$bank<count($banks);$bank++){
		   $arr = "";
		$arr1=array();
			$sql_query = "select  b.bank_name , eq.total_capital , it.net_income from income_totals it LEFT JOIN equity_totals eq on 
			(eq.transaction_date=it.transaction_date and eq.bank_id='".$banks[$bank]."') left join banks b on
			(b.bank_id='".$banks[$bank]."')
			where it.transaction_date='".$t."' and it.bank_id='".$banks[$bank]."'";
			$result = mysql_query($sql_query);
			$num_rows = mysql_num_rows($result);
			if($num_rows>0){
				while($res = mysql_fetch_array($result)){
				$netincome=  floatval($res['net_income']/1000);
				$equity=  floatval($res['total_capital']/1000);
				
				if($equity==null || $equity==0 || $equity==''){
					$ratio=null;
				}
				else{
				$ratio=($netincome*100)/$equity;
				}
				$ty=   $res['bank_name'];
				array_push($arr1,round($ratio, 2));
				} 
			}
			else{ 
				array_push($arr1,null);
				$ty=null;
				
				}
			
			$stack=array($ty);
			foreach($arr1 as $s){
				array_push($stack,$s);
			}
			array_push($array,$stack);
			}
			  echo (json_encode($array));
	}

	else if($countbanks=='1' && $countdates > '1'){
	  for($date=0;$date<count($dates);$date++){
	    $arr = "";
		$arr1=array();
		  $sql_query = "select it.transaction_date,eq.total_capital,it.net_income from income_totals it LEFT JOIN equity_totals eq on 
			(eq.transaction_date=it.transaction_date and eq.bank_id=it.bank_id) 
			where it.transaction_date='".$dates[$date]."' and it.bank_id='".$q."'";
		  $result = mysql_query($sql_query);
			$num_rows = mysql_num_rows($result);
			if($num_rows>0){
				while($res = mysql_fetch_array($result)){
				$netincome=  floatval($res['net_income']/1000);
				$equity=  floatval($res['total_capital']/1000);
				
				if($equity==null || $equity==0 || $equity==''){
					$ratio=null;
				}
				else{
				$ratio=($netincome*100)/$equity;
				}
				$ty=   $res['transaction_date'];
				array_push($arr1,round($ratio, 2));
				} 
			}
			else{
				array_push($arr1,null);
				$ty=$dates[$date];
				
				
				}
			
			$stack=array($ty);
			foreach($arr1 as $s){
				array_push($stack,$s);
			}
			array_push($array,$stack);
			}
			  echo (json_encode($array));
	 
    }
	else if($countbanks > '1' && $countdates > '1'){
	for($i=0;$i<count($dates);$i++){
		$arr = "";
		$arr1=array();
		for($j=0;$j<count($banks);$j++){
		$sql_query = "select it.transaction_date,eq.total_capital,it.net_income from income_totals it LEFT JOIN equity_totals eq on 
		(eq.transaction_date=it.transaction_date and eq.bank_id=it.bank_id) left join banks b on
		(b.bank_id=eq.bank_id)
		where it.transaction_date='".$dates[$i]."' and it.bank_id='".$banks[$j]."'";
			
			$result = mysql_query($sql_query);
			$num_rows = mysql_num_rows($result);
			if($num_rows>0){
				while($res = mysql_fetch_array($result)){
				$netincome=  floatval($res['net_income']/1000);
				$equity=  floatval($res['total_capital']/1000);
				
				if($equity==null || $equity==0 || $equity==''){
					$ratio=null;
				}
				else{
				$ratio=($netincome*100)/$equity;
				}
				$ty=   $res['transaction_date'];
				array_push($arr1,round($ratio, 2));
				} 
			}
			else{
				array_push($arr1,null);
				$ty=$dates[$i];
				
				
				}
			} 
			$stack=array($ty);
			foreach($arr1 as $s){
				array_push($stack,$s);
			}
			array_push($array,$stack);
			}
			  echo (json_encode($array));
	}
	
?>
