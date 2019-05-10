<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use AppBundle\Common\BlockToolkit;
use Biz\Org\Service\OrgService;
use Biz\System\Service\SettingService;
use Codeages\Biz\Framework\Service\Exception\NotFoundException;
use CorporateTrainingBundle\Biz\Post\Service\PostService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildInitJianMoBlockCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('build-data:initJianMoBlock')
            ->setDescription('初始化jianmo编辑区');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $biz = $this->getBiz();
        $themeDir=$biz['root_directory'].'/web/themes';
        BlockToolkit::init("{$themeDir}/jianmo/block.json");
    }
}
