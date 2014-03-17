# source http://www.mediawiki.org/wiki/Manual:Upgrading

1029  cd ..
 1030  mkdir upgrade
 1031  cd upgrade/
 1032  mkdir malversando.mediawiki.1.22.4
 1033  mkdir literaturawiki.mediawiki.1.22.4
 1034  cd malversando.mediawiki.1.22.4/
1035  tar xzvf ~/Téléchargements/mediawiki-1.22.4.tar.gz 
 1036  ls
 1037  cd mediawiki-1.22.4/
 1038  ls
 1039  pwd
 1040  cp ~/Documents/harmodio/web/malver.huiqui/malversando.com/LocalSettings.php .
 1041  cp ~/Documents/harmodio/web/malver.huiqui/malversando.com/LocalSettings.ouvaton.php .
 1042  cp ~/Documents/harmodio/web/malver.huiqui/malversando.com/LocalSettings.localhost.php .
 1043  ls
 1044  cp -r ~/Documents/harmodio/web/malver.huiqui/malversando.com/images/ .
 1045  ls images/
 1046  ls extensions/
 1047  ls ~/Documents/harmodio/web/malver.huiqui/malversando.com/extensions/
 1048  cp -r ~/Documents/harmodio/web/malver.huiqui/malversando.com/extensions/Vector/ ./extensions/
 1049  cp -r ~/Documents/harmodio/web/malver.huiqui/malversando.com/extensions/YouTube/ ./extensions/
 1050  ls extensions/
 1051  ls ~/Documents/harmodio/web/malver.huiqui/malversando.com/skins/common/images/
 1052  cp  ~/Documents/harmodio/web/malver.huiqui/malversando.com/skins/common/images/mitosis.de.rueda.* ./skins/common/images/
 1053  chmod -R 755 images/
 1054  chgrp -R apache images/
 1055  sudo chgrp -R apache images/
 1056  ll
 1057  ll images/
 1058  locate htaccess
 1059  ls
 1060  pwd
 1061  cd ..
 1062  ls
 1063  pushd ../../
 1064  cd malversando.com/
 1065  ls
 1066  rm *~
 1067  mkdir  ~/tmp/malversando.com.1.21.backup
 1068  mv * ~/tmp/malversando.com.1.21.backup
 1069  ls
 1070  ls ~/Documents/harmodio/web/malver.huiqui/upgrade/malversando.mediawiki.1.22.4
 1071  ls ~/Documents/harmodio/web/malver.huiqui/upgrade/malversando.mediawiki.1.22.4/mediawiki-1.22.4/
 1072  mv ~/Documents/harmodio/web/malver.huiqui/upgrade/malversando.mediawiki.1.22.4/mediawiki-1.22.4/* .
 1073  ls
 1074  git status
 1075  php maintenance/update.php
