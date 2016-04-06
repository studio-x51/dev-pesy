<?php

/**
 * @package:
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:49
 */


require '../base.php';

$xml = "
<xmlrequest>
   <username>{$username}</username>
    <usertoken>{$token}</usertoken>
	<requesttype>Contacts</requesttype>
	<requestmethod>createupdateBatch</requestmethod>
	<details>
		<item>
			<!--
			only emailaddress is required
			only fields contained in XML are updated
			-->
			<emailaddress>vaclav@smartemailing.cz</emailaddress>
			<language>cs_CZ</language>
			<!-- cs_CZ | sk_SK | en_GB -->
			<blacklisted>0</blacklisted>
			<name>Martin</name>
			<surname>Strouhal</surname>
			<titlesbefore>Ing.</titlesbefore>
			<titlesafter>Csc.</titlesafter>
			<birthday>2013-01-01</birthday>
			<nameday>2013-11-11</nameday>
			<!-- year does not matter -->
			<salution></salution>
			<!-- will be generated if this field is empty -->
			<company>My Corp</company>
			<street>Long 123</street>
			<town>My Town</town>
			<country>Czech Republic</country>
			<postalcode>123456</postalcode>
			<notes>Something very long</notes>
			<phone>+420123456789</phone>
			<cellphone>+420123456789</cellphone>
			<softbounced>0</softbounced>
			<!-- number of 'soft' bounces -->
			<hardbounced>0</hardbounced>
			<!-- 1 if contact is hardbounced -->
 <customfields>
 <item>
<id>2</id>
<value>
<item>2</item>
</value>
 </item>
 <item>
<id>3</id>
<value>
<item>4</item>
</value>
 </item>
 <item>
<id>4</id>
<value>
<item>8</item>
</value>
 </item>
 </customfields>
<contactliststatuses>
 <item>
<id>2</id>
<status>confirmed</status>
<added>2015-04-13 12:13:53</added>
 </item>
</contactliststatuses>
		</item>
		<item>
			<!--
			only emailaddress is required
			only fields contained in XML are updated
			-->
			<emailaddress>martin@smartemailing.cz</emailaddress>
			<language>cs_CZ</language>
			<!-- cs_CZ | sk_SK | en_GB -->
			<blacklisted>0</blacklisted>
			<name>Martin</name>
			<surname>Strouhal</surname>
			<titlesbefore>Ing.</titlesbefore>
			<titlesafter>Csc.</titlesafter>
			<birthday>2013-01-01</birthday>
			<nameday>2013-11-11</nameday>
			<!-- year does not matter -->
			<salution></salution>
			<!-- will be generated if this field is empty -->
			<company>My Corp</company>
			<street>Long 123</street>
			<town>My Town</town>
			<country>Czech Republic</country>
			<postalcode>123456</postalcode>
			<notes>Something very long</notes>
			<phone>+420123456789</phone>
			<cellphone>+420123456789</cellphone>
			<softbounced>0</softbounced>
			<!-- number of 'soft' bounces -->
			<hardbounced>0</hardbounced>
			<!-- 1 if contact is hardbounced -->
 <customfields>
 <item>
<id>2</id>
<value>
<item>2</item>
</value>
 </item>
 <item>
<id>3</id>
<value>
<item>4</item>
</value>
 </item>
 <item>
<id>4</id>
<value>
<item>8</item>
</value>
 </item>
 </customfields>
<contactliststatuses>
 <item>
<id>2</id>
<status>confirmed</status>
<added>2015-04-13 12:13:53</added>
 </item>
</contactliststatuses>
		</item>
	</details>
</xmlrequest>
";

sendRequest($xml);