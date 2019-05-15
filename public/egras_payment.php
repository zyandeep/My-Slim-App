<?php
		//include("Common/php/frmGuestConnection.php");
		//include ("Common/php/csrf-magic.php");
		date_default_timezone_set('Asia/Calcutta');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
<script language = "JavaScript" src="Common/js/NumberFormat153.js"></script>
<script>
function jFun_total(amt){			
	document.frmrto.CHALLAN_AMOUNT.value = getTotalAmount();
}
//FUNCTION FOR TOTAL AMOUNT OF SCHEME CODE
function getTotalAmount() {
	var total=0;	
	for(i=1;i<=9;i++) {		
	//alert(eval('document.frmrto.AMOUNT'+i+'.value'));
			d = eval('document.frmrto.AMOUNT'+i+'.value');					
			if(d.length>0) {
				total += parseFloat(d)
			}
	}	
	var numberTest = new NumberFormat(total);	
	return numberTest.toFormatted();
}
function showPDF(){
	//document.form.action = 'mobileapp/views/frmgrndisplaymobile.php';
	document.getElementById('frmrto').submit();
}
</script>
</head>
<body>
<form name="frmrto" method="post" action="http://103.8.248.139/challan/views/frmgrnfordept.php">
		<table width="90%" align="center" border="1" cellspacing="0" cellpadding="3" class="sort-table">
			<tr>
				<th>Sr. No.</th>
				<th>Variable Name</th>
				<th>Content</th>
				<th>Remarks</th>
			</tr>
		 
			<tr>
				<td class="sort-table"  style="width:10px">1</td>
				<td class="sort-table"  style="width:45px">DEPT_CODE</td>
				<td class="sort-table"  style="width:40px">
					<input type="Text" name="DEPT_CODE" id="DEPT_CODE" value="LRS" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" />
				</td>
				<td class="sort-table"  style="width:220px">Department code assigned to the Department</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">2</td>
				<td class="sort-table"  style="width:45px">PAYMENT_TYPE</td>
				<td class="sort-table"  style="width:40px">
					<input type="Text" name="PAYMENT_TYPE" id="PAYMENT_TYPE" value="01" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Payment types defined by GRAS for each department</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">3</td>
				<td class="sort-table"  style="width:45px">TREASURY_CODE</td>
				<td class="sort-table"  style="width:40px">
					<input type="Text" name="TREASURY_CODE" id="TREASURY_CODE" value="BIL" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Treasury Code assigned to the treasury</td>
			</tr> 
			<tr>
				<td class="sort-table"  style="width:10px">4</td>
				<td class="sort-table"  style="width:45px">OFFICE_CODE</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="OFFICE_CODE" id="OFFICE_CODE" value="LRS000" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Office code of the receipt collecting office</td>
			</tr>	
			<tr>
				<td class="sort-table"  style="width:10px">5</td>
				<td class="sort-table"  style="width:45px">REC_FIN_YEAR</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="REC_FIN_YEAR" id="REC_FIN_YEAR" value="2018-2019" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">For which year the receipt is collected </td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">6</td>
				<td class="sort-table"  style="width:45px">PERIOD</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="PERIOD" id="PERIOD" value="A" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Annual  -      <b>A</b>
					<p>Half Yearly</p>
					<ul>
						<li><b>H1</b> -   Apr - Sep </li>
						<li><b>H2</b> -   Oct - Mar</li>
					</ul>
					<p>Quarterly</p>
					<ul>
						<li><b>Q1</b> - Apr - Jun </li>
						<li><b>Q2</b> - Jul  -  Sep</li>
						<li><b>Q3</b> - Oct - Dec</li>
						<li><b>Q4</b> - Jan - Mar</li>
					</ul>
					<p>Monthly</p>
					<ul>
						<li><b>M</b> - [ Apr May Jun Jul Aug Sep Oct Nov Dec Jan Feb Mar ]</li>
					</ul>
					Specific &nbsp&nbsp&nbsp-&nbsp<b>S</b><br>
					One Time - <b>O</b>
				</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">7</td>
				<td class="sort-table"  style="width:45px">FROM_DATE</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="FROM_DATE" id="FROM_DATE" value="01/04/2018" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Date in From Date will come only if Period is <b>S</b></td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">8</td>
				<td class="sort-table"  style="width:45px">TO_DATE</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="TO_DATE" id="TO_DATE" value="31/03/2019" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Date in To Date will come only if Period is <b>S</b></td>
			</tr>		
			<tr>
				<td class="sort-table"  style="width:10px">9</td>
				<td class="sort-table"  style="width:45px">MAJOR_HEAD</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="MAJOR_HEAD" id="MAJOR_HEAD" value="0029" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Major head of the head of account</td>
			</tr>
			 
			<tr>
				<td class="sort-table"  style="width:10px">10</td>
				<td class="sort-table"  style="width:45px">HOA1</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA1" id="HOA1" value="0029-00-101-0000-000-01" size="35" maxlength="23" style="Hidden-align:left;height:20Px;"  /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			
			<tr>
				<td class="sort-table"  style="width:10px">11</td>
				<td class="sort-table"  style="width:45px">AMOUNT1</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT1" id="AMOUNT1" value="10" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA1</td>
			</tr>
			 
			<tr>
				<td class="sort-table"  style="width:10px">12</td>
				<td class="sort-table"  style="width:45px">HOA2</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA2" id="HOA2" value="" size="35" maxlength="23" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			 
			 
			<tr>
				<td class="sort-table"  style="width:10px">13</td>
				<td class="sort-table"  style="width:45px">AMOUNT2</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT2" id="AMOUNT2" value="" size="35" maxlength="20" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA2</td>
			</tr>
			
			<tr>
				<td class="sort-table"  style="width:10px">14</td>
				<td class="sort-table"  style="width:45px">HOA3</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA3" id="HOA3" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			
			 
			<tr>
				<td class="sort-table"  style="width:10px">15</td>
				<td class="sort-table"  style="width:45px">AMOUNT3</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT3" id="AMOUNT3" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA3</td>
			</tr>
			
			<tr>
				<td class="sort-table"  style="width:10px">16</td>
				<td class="sort-table"  style="width:45px">HOA4</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA4" id="HOA4" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>

			 
			<tr>
				<td class="sort-table"  style="width:10px">17</td>
				<td class="sort-table"  style="width:45px">AMOUNT4</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT4" id="AMOUNT4" value="" size="35" maxlength="20" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA4</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">18</td>
				<td class="sort-table"  style="width:45px">HOA5</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA5" id="HOA5" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>

			 
			<tr>
				<td class="sort-table"  style="width:10px">19</td>
				<td class="sort-table"  style="width:45px">AMOUNT5</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT5" id="AMOUNT5" value="" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA5</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">20</td>
				<td class="sort-table"  style="width:45px">HOA6</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA6" id="HOA6" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			
			<tr>
				<td class="sort-table"  style="width:10px">21</td>
				<td class="sort-table"  style="width:45px">AMOUNT6</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT6" id="AMOUNT6" value="" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA6</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">22</td>
				<td class="sort-table"  style="width:45px">HOA7</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA7" id="HOA7" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			 
			<tr>
				<td class="sort-table"  style="width:10px">23</td>
				<td class="sort-table"  style="width:45px">AMOUNT7</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT7" id="AMOUNT7" value="" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA7</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">24</td>
				<td class="sort-table"  style="width:45px">HOA8</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA8" id="HOA8" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;"  /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			 
			<tr>
				<td class="sort-table"  style="width:10px">25</td>
				<td class="sort-table"  style="width:45px">AMOUNT8</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT8" id="AMOUNT8" value="" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA8</td>
			</tr>
			 
			<tr>
				<td class="sort-table"  style="width:10px">26</td>
				<td class="sort-table"  style="width:45px">HOA9</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="HOA9" id="HOA9" value="" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">5 digit scheme code</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">27</td>
				<td class="sort-table"  style="width:45px">AMOUNT9</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="AMOUNT9" id="AMOUNT9" value="" size="35" maxlength="10" style="Hidden-align:left;height:20Px;" onkeyup="jFun_total(this.value);" /></td>
				<td class="sort-table"  style="width:220px">Amount of HOA9</td>
			</tr> 
			 
			<tr>
				<td class="sort-table"  style="width:10px">28</td>
				<td class="sort-table"  style="width:45px">CHALLAN_AMOUNT</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="CHALLAN_AMOUNT" id="CHALLAN_AMOUNT" value="10.00" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Total Amount of Challan </td>
			</tr>	
			<tr>
				<td class="sort-table"  style="width:10px">29</td>
				<td class="sort-table"  style="width:45px">TAX_ID</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="TAX_ID" id="TAX_ID" value="tin123" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Any Id given by department to tax payer for identification</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">30</td>
				<td class="sort-table"  style="width:45px">PAN_NO</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="PAN_NO" id="PAN_NO" value="ADDPN1233E" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Pan No Of Payee</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">31</td>
				<td class="sort-table"  style="width:45px">PARTY_NAME</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="PARTY_NAME" id="PARTY_NAME" value="hadi shaikh" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Name of Payee</td>
			</tr>	
			<tr>
				<td class="sort-table"  style="width:10px">32</td>
				<td class="sort-table"  style="width:45px">ADDRESS1</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="ADDRESS1" id="ADDRESS1" value="block no 1 premises vibhav" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Will Contain Flat /Block/Premises /Building to be printed on Challan</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">33</td>
				<td class="sort-table"  style="width:45px">ADDRESS2</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="ADDRESS2" id="ADDRESS2" value="karve road" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Will Contain Road / Street to be printed On Challan</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">34</td>
				<td class="sort-table"  style="width:45px">ADDRESS3</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="ADDRESS3" id="ADDRESS3" value="kothrud" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Will Contain Area / Locality Town/City/District</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">35</td>
				<td class="sort-table"  style="width:45px">PIN_NO</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="PIN_NO" id="PIN_NO" value="411038" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Pin Code of Payee</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">36</td>
				<td class="sort-table"  style="width:45px">MOBILE_NO</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="MOBILE_NO" id="MOBILE_NO" value="9876543210" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Mobile number of payee</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">37</td>
				<td class="sort-table"  style="width:45px">DEPARTMENT_ID</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="DEPARTMENT_ID" id="DEPARTMENT_ID" value="Ele1974" size="35" maxlength="30" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Department generated id for each transaction </td>
			</tr>
			
		  
			<tr>
				<td class="sort-table"  style="width:10px">38</td>
				<td class="sort-table"  style="width:45px">REMARKS</td>
				<td class="sort-table"  style="width:40px"><input type="Text" name="REMARKS" id="REMARKS" value="purpose of challan" size="35" maxlength="14" style="Hidden-align:left;height:20Px;" /></td>
				<td class="sort-table"  style="width:220px">Purpose of paying the challan</td>
			</tr>
			<tr>
				<td class="sort-table"  style="width:10px">38</td>
				<td class="sort-table"  style="width:45px">SUB_SYSTEM</td>
				<td class="sort-table"  style="width:40px"><input type="text" name="SUB_SYSTEM" id="SUB_SYSTEM" value="LRC-EMOJNI"  /></td><!--Election-->
				<td class="sort-table"  style="width:220px">responce URL</td>
			</tr>  
		</table>  
		<table width="90%" align="center" border="1" cellspacing="0" cellpadding="3" class="sort-table">
			<tr>
				<td colspan="4"> <input type="submit" value="Submit"/><a href="#" onclick="showPDF();" >Download here</a></td>
			</tr>
		</table>

</form>
</body>
</html>