# tiktokbeauty
一个tiktok抖音美女热舞视频的识别和采集 -基于php和易语言和e4a  
**我们的tiktok会随您的喜好给您分配观看视频，但是即便我们爱看美食视频，在刷抖音或者tiktok的时候，十个视频，有5个是美食的，还有部分是和美食无关的，我在想，我们能否建立出一个只有美食的短视频APP呢，或者只有某一类的短视频APP。对我来讲，做一个简易的短视频APP或许不太难，主要还是没有视频的问题，所以我制作了一个自己会通过鼠标触屏刷tiktok的程序，并且程序能识别目前刷到的短视频是否一个美食、或者美女，而且能根据视频的收藏数、点赞数决定是否收录，一旦收录，将会自动下载该视频并保存到指定位置，甚至它还调用了ffmpeg插件为视频增加了新的水印！**
****
## 实现方法  
1：检查程序运行目录下 “4G.mp4”, “1G.mp4”, “500M.mp4”, “130M.exe”, “600K.png”, “10K.txt” 是否存在，这里我们为您准备了部分比较小的文件，不过一些大的文件就无法上传至github了，比如4G.mp4等，您只需要随便找个4GB左右的视频放入本目录即可，并且文件名务必改为4G.mp4.  
2：直接运行本程序，程序启动会提示让您选择要覆盖写入的U盘或者磁盘，也有可能支持目录。  
3：程序会自动覆盖写入，精度到10K以内。程序运行结束后，整个磁盘剩余空间为100K以下，内部会填充所有无意义的文件。  
****
## 要点：   
温馨提示：这是一个美女识别模型的程序，原理是通过图像人物识别接口，通过调用第三方API，得到视频中的人物是否女性，颜值分数，外加以点赞数、背景乐等判断是否是一个美女视频，以决定是否采集入库，目前识别准确率在80%左右。   
****
## 免责声明：
该程序仅供娱乐和测试和学习，切勿用于商业用途，未经他人或者短视频作者许可，拷贝和分发他人的视频属于盗版违法行为。


