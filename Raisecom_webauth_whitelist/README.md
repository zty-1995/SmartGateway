# Raisecom smart gateway has unauthorized access vulnerability

## Overview
Manufacturer's address：[(http://www.raisecom.com.cn/)]
## Vulnerability impact
Raisecom Technology Development Co., Ltd. Raisecom Intelligent Gateway
## Vulnerability location
/firwall/webauth_whitelist.php
<br /> 
## Vulnerability details
Raisecomda Technology Development Co., Ltd. (http://www.raisecom.com.cn/) Raisecomda is the industry's leading provider of optical network products and system solutions. The company was founded in 1999 and has been deeply involved in the communications industry for more than 20 years. It provides customers with information and communications infrastructure construction, as well as digital and intelligent transformation in the fields of all-optical networks, switching routing, cloud network security integration, wireless communications, and edge computing. Upgrade solutions and technical services. The company is committed to becoming a trustworthy partner for customers in their digital and intelligent transformation, and continues to create value for customers through continuous innovation. It has a wealth of successful cases in telecom operators, energy, transportation, government, finance, education, manufacturing and other industries.
    Raisecom smart gateway has an unauthorized access vulnerability. An attacker can exploit this vulnerability to gain server privileges.
<br/> ![image](https://github.com/user-attachments/assets/57c64993-407f-45f0-a91c-1164dc86cd83)
<br/>本漏洞均为代码审计结果，所以不提供互联网案例，提供部分源码，请审核仔细查阅，谢谢。
<br/>资产证明：Fofa 
<br/>body="/images/raisecom/back.gif"
<br/>![image](https://github.com/user-attachments/assets/f12a22cb-b878-4416-8885-20ec06621107)
## Vulnerability verify
[(http://www.raisecom.com.cn/)]
<br /> 1.Code analysis
<br /> Because the function.php authentication file is not included in webauth_whitelist.php, the system's background firewall adds a whitelist function point that is directly accessed and operated without authorization.
<br /> ![image](https://github.com/user-attachments/assets/efb9d223-5a47-42de-a3f1-b5991ae1798f)
<br /> ![image](https://github.com/user-attachments/assets/32f522dd-d73d-4454-83e6-86b1233b36a3)
<br /> 2.Local reproduction
<br />https://127.0.0.1:8072
<br /> The login interface is as shown in the figure
<br /> ![image](https://github.com/user-attachments/assets/f4134e8d-b7fb-4ab2-b56a-017b4d6acc94)
<br /> By directly accessing /firwall/webauth_whitelist.php without authorization, the whitelist function point can be added to the background firewall of the operating system.
<br /> ![image](https://github.com/user-attachments/assets/99b2ff90-6331-43c2-9e6a-fb3125e55d10)
<br /> Provide real case reproduction: Since it is a front-end vulnerability, the account and password are not provided.
<br /> http://171.38.92.38:8080
