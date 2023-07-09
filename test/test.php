<?php
/**
 * author      : Administrator
 * creatTime   : 2023/7/9 15:12
 * description :
 */
require "../src/CookiesManager.php";
require "../src/SetCookie.php";
$cookie        = 'JSESSIONID=RZ558rUmVP0wWb48KNzz4ERqSmUZMhmobileclientgwRZ55; mobileSendTime=-1; credibleMobileSendTime=-1; ctuMobileSendTime=-1; riskMobileBankSendTime=-1; riskMobileAccoutSendTime=-1; riskMobileCreditSendTime=-1; riskCredibleMobileSendTime=-1; riskOriginalAccountMobileSendTime=-1; cna=D7enGdKJIUwCAXug876RH35z; LoginForm=alipay_login; CLUB_ALIPAY_COM=2088112344752584; ali_apache_tracktmp="uid=2088112344752584"; auth_jwt=e30.eyJleHAiOjE2ODcwOTc4MTU2ODMsInJsIjoiNSwwLDI3LDE4LDI4LDMwLDEzLDEwIiwic2N0IjoiTGxlOWJkOTExUlExQ2tRb3pFcitRRm96VlAra3dGZ1d5RHl5Q0tBIiwidWlkIjoiMjA4ODExMjM0NDc1MjU4NCJ9.rxQ3aCo3_67SBlBPz3pl2AJ-KbaYX4shv61oxodz1pI; session.cookieNameId=ALIPAYJSESSIONID; ALI_PAMIR_SID=U585n5FWodXoSpx+8NHpOh9xjU4#WpaP5E1vQry+ZTTFK1SMlzU4; userId=2088112344752584; __TRACERT_COOKIE_bucUserId=2088112344752584; umt=HB73f64748a5fce633e6f6f134772acea2; rtk=fr0pAOfRBdIFAbArmraNoaKE7JJSZ1UXbBz+y5cRnx4CZ1iJnHM; csrfToken=rQWSvCKzKJSty1h_btmUH-JX; ALIPAYJSESSIONID=GZ00guidEfaPKSY5D2g4WNOe9yEm5topenhomeRZ41GZ00; ALIPAY_WAP_CASHIER_COOKIE=1FD6B2F6C97FEB9749B52C89E7FA4445D27421DE3835157BBB4A658EB9254DC0; zone=RZ55A; awid=RZ558rUmVP0wWb48KNzz4ERqSmUZMhmobileclientgwRZ55; ctoken=vsGsQWlU65NKmEdt; JSESSIONID=RZ558rUmVP0wWb48KNzz4ERqSmUZMhmobileclientgwRZ55; spanner=2ARL+0fT9gYycJ3GZ6poNBLOr789igf5Xt2T4qEYgj0=';
$ck = new \Ibazhe\Cookies\CookiesManager();
$ck->up($cookie);