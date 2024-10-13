# Raisecom smart gateway has unauthorized access vulnerability

## Overview
Manufacturer's address：[(http://www.raisecom.com.cn/)]
## Vulnerability impact
Raisecom Technology Development Co., Ltd. Raisecom Intelligent Gateway
## Vulnerability location
/network/bind_ipmac_add.php
## Vulnerability details
Raisecomda Technology Development Co., Ltd. (http://www.raisecom.com.cn/) Raisecomda is the industry's leading provider of optical network products and system solutions. The company was founded in 1999 and has been deeply involved in the communications industry for more than 20 years. It provides customers with information and communications infrastructure construction, as well as digital and intelligent transformation in the fields of all-optical networks, switching routing, cloud network security integration, wireless communications, and edge computing. Upgrade solutions and technical services. The company is committed to becoming a trustworthy partner for customers in their digital and intelligent transformation, and continues to create value for customers through continuous innovation. It has a wealth of successful cases in telecom operators, energy, transportation, government, finance, education, manufacturing and other industries.
    Raisecom smart gateway has an unauthorized access vulnerability. An attacker could exploit this vulnerability to gain server privileges.
   <br/> ![image](https://github.com/user-attachments/assets/1aa3547b-9a07-4637-827e-3db41494da2e)
<br/>This vulnerability is the result of code audit, so Internet cases are not provided. Some source codes are provided. Please review them carefully. Thank you.
<br/>Proof of assets: Fofa
<br/>body="/images/raisecom/back.gif"
<br/>![image](https://github.com/user-attachments/assets/e59b69e2-f4c3-413f-a0ba-18cbcd31a02d)

## Vulnerability verify
[(https://www.wondershare.cn/shop.html)]
<br /> 1.Code analysis
<br />Because the function.php authentication file is not included in bind_ipmac_add.php, the function point that adds a static address in the background of the system is directly accessed and operated without authorization.
<br /> ![image](https://github.com/user-attachments/assets/0b8c5ecb-995d-4b7b-9687-869ae40856fc)
<br /> ![image](https://github.com/user-attachments/assets/298964bf-ab6e-4c9d-acda-8e19a14bb390)
<br /> 2.Local reproduction
<br /> https://127.0.0.1:8072
<br /> The login interface is as shown in the figure
<br /> ![image](https://github.com/user-attachments/assets/91f76e0b-6eae-4bb1-bb70-cb3c371de5b8)
<br /> By directly accessing /network/bind_ipmac_add.php without authorization, you can add a static address function in the background of the operating system.
<br /> ![image](https://github.com/user-attachments/assets/775c70c4-5fd9-4861-bc0b-70b326796a6e)
<br />Provide real case reproduction: Since it is a front-end vulnerability, the account and password are not provided.
<br />http://171.38.92.38:8080
