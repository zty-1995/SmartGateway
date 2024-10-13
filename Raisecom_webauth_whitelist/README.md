# There is a remote code execution vulnerability in the project management of Wanxing Technology's Yitu project

## Overview
Manufacturer's address：[(https://www.wondershare.cn/)]
## Vulnerability impact
Wanxing Technology's Yitu project Management Software 3.2.2
## Vulnerability location
The expe.dpx project file is actually a zip compressed file, which can be used to construct special file names, resulting in unexpected paths when opening and decompressing the project file to a temporary directory
<br /> <img width="415" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/01ca0d6a-e118-4498-8729-267178393839">
## Vulnerability details
There is a remote code execution vulnerability in the project management of Wanxing Technology's Yitu project.Attackers can use the exp.adpx project file as a zip compressed file to construct a special file name, which can be used to decompress the project file into the system startup folder, restart the system, and automatically execute the constructed attack script 
## Vulnerability verify
[(https://www.wondershare.cn/shop.html)]
<br /> 1.Win+r (run) "shell: startup" Open the startup folder and there is no calc.exe program file in the folder
<br /> <img width="415" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/1f3fe98b-3ddb-4fe0-8628-ff49b9ec3da4">
<br /> 2.Double click to open the project file exp.adpx and wait for the file to open
<br /> <img width="415" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/feae17aa-9b6d-4736-a160-d3d9710038fa">
<br /> 3.Win+r (run) "shell: startup" Open the startup folder
<br /> <img width="297" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/1a3a62fc-a6d0-4d4d-8f42-d8d8e57defde">
<br /> 4.See that calc.exe has been released into the startup folder
<br /> <img width="415" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/a5583363-dbe8-414e-9556-73a68c2e7008">
<br /> 5.Restart the computer and wait for the system to start to see calc.exe running automatically.
<br /> <img width="415" alt="image" src="https://github.com/zty007666/Shenzhen-Yitu-Software-Yitu-Project-Management-Software/assets/26759286/b7b48578-aecf-480e-8abf-aa1b1ee88d34">
<br /> 6.verification has been successful
## remaining assets
https://www.wondershare.cn/shop.html
