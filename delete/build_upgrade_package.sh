#!/bin/bash -ex

from_version=$1
to_version=$2
upgrade_scripts=$3
h5_version=$4


#fetch release 代码
#rm -rf edusohoTY
#mkdir edusohoTY
cd /var/www/edusoho-system
#git init
#git remote add origin git@coding.codeages.work:edusoho/edusoho.git
git fetch origin release/$to_version:release/$to_version
git fetch --tags
git checkout release/$to_version

preVerTag=`git tag --list | grep "ct-v$from_version"`
if [ ! -n $preVerTag ]; then
  echo "tag ct-v$from_version 不存在!"
  exit 1
fi

#修改数据库连接配置
#if [ ! -f app/config/parameters.yml  ]; then
#  cp app/config/parameters.yml.dist app/config/parameters.yml
#fi

#sed -i "s/\s*database_name.*/    database_name: edusoho_for_build/g" app/config/parameters.yml

#sed -i 's/\s*database_user.*/    database_user: root/g' app/config/parameters.yml

#sed -i 's/\s*database_password.*/    database_password: root/g' app/config/parameters.yml

#创建数据库用于编包
#mysql -uroot -proot -e "drop database if exists \`edusoho_for_build\`";

#mysql -uroot -proot -e "create database \`edusoho_for_build\` DEFAULT CHARACTER SET utf8";

#git clone git clone git@coding.codeages.net:edusoho/upgradescripts.git scripts

#静态编译
#yarn
#npm run compile

#copy seajs静态文件到web目录下
app/console assets:install

#生成js语言包
app/console trans:dump-js

#检查文件变更
if [[ `git status --porcelain` ]]; then
  echo "<<<<<<<<<<Start Check File Changes With Git Diff>>>>>>>>>>"
  git diff --name-status
  echo "<<<<<<<<<<End Check File Changes With Git Diff>>>>>>>>>>"
  git add .
  git commit -m "save compile changes"
else
  echo "compile result no file change"
fi

#执行升级包打包命令

if   [ "$#" -lt 2 ] ; then
echo  $(tput setaf 1)ERROR!!! eg: bin/build-package 8.0.1 8.0.2 or  bin/build-package 8.0.1 8.0.2 y \(y means that skip compile js\) $(tput sgr 0)
exit 1
fi

SKIP="n"
if [ "$#"  -eq 3 ] && [ $3 = 'y' ] ; then
echo  $(tput setaf 1)WARING!!! skip compile js . $(tput sgr 0)
SKIP=$3
fi

# pull the last version of repository
echo $(tput setaf 2)pull the last version of repository using command $(tput bold)git pull$(tput sgr 0)
git pull
app/console trans:dump-js
echo "生成静态资源文件"

# pull the last version of repository from scripts
echo $(tput setaf 2)pull the last version of repository from scripts command $(tput bold) git pull $(tput sgr 0)
cd scripts
git pull
cd ..




if   [ -f "$upgrade_scripts" ] ; then
upgradefile="scripts/upgrade-${to_version}.php";
    if [ ! -f "$upgradefile" ]; then
        echo "升级脚本不存在，请确是否需要升级脚本，确认开其他发者是否已经上传了升级脚本${upgradefile}, 如需处理${upgradefile} 输入n，继续输入y  y|n";
        read LINE;
        if [ "$LINE" == "N" ] || [ "$LINE" == "n" ] || [ "$LINE" == "" ]; then
            echo "升级包制作中止，请创建升级脚本 ${upgradefile}";
            exit 1
        fi
    fi
exit 1
fi



if [ "$SKIP" = 'n' ]; then

# 生成h5静态资源文件
h5repertory="edusoho-h5/index.html";
if [ ! -f "$h5repertory" ]; then
git clone git@coding.codeages.work:edusoho/edusoho-h5.git;
fi

# 打包edusoho-h5
cd edusoho-h5;
git pull;
echo "输入要打包的h5版本号,不输入就是 master分支";

if ['' == "$h5_version"]; then
git checkout master;
git pull;
else
git checkout release/$h5_version;
git pull;
fi

rm -rf node_modules;
rm -rf dist/*;
yarn install --production;
npm run build;
rm -rf ./../web/h5/*;
cp  -rf ./dist/* ./../web/h5/;
cd ../
echo "h5静态资源生成成功";
# 打包edusoho-h5

# remove node modules folder and caches folder
echo $(tput setaf 2)remove old node_modules and app/caches using  command  $(tput bold)rm -rf app/caches node_modules $(tput sgr 0)
rm -rf app/caches node_modules
# yarn install --production;
yarn;

#compile static resource file with webpack
echo $(tput setaf 2)compile static resource file with webpack using command  $(tput bold)npm run compile$(tput sgr 0)
npm run compile

fi
#commit static file
git add web/static-dist
git commit -m "add static file"

# build package
echo $(tput setaf 2) build package  using command  $(tput bold)app/console build:upgrade-package $1 $2  $(tput sgr 0)
app/console   build:upgrade-package $1 $2

echo 'build package is completed'
echo $(tput setaf 2)build package is completed$(tput sgr 0)



