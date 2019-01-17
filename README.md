# 配置创造测试数据插件

以下步骤适用于。

## 1. 克隆代码

    cd /var/www/edusoho
    git clone git@coding.codeages.net:edusoho-user-conference/BuildTestDataPlugin.git plugins/BuildTestDataPlugin

## 2. 安装插件

    app/console plugin:register BuildTestData

## 3. 创建用户
    app/console corporate-training:build-user num(你需要创建多少个100条)
    php plugins/BuildTestDataPlugin/Command/CTClearPlugin.php  -f Survey,QA 处理导数据库有插件依赖无法执行app/console问题
    app/console corporate-training:build-courseSet courseSetId课程Id num个数
