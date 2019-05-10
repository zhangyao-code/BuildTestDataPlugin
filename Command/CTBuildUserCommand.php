<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use Codeages\Biz\Framework\Service\Exception\NotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildUserCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('build-data:build-user')
            ->addArgument('number', InputArgument::REQUIRED, '个数')
            ->addArgument('orgCode', InputArgument::OPTIONAL, '组织机构orgCode，例：1.')
            ->addArgument('postCode', InputArgument::OPTIONAL, '岗位code')
            ->setDescription('批量创建用户,传递参数是多少个100条,建议单次最大个数7000条');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = array();
        $parameters['number'] = $input->getArgument('number');

        $parameters['orgCode'] = $input->getArgument('orgCode');
        $org = $this->getOrgService()->getOrgByOrgCode($parameters['orgCode']);
        if (empty($org)&&empty($parameters['orgCode'])) {
            throw new NotFoundException("{$parameters['orgCode']} 对应org不存在");
            exit;
        }

        $org['orgCode'] = empty($parameters['orgCode'])?'1.':$parameters['orgCode'];
        $org['id'] = empty($parameters['orgCode'])?'1':$org['id'];

        $parameters['postCode'] = $input->getArgument('postCode');
        $post = $this->getPostService()->getPostByCode($parameters['postCode']);
        if (empty($post)&&!empty($parameters['postCode'])) {
            throw new NotFoundException("{$parameters['postCode']} 对应岗位不存在");
            exit;
        }
        $parameters['postId'] = empty($parameters['postCode'])?'':$post['id'];



        $this->importUsers($parameters['number'], $org, $parameters['postId']);
    }

    protected function getUserDao()
    {
        return $this->createDao('User:UserDao');
    }

    protected function getSettingService()
    {
        return $this->createDao('System:SettingService');
    }

    protected function getAuthService()
    {
        return $this->createService('User:AuthService');
    }

    protected function getOrgService()
    {
        return $this->createService('Org:OrgService');
    }

    protected function getPostService()
    {
        return $this->createService('Post:PostService');
    }

    protected function createDao($alias)
    {
        $biz = $this->getBiz();
        return $biz->dao($alias);
    }

    public function importUsers($num, $org, $postId='')
    {
        $truename = array('a','b','c','d','e','f','g','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        for ($n = 0; $n<$num; $n++) {
            $this->getUserDao()->db()->beginTransaction();
            try {
                $users = array();
                for ($i = 0; $i < 100; $i++) {
                    if (!empty($parameters['postId'])) {
                        $users[$i]["postId"] = $parameters['postId'];
                    }
                    $users[$i]["gender"] = "male";
                    $users[$i]["truename"] = $truename[rand(0, 25)].'姓名'.$truename[rand(0, 25)];
                    $users[$i]["password"] = "123456";
                    $users[$i]['type'] = 'import';
                    $users[$i]['email']          =  'ex'.time().'test@edu.com'.$i;
                    $users[$i]['orgIds'] = array($org['id']);
                    $users[$i]['orgCodes'] = array($org['orgCode']);
                    $user = $this->getAuthService()->register($users[$i]);
                    var_dump('创建用户,Id'.$user['id']);
                }
                $this->getUserDao()->db()->commit();
            } catch (\Exception $e) {
                $this->getUserDao()->db()->rollback();
                throw $e;
            }
        }
    }
}
