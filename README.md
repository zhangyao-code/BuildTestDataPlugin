# 配置创造测试数据插件

以下步骤适用于。

## 1. 克隆代码

    cd /var/www/edusoho
    git clone git@coding.codeages.work:edusoho-user-conference/BuildTestDataPlugin.git plugins/BuildTestDataPlugin

## 2. 安装插件

    app/console plugin:register BuildTestData

## 3. 创建数据
    app/console CT:build-courseSet num orgCode<非必填> postCode<非必填>(批量创建用户,传递参数是多少个100条,建议单次最大个数7000条)
    app/console CT:build-courseSet courseSetId课程Id num个数(批量复制课程)
    app/console CT:init-JianMo-Block 初始化jianmo编辑区
    app/console CT:fix  批量格式化git status 修改文件,主要是目标路径在src下的.php文件,
    app/console CT:init-plugin-error code(系统code如TRAININGMAIN) 处理使用线上数据执行安装或卸载插件报错问题,

