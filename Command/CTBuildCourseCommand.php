<?php

namespace BuildTestDataPlugin\Command;

use AppBundle\Command\BaseCommand;
use Biz\Course\Dao\CourseSetDao;
use Biz\Course\Service\CourseSetService;
use Biz\Org\Service\OrgService;
use Biz\System\Service\SettingService;
use Codeages\Biz\Framework\Service\Exception\NotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CTBuildCourseCommand extends BaseCommand
{
    private $biz;
    protected function configure()
    {
        $this
            ->setName('corporate-training:build-courseSet')
            ->addArgument('courseSetId', InputArgument::REQUIRED, 'courseSetId')
            ->addArgument('number', InputArgument::REQUIRED, '个数')
            ->setDescription('批量复制课程,传递参数是多少个10条,');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->biz = $this->getBiz();

        $parameters = array();
        $parameters['courseSetId'] = $input->getArgument('courseSetId');
        $parameters['number'] = $input->getArgument('number');
        $courseSet = $this->getCourseSetService()->getCourseSet($parameters['courseSetId']);
        if (empty($courseSet)) {
           throw new NotFoundException("{$parameters['courseSetId']} 对应课程不存在");
           exit;
        }

        for ($n = 0; $n<$parameters['number']; $n++){
            $this->cloneCourseSet($courseSet);

        }


    }

    /**
     * @return CourseSetDao
     */
    protected function getCourseSetDao()
    {
        return $this->createDao('Course:CourseSetDao');
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return CourseSetService
     */
    protected function getCourseSetService()
    {
        return $this->createService('Course:CourseSetService');
    }

    /**
     * @return OrgService
     */
    protected function getOrgService()
    {
        return $this->createService('Org:OrgService');
    }

    protected function createDao($alias)
    {
        return $this->biz->dao($alias);
    }

    protected function beginTransaction()
    {
        $this->biz['db']->beginTransaction();
    }

    protected function commit()
    {
        $this->biz['db']->commit();
    }

    protected function rollback()
    {
        $this->biz['db']->rollback();
    }

    public function cloneCourseSet($courseSet)
    {

        for ($i = 0; $i < 10; $i++) {

            try {
                $this->beginTransaction();

                if (empty($courseSet)) {
                    throw new NotFoundException('courseSet not found');
                }
                $newCourse['title'] = '新建课程'.time();
                $this->biz['course_set_courses_copy']->copy($courseSet, array('params' => $newCourse));
                var_dump('复制课程-'.$newCourse['title']);
                $this->commit();
            } catch (\Exception $e) {
                $this->rollback();

                throw $e;
            }
        }

    }


}
