<?php
$leftColumn  = false;
$rightColumn = false;
$txtsRight	 = 0;
$txtsLeft	 = 0;
$txtsCenter	 = 2;
$colors 	 = 4;
$content = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:rgb(42, 55, 78)" >
<tbody>
<tr>
<td align="center" bgcolor="#%color_1%">
<table  cellpadding="0" cellspacing="0" border="0">
	<tbody>                            
		<tr>
			<td class="w640"  width="640" height="10"></td>
		</tr>

		<tr>
			<td align="center" class="w640"  width="640" height="20"> <a style="color:#ffffff; font-size:12px;" href="#"><span style="color:#ffffff; font-size:12px;">Voir le contenu de ce mail en ligne</span></a> </td>
		</tr>
		<tr>
			<td class="w640"  width="640" height="10"></td>
		</tr>


		<!-- entete -->
		<tr class="pagetoplogo">
			<td bgcolor="#%color_2%">
				<table cellpadding="0" cellspacing="0" border="0" bgcolor="#%color_2%">
					<tbody>
						<tr>
							<td class="w30"  width="30"></td>
							<td  class="w580"  width="580" valign="middle" align="left">
								<div class="pagetoplogo-content">
									<center><img  style="text-decoration: none; display: block; color:#476688; font-size:30px;" src="%logo%" alt="Mon Logo"/></center>
								</div>
							</td> 
							<td class="w30"  width="30"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

		<!-- separateur horizontal -->
		<tr>
			<td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
		</tr>

		 <!-- contenu -->
		<tr class="content">
			<td class="w640" class="w640"  width="640" bgcolor="#%color_3%">
				<table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td  class="w30"  width="30"></td>
							<td  class="w580"  width="580">
								<!-- une zone de contenu -->
								<table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
									<tbody>                                                            
										<tr>
											<td class="w580"  width="580">
												%center_1%
											</td>
										</tr>
										<tr>
											<td class="w580"  width="580" height="1" bgcolor="#c7c5c5"></td>
										</tr>
									</tbody>
								</table>
								<!-- fin zone -->                                                   
							</td>
							<td class="w30" class="w30"  width="30"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

		<!--  separateur horizontal de 15px de  haut-->
		<tr>
			<td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
		</tr>

		<!-- pied de page -->
		<tr class="pagebottom">
			<td class="w640"  width="640" bgcolor="#%color_4%">
				<table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#%color_4%">
					<tbody>
						<tr>
							<td colspan="5" height="10"></td>
						</tr>
						<tr>
							<td class="w30"  width="30"></td>
							<td class="w580"  width="580" valign="top">
								%center_2%
							</td>

							<td class="w30"  width="30"></td>
						</tr>
						<tr>
							<td colspan="5" height="10"></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td class="w640"  width="640" height="60"></td>
		</tr>
	</tbody>
</table>';