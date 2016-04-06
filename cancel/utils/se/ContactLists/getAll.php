<?php

/**
 * @package: 
 * @subpackage:
 * @author: Martin Strouhal <martin@martinstrouhal.cz>, <martin@smartemailing.cz>
 * Created on: 10.05.13 11:49
 */


require '../base.php';

$xml = '
<xmlrequest>
    <username>'.$username.'</username>
    <usertoken>'.$token.'</usertoken>
    <requesttype>ContactLists</requesttype>
    <requestmethod>getAll</requestmethod>
    <details>
    </details>
</xmlrequest>
';
sendRequest($xml);
?>

<!--<response>
   <status>SUCCESS</status>
   <data>
      <item>
         <name>x51 - EBOOK (outsourcing)</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-10 08:55:58</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:6:{i:0;s:7:"created";i:1;s:7:"updated";i:2;s:12:"emailaddress";i:3;s:4:"name";i:4;s:7:"surname";i:5;s:5:"notes";}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>111</activeContacts>
         <totalContacts>170</totalContacts>
         <unsubscribedContacts>25</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>12</bouncedContacts>
         <blacklistedContacts>22</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>2</id>
      </item>
      <item>
         <name>x51 - EBOOK (13 tipů)</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-10 09:33:18</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:5:{i:0;s:7:"created";i:1;s:7:"updated";i:2;s:12:"emailaddress";i:3;s:4:"name";i:4;s:7:"surname";}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1079</activeContacts>
         <totalContacts>1758</totalContacts>
         <unsubscribedContacts>180</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>158</bouncedContacts>
         <blacklistedContacts>341</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>3</id>
      </item>
      <item>
         <name>x51 - BLOG - týdenní aktualizace</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-10 09:58:50</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:5:{i:0;s:7:"created";i:1;s:7:"updated";i:2;s:12:"emailaddress";i:3;s:4:"name";i:4;s:7:"surname";}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>45</activeContacts>
         <totalContacts>96</totalContacts>
         <unsubscribedContacts>28</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>8</bouncedContacts>
         <blacklistedContacts>15</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>4</id>
      </item>
      <item>
         <name>x51 - Webinář 7 chyb - registrovaní účastníci</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-10 10:01:41</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:5:{i:0;s:7:"created";i:1;s:7:"updated";i:2;s:12:"emailaddress";i:3;s:4:"name";i:4;s:7:"surname";}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>95</activeContacts>
         <totalContacts>166</totalContacts>
         <unsubscribedContacts>36</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>5</bouncedContacts>
         <blacklistedContacts>30</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>7</id>
      </item>
      <item>
         <name>Test_SE_podpora</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-13 11:03:57</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.vans@seznam.cz</senderemail>
         <replyto>tomas.vans@seznam.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>211</activeContacts>
         <totalContacts>311</totalContacts>
         <unsubscribedContacts>37</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>26</bouncedContacts>
         <blacklistedContacts>37</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>9</id>
      </item>
      <item>
         <name>x51 - Webinář 7 chyb - otevřeli 1. email s pozvánkou</name>
         <category />
         <publicname />
         <notes />
         <created>2014-02-24 12:42:09</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>224</activeContacts>
         <totalContacts>447</totalContacts>
         <unsubscribedContacts>81</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>17</bouncedContacts>
         <blacklistedContacts>125</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>11</id>
      </item>
      <item>
         <name>Kontakty - web x51 - služby</name>
         <category />
         <publicname>Služby Studia x51</publicname>
         <notes />
         <created>2014-06-23 20:49:13</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>122</activeContacts>
         <totalContacts>224</totalContacts>
         <unsubscribedContacts>39</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>29</bouncedContacts>
         <blacklistedContacts>34</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>12</id>
      </item>
      <item>
         <name>Kontakty - blog - telemarketing</name>
         <category />
         <publicname>Kontaktní údaje firem</publicname>
         <notes />
         <created>2014-07-04 11:02:09</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:4:{i:0;s:12:"emailaddress";i:1;s:4:"name";i:2;s:7:"company";i:3;s:5:"phone";}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>4</activeContacts>
         <totalContacts>38</totalContacts>
         <unsubscribedContacts>28</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>6</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>13</id>
      </item>
      <item>
         <name>Studio x51 academy - kontakty z landing page</name>
         <category />
         <publicname>Studio x51 academy</publicname>
         <notes />
         <created>2014-07-15 12:11:45</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:3:{i:0;s:12:"emailaddress";i:1;s:4:"name";i:2;s:7:"nameday";}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>106</activeContacts>
         <totalContacts>205</totalContacts>
         <unsubscribedContacts>38</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>23</bouncedContacts>
         <blacklistedContacts>38</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>14</id>
      </item>
      <item>
         <name>Studio x51 academy - kontakty z landing page - lajky</name>
         <category />
         <publicname>Facebook lajky</publicname>
         <notes />
         <created>2014-09-22 18:43:09</created>
         <alertIn>0</alertIn>
         <alertOut>1</alertOut>
         <trackedDefaultFields>a:2:{i:0;s:12:"emailaddress";i:1;s:5:"notes";}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>271</activeContacts>
         <totalContacts>523</totalContacts>
         <unsubscribedContacts>55</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>46</bouncedContacts>
         <blacklistedContacts>151</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>15</id>
      </item>
      <item>
         <name>studio x51 academy - kontakty z landing page - ostrá</name>
         <category />
         <publicname>Studio x51 academy - trénink</publicname>
         <notes />
         <created>2014-10-13 17:23:34</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:1:{i:0;s:12:"emailaddress";}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>3149</activeContacts>
         <totalContacts>4881</totalContacts>
         <unsubscribedContacts>314</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>496</bouncedContacts>
         <blacklistedContacts>922</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>17</id>
      </item>
      <item>
         <name>Studio x51 academy - Webinář 7 chyb - registrační form</name>
         <category />
         <publicname />
         <notes />
         <created>2014-11-01 11:59:27</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>606</activeContacts>
         <totalContacts>921</totalContacts>
         <unsubscribedContacts>70</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>33</bouncedContacts>
         <blacklistedContacts>212</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>25</id>
      </item>
      <item>
         <name>Studio x51 academy PREMIUM - objednali</name>
         <category />
         <publicname />
         <notes />
         <created>2014-11-02 13:27:52</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>89</activeContacts>
         <totalContacts>103</totalContacts>
         <unsubscribedContacts>3</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>4</bouncedContacts>
         <blacklistedContacts>7</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>26</id>
      </item>
      <item>
         <name>REOPEN Studio x51 academy</name>
         <category />
         <publicname>REOPEN Studio x51 academy</publicname>
         <notes />
         <created>2015-02-03 16:16:25</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1158</activeContacts>
         <totalContacts>1924</totalContacts>
         <unsubscribedContacts>110</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>248</bouncedContacts>
         <blacklistedContacts>408</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>33</id>
      </item>
      <item>
         <name>REOPEN - ebook - proč se všechny firmy musí přestat hnát</name>
         <category />
         <publicname />
         <notes />
         <created>2015-02-06 09:10:24</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1701</activeContacts>
         <totalContacts>2506</totalContacts>
         <unsubscribedContacts>83</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>143</bouncedContacts>
         <blacklistedContacts>579</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>34</id>
      </item>
      <item>
         <name>REOPEN - workshop - registrovaní uživatelé</name>
         <category />
         <publicname />
         <notes />
         <created>2015-02-19 17:02:29</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1119</activeContacts>
         <totalContacts>1624</totalContacts>
         <unsubscribedContacts>62</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>59</bouncedContacts>
         <blacklistedContacts>384</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>37</id>
      </item>
      <item>
         <name>Studio x51 academy PREMIUM - objednali VIP (reopen)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-02-24 12:02:14</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>6</activeContacts>
         <totalContacts>8</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>1</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>38</id>
      </item>
      <item>
         <name>Studio x51 academy PREMIUM - objednali PREMIUM (reopen)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-02-24 12:02:29</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>231</activeContacts>
         <totalContacts>256</totalContacts>
         <unsubscribedContacts>3</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>4</bouncedContacts>
         <blacklistedContacts>18</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>39</id>
      </item>
      <item>
         <name>REOPEN #2 - video 1 zdarma - ebook</name>
         <category />
         <publicname>REOPEN #2 - video 1 zdarma - ebook</publicname>
         <notes />
         <created>2015-03-10 14:28:01</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>965</activeContacts>
         <totalContacts>1380</totalContacts>
         <unsubscribedContacts>46</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>84</bouncedContacts>
         <blacklistedContacts>285</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>42</id>
      </item>
      <item>
         <name>REOPEN #2 - video 1 zdarma - classic landing</name>
         <category />
         <publicname>REOPEN #2 - video 1 zdarma - classic landing</publicname>
         <notes />
         <created>2015-03-10 14:31:28</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>844</activeContacts>
         <totalContacts>1220</totalContacts>
         <unsubscribedContacts>38</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>162</bouncedContacts>
         <blacklistedContacts>176</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>43</id>
      </item>
      <item>
         <name>REOPEN #2 - workshop - registrovaní uživatelé</name>
         <category />
         <publicname />
         <notes />
         <created>2015-03-26 09:50:20</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík @ Studio x51 academy</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>229</activeContacts>
         <totalContacts>313</totalContacts>
         <unsubscribedContacts>11</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>9</bouncedContacts>
         <blacklistedContacts>64</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>46</id>
      </item>
      <item>
         <name>sx51a live - landing page - casestudy</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-10 11:41:47</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>701</activeContacts>
         <totalContacts>966</totalContacts>
         <unsubscribedContacts>35</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>95</bouncedContacts>
         <blacklistedContacts>135</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>49</id>
      </item>
      <item>
         <name>sx51a live - rezervace vstupenky</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-12 11:34:04</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>45</activeContacts>
         <totalContacts>49</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>4</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>50</id>
      </item>
      <item>
         <name>Segment id #22 (sx51a - neotevřeli video 1)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-12 20:16:14</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id>22</segment_id>
         <hidden>0</hidden>
         <activeContacts>8589</activeContacts>
         <totalContacts>10233</totalContacts>
         <unsubscribedContacts>201</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>294</bouncedContacts>
         <blacklistedContacts>1149</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>51</id>
      </item>
      <item>
         <name>sx51a live - webinář 4 způsoby</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-21 21:19:12</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>246</activeContacts>
         <totalContacts>292</totalContacts>
         <unsubscribedContacts>5</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>5</bouncedContacts>
         <blacklistedContacts>36</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>53</id>
      </item>
      <item>
         <name>sx51a live - objednali vstupenku</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-24 19:26:56</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>19</activeContacts>
         <totalContacts>25</totalContacts>
         <unsubscribedContacts>1</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>4</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>54</id>
      </item>
      <item>
         <name>sx51a live - webinář s hosty</name>
         <category />
         <publicname />
         <notes />
         <created>2015-05-31 22:06:20</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>219</activeContacts>
         <totalContacts>256</totalContacts>
         <unsubscribedContacts>2</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>3</bouncedContacts>
         <blacklistedContacts>32</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>55</id>
      </item>
      <item>
         <name>sx51a live - guest list rozesílka</name>
         <category />
         <publicname />
         <notes />
         <created>2015-06-01 10:28:46</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>86</activeContacts>
         <totalContacts>90</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>4</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>56</id>
      </item>
      <item>
         <name>Segment id #23 (sx51a live - neotevřeli sebevraždu #mail1)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-06-02 08:45:51</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id>23</segment_id>
         <hidden>0</hidden>
         <activeContacts>9141</activeContacts>
         <totalContacts>10548</totalContacts>
         <unsubscribedContacts>156</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>221</bouncedContacts>
         <blacklistedContacts>1030</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>57</id>
      </item>
      <item>
         <name>Lajk nelaik - koupili knihu za 349 Kč</name>
         <category />
         <publicname>Lajk Nelaik</publicname>
         <notes />
         <created>2015-06-17 18:59:16</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>149</activeContacts>
         <totalContacts>164</totalContacts>
         <unsubscribedContacts>1</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>3</bouncedContacts>
         <blacklistedContacts>11</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>58</id>
      </item>
      <item>
         <name>Celý guest list lidí z konference</name>
         <category />
         <publicname />
         <notes />
         <created>2015-06-25 11:07:16</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>102</activeContacts>
         <totalContacts>113</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>10</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>59</id>
      </item>
      <item>
         <name>Obytný přívěs</name>
         <category />
         <publicname />
         <notes />
         <created>2015-07-08 12:19:43</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>katerina.komorousova@x51.cz</senderemail>
         <replyto>katerina.komorousova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>0</activeContacts>
         <totalContacts>0</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>0</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>60</id>
      </item>
      <item>
         <name>SocialSprinters - kontakty z 26 nápadů na soutěže</name>
         <category />
         <publicname />
         <notes />
         <created>2015-08-05 13:21:47</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1116</activeContacts>
         <totalContacts>1384</totalContacts>
         <unsubscribedContacts>25</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>35</bouncedContacts>
         <blacklistedContacts>208</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>61</id>
      </item>
      <item>
         <name>Socialsprinters - koupili video z akademie</name>
         <category />
         <publicname />
         <notes />
         <created>2015-08-17 12:41:29</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>106</activeContacts>
         <totalContacts>124</totalContacts>
         <unsubscribedContacts>3</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>15</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>62</id>
      </item>
      <item>
         <name>socialsprinters - uživatelé</name>
         <category />
         <publicname />
         <notes />
         <created>2015-09-14 19:41:47</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>245</activeContacts>
         <totalContacts>302</totalContacts>
         <unsubscribedContacts>6</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>6</bouncedContacts>
         <blacklistedContacts>45</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>63</id>
      </item>
      <item>
         <name>Nemovitostník - kontakty z ebooku</name>
         <category />
         <publicname />
         <notes />
         <created>2015-09-25 11:45:32</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>8</activeContacts>
         <totalContacts>10</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>1</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>64</id>
      </item>
      <item>
         <name>Nemovitostník - konakty pro schůzku</name>
         <category />
         <publicname />
         <notes />
         <created>2015-09-25 11:45:56</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>kamila.kosova@x51.cz</senderemail>
         <replyto>kamila.kosova@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>2</activeContacts>
         <totalContacts>3</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>1</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>65</id>
      </item>
      <item>
         <name>SocialSprinters -Webinář Jágr - Registrovaní lidé</name>
         <category />
         <publicname />
         <notes />
         <created>2015-10-06 14:32:39</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>899</activeContacts>
         <totalContacts>1021</totalContacts>
         <unsubscribedContacts>9</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>22</bouncedContacts>
         <blacklistedContacts>91</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>66</id>
      </item>
      <item>
         <name>Segment id #24 (SocialSprinters -Webinář Jágr - neotevřeli ani jeden z mailů)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-10-13 14:13:50</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id>24</segment_id>
         <hidden>0</hidden>
         <activeContacts>8499</activeContacts>
         <totalContacts>9324</totalContacts>
         <unsubscribedContacts>52</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>123</bouncedContacts>
         <blacklistedContacts>650</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>67</id>
      </item>
      <item>
         <name>Segment id #25 (SocialSprinters -Webinář Jágr - neotevřeli ani jeden z mailů - + reg)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-10-13 14:15:45</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id>25</segment_id>
         <hidden>0</hidden>
         <activeContacts>8052</activeContacts>
         <totalContacts>8806</totalContacts>
         <unsubscribedContacts>54</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>118</bouncedContacts>
         <blacklistedContacts>582</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>68</id>
      </item>
      <item>
         <name>SocialSprinters Premium - členové</name>
         <category />
         <publicname />
         <notes />
         <created>2015-10-16 13:08:26</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>390</activeContacts>
         <totalContacts>406</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>6</bouncedContacts>
         <blacklistedContacts>10</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>69</id>
      </item>
      <item>
         <name>sx51a live - objednávka záznamu</name>
         <category />
         <publicname />
         <notes />
         <created>2015-10-21 13:26:52</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>4</activeContacts>
         <totalContacts>5</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>1</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>70</id>
      </item>
      <item>
         <name>SocialSprinters - registrovaní na Vánoční webinář</name>
         <category />
         <publicname />
         <notes />
         <created>2015-11-02 21:24:28</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1123</activeContacts>
         <totalContacts>1256</totalContacts>
         <unsubscribedContacts>5</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>19</bouncedContacts>
         <blacklistedContacts>109</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>71</id>
      </item>
      <item>
         <name>Segment id #26 (SocialSprinters - Vánoční webinář - neotevřeli první pozvánku)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-11-11 10:11:26</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id>26</segment_id>
         <hidden>0</hidden>
         <activeContacts>10285</activeContacts>
         <totalContacts>11198</totalContacts>
         <unsubscribedContacts>36</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>74</bouncedContacts>
         <blacklistedContacts>803</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>72</id>
      </item>
      <item>
         <name>Segment id #27 (SocialSprinters - Vánoční webinář - neotevřeli žádnou pozvánku)</name>
         <category />
         <publicname />
         <notes />
         <created>2015-11-15 21:30:36</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id>27</segment_id>
         <hidden>0</hidden>
         <activeContacts>8058</activeContacts>
         <totalContacts>8609</totalContacts>
         <unsubscribedContacts>18</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>49</bouncedContacts>
         <blacklistedContacts>484</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>73</id>
      </item>
      <item>
         <name>SocialSprinters - uživatelé, kteří vytvořili záložku</name>
         <category />
         <publicname />
         <notes />
         <created>2015-11-20 14:13:57</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>229</activeContacts>
         <totalContacts>249</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>19</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>74</id>
      </item>
      <item>
         <name>SocialSprinters - Webinář Soutěže</name>
         <category />
         <publicname />
         <notes />
         <created>2016-01-11 13:52:50</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1260</activeContacts>
         <totalContacts>1482</totalContacts>
         <unsubscribedContacts>10</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>18</bouncedContacts>
         <blacklistedContacts>194</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>75</id>
      </item>
      <item>
         <name>Lajk Nelaik - ukázka knihy zdarma</name>
         <category />
         <publicname />
         <notes />
         <created>2016-01-15 11:02:56</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>813</activeContacts>
         <totalContacts>938</totalContacts>
         <unsubscribedContacts>6</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>9</bouncedContacts>
         <blacklistedContacts>110</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>76</id>
      </item>
      <item>
         <name>SocialSprinters - "vím, že aplikace jsou bomba" - šek na 94.830 Kč</name>
         <category />
         <publicname />
         <notes />
         <created>2016-01-30 20:08:33</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>866</activeContacts>
         <totalContacts>974</totalContacts>
         <unsubscribedContacts>3</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>5</bouncedContacts>
         <blacklistedContacts>100</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>77</id>
      </item>
      <item>
         <name>REOPEN 2016 - Studio x51 academy - kontakty landing page</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-01 12:52:28</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>tomas.muzik@x51.cz</senderemail>
         <replyto>tomas.muzik@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1162</activeContacts>
         <totalContacts>1317</totalContacts>
         <unsubscribedContacts>2</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>81</bouncedContacts>
         <blacklistedContacts>72</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>78</id>
      </item>
      <item>
         <name>stefi - seznam webinar test</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-06 19:03:10</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1</activeContacts>
         <totalContacts>2</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>0</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>79</id>
      </item>
      <item>
         <name>REOPEN 2016 - webinář 1</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-18 09:16:38</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>980</activeContacts>
         <totalContacts>1010</totalContacts>
         <unsubscribedContacts>1</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>6</bouncedContacts>
         <blacklistedContacts>23</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>80</id>
      </item>
      <item>
         <name>S angličtinou kolem světa - kontakty - prelaunch (ebook+lp)</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-19 14:18:25</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>1942</activeContacts>
         <totalContacts>2039</totalContacts>
         <unsubscribedContacts>5</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>25</bouncedContacts>
         <blacklistedContacts>67</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>81</id>
      </item>
      <item>
         <name>REOPEN 2016 - premium - 1 Kč</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-22 15:08:01</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>342</activeContacts>
         <totalContacts>361</totalContacts>
         <unsubscribedContacts>2</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>5</bouncedContacts>
         <blacklistedContacts>12</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>82</id>
      </item>
      <item>
         <name>REOPEN 2016 - premium - 3950 Kč</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-22 15:08:16</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>0</activeContacts>
         <totalContacts>0</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>0</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>83</id>
      </item>
      <item>
         <name>Segment id #28 (REOPEN 2016 - webinar1 - neotevřeli první mail)</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-25 10:10:34</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id>28</segment_id>
         <hidden>0</hidden>
         <activeContacts>12602</activeContacts>
         <totalContacts>13421</totalContacts>
         <unsubscribedContacts>44</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>24</bouncedContacts>
         <blacklistedContacts>751</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>84</id>
      </item>
      <item>
         <name>Segment id #29 (REOPEN 2016 - otevřeli vymazání - 1 kč email)</name>
         <category />
         <publicname />
         <notes />
         <created>2016-02-29 11:41:35</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id>29</segment_id>
         <hidden>0</hidden>
         <activeContacts>2531</activeContacts>
         <totalContacts>3183</totalContacts>
         <unsubscribedContacts>9</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>643</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>86</id>
      </item>
      <item>
         <name>EBOOK - 8 způsobů, jak získat na Facebooku více fanoušků</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-03 10:57:51</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>81</activeContacts>
         <totalContacts>82</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>0</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>87</id>
      </item>
      <item>
         <name>Segment id #30 (Neotevřeli kampaně 1 kč)</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-03 15:21:35</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id>30</segment_id>
         <hidden>0</hidden>
         <activeContacts>12971</activeContacts>
         <totalContacts>13350</totalContacts>
         <unsubscribedContacts>2</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>5</bouncedContacts>
         <blacklistedContacts>372</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>88</id>
      </item>
      <item>
         <name>SocialSprinters - (aktualizovaná verze) kontakty z 26 nápadů na soutěže</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-09 08:30:03</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>126</activeContacts>
         <totalContacts>129</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>2</bouncedContacts>
         <blacklistedContacts>1</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>90</id>
      </item>
      <item>
         <name>SocialSprinters - (aktualizovaná verze) koupili video z akademie</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-09 10:14:48</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>4</activeContacts>
         <totalContacts>4</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>0</bouncedContacts>
         <blacklistedContacts>0</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>91</id>
      </item>
      <item>
         <name>S angličtinou kolem světa - studenti free trénink</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-10 10:50:13</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>2699</activeContacts>
         <totalContacts>2784</totalContacts>
         <unsubscribedContacts>2</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>41</bouncedContacts>
         <blacklistedContacts>42</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>92</id>
      </item>
      <item>
         <name>S angličtinou kolem světa - kontakty z kvízu (video 2)</name>
         <category />
         <publicname />
         <notes />
         <created>2016-03-21 11:44:05</created>
         <alertIn>0</alertIn>
         <alertOut>0</alertOut>
         <trackedDefaultFields>a:0:{}</trackedDefaultFields>
         <sendername>Tomáš Mužík</sendername>
         <senderemail>hello@x51.cz</senderemail>
         <replyto>hello@x51.cz</replyto>
         <signature />
         <segment_id />
         <hidden>0</hidden>
         <activeContacts>989</activeContacts>
         <totalContacts>999</totalContacts>
         <unsubscribedContacts>0</unsubscribedContacts>
         <unconfirmedContacts>0</unconfirmedContacts>
         <bouncedContacts>1</bouncedContacts>
         <blacklistedContacts>9</blacklistedContacts>
         <openRate />
         <clickRate />
         <id>93</id>
      </item>
   </data>
</response>-->