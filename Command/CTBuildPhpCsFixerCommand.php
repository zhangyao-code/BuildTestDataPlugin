<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildPhpCsFixerCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('build-data:php-cs-fixer')
            ->addArgument('plugin', InputArgument::OPTIONAL, '插件名称如ExamPlugin')
            ->setDescription('批量格式化git status 修改文件,主要是目标路径在src下的.php文件,插件带上插件名称如ExamPlugin');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $plugin= $input->getArgument('plugin');
        exec('pwd', $filePaths, $status);

        if (!empty($plugin)&&!empty($filePaths[0])) {
            exec('cd  '.$filePaths[0].'/plugins/'.$plugin.'; git add .;git status', $logs, $status);
        } else {
            exec('git add .;git status', $logs, $status);
        }

        foreach ($logs as $key => $log) {
            if (strpos($log, '.php')!== false && strpos($log, 'deleted:') == false) {
                $src = strpos($log, ':');
                $php = strpos($log, '.php');

                if (!empty($plugin)&&!empty($logs[0])) {
                    var_dump(trim(substr($log, $src+1, $php)));
                    system("php-cs-fixer fix ".$filePaths[0].'/plugins/'.$plugin.'/'.trim(substr($log, $src+1, $php)));
                } else {
                    var_dump(substr($log, $src, $php));
                    system("php-cs-fixer fix ".substr($log, $src, $php));
                }
            }
        }
    }
}
