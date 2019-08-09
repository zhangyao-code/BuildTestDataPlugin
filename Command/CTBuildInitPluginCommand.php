<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildInitPluginCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('CT:init-plugin-error')
            ->addArgument('code', InputArgument::OPTIONAL, '系统code')
            ->setDescription('处理使用线上数据执行安装或卸载插件报错问题');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code= $input->getArgument('code');
        $code= empty($code) ? 'TRAININGMAIN':$code;
        $biz = $this->getBiz();
        $connection = $biz['db'];
        $connection->exec("
           delete from cloud_app where code != '{$code}';
        ");

        exec('pwd', $filePaths, $status);
        $conent = "
<?php 
     return array (
        'active_theme_name' => 'jianmo',
        'installed_plugins' => array (
        'BuildTestData' => 
            array (
              'code' => 'BuildTestData',
              'version' => '1.0.0',
              'type' => 'plugin',
              'protocol' => '3',
            ),
            ),
        );
";
        $routingConent = "
_plugin_BuildTestData_:
     resource: '@BuildTestDataPlugin/Resources/config/routing.yml'
     prefix: /
   ";
        file_put_contents('./app/config/plugin.php',$conent);
        file_put_contents('./app/config/routing_plugins.yml',$routingConent);
    }
}
