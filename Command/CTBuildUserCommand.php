<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use Biz\System\Service\SettingService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildUserCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('corporate-training:build-user')
            ->addArgument('number', InputArgument::REQUIRED, '个数')
            ->setDescription('批量创建用户,传递参数是多少个500条');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parameters = array();
        $parameters['number'] = $input->getArgument('number');
        $this->importUsers($parameters['number']);

    }

    protected function getUserDao()
    {
        return $this->createDao('User:UserDao');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createDao('System:SettingService');
    }

    protected function getAuthService()
    {
        return $this->createService('User:AuthService');
    }

    protected function createDao($alias)
    {
        $biz = $this->getBiz();
        return $biz->dao($alias);
    }

    public function importUsers($num)
    {
        $truename = array('a','b','c','d','e','f','g','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        for($n = 0; $n<$num; $n++){
            $this->getUserDao()->db()->beginTransaction();
            try {
                $users = array();
                for ($i = 0; $i < 500; $i++) {
                    $users[$i]["gender"] = "male";
                    $users[$i]["truename"] = '姓名'.$truename[rand(0,25)];
                    $users[$i]["password"] = "123456";
                    $users[$i]['type'] = 'import';
                    $users[$i]['email']          =  'ex'.time().'test@edu.com'.$i;
                    $users[$i]['orgIds'] = array(1);
                    $users[$i]['orgCodes'] = array('1.');
                    $user = $this->getAuthService()->register($users[$i]);
                    $roles[] = 'ROLE_USER';
                    $users[$i]['roles'] = $roles;
                    $roles = array_unique($roles);
                    $this->getUserService()->changeUserRoles($user['id'], $roles);
                    var_dump('创建用户'.$user['id']);
                }
                $this->getUserDao()->db()->commit();
            } catch (\Exception $e) {
                $this->getUserDao()->db()->rollback();
                throw $e;
            }
        }

    }


}
