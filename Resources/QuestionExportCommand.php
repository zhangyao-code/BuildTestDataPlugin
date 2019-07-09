<?php
namespace Topxia\WebBundle\Command;
//
//use Symfony\Component\HttpFoundation\StreamedResponse;
//use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Output\OutputInterface;
//use Topxia\Common\ArrayToolkit;
//use Topxia\Service\Common\ServiceKernel;
//use Topxia\Service\Question\QuestionService;
//
use AppBundle\Command\BaseCommand;
class QuestionExportCommand extends BaseCommand
{
//    protected function configure()
//    {
//        $this->setName('question:export-csv')
//            ->setDescription('用于导出question表问题');
//    }
//
//    protected function execute(InputInterface $input, OutputInterface $output)
//    {
//        $this->initServiceKernel();
//        $exporter = new QuestionExporter();
//        $objWriter = $exporter->writeToExcel(array());
//        $objWriter->save($exporter->getExportFileName());
//    }
//}
//
//class QuestionExporter extends AbstractExporter
//{
//    public function canExport($parameters)
//    {
//        return true;
//    }
//
//    public function getExportFileName()
//    {
//        $filedir = $this->getServiceKernel()->getParameter('kernel.root_dir')."/data/question/";
//
//        if (!is_dir($filedir)) {
//            @mkdir($filedir);
//            @chmod($filedir, 0777);
//        }
//
//        return $filedir.'question'.date("YmdHis", time()).'.xls';
//    }
//
//    protected function getSortedHeadingRow($parameters)
//    {
//        return array(
//            array('code' => 'type', 'title' => '题型'),
//            array('code' => 'stem', 'title' => '题干'),
//            array('code' => 'answer', 'title' => '正确答案'),
//            array('code' => 'difficulty', 'title' => '难度'),
//            array('code' => 'score', 'title' => '分数'),
//            array('code' => 'missScore', 'title' => '漏选分数'),
//            array('code' => 'analysis', 'title' => '解析'),
//            array('code' => 'answer1', 'title' => '选项A'),
//            array('code' => 'answer2', 'title' => '选项B'),
//            array('code' => 'answer3', 'title' => '选项C'),
//            array('code' => 'answer4', 'title' => '选项D'),
//            array('code' => 'answer5', 'title' => '选项E'),
//            array('code' => 'answer6', 'title' => '选项F'),
//            array('code' => 'answer7', 'title' => '选项G'),
//            array('code' => 'answer8', 'title' => '选项H'),
//            array('code' => 'answer9', 'title' => '选项i'),
//            array('code' => 'answer10', 'title' => '选项j'),
//            array('code' => 'answer11', 'title' => '选项k'),
//        );
//    }
//
//    protected function buildExportData($parameters)
//    {
//        $data = array();
//        $count = $this->getQuestionService()->searchQuestionsCount(array());
//        $type= array(
//            'choice' => '多选题',
//            'single_choice'=>'单选题',
//            'uncertain_choice' => '不定项选择题',
//            'fill'=>'填空题',
//            'determine' => '判断题',
//            'essay'=>'问答题',
//            'material' => '材料题'
//        );
//        $difficulty = array(
//            'simple'     => "简单",
//            'normal'     => "一般",
//            'difficulty' => "困难"
//        );
//        if ($count>0) {
//            $page       = $count/100;
//            $page_arr   = $count>100? range(0, $page):array(0);
//            foreach ($page_arr as $page) {
//                $questions = $this->getQuestionService()->searchQuestions(array('types'=>array('choice','single_choice','uncertain_choice', 'fill','determine','essay')), array('createdTime','ASC'), $page*100, ($page+1)*100);
//
//                foreach ($questions as $value) {
//                    $question = array();
//                    $question = array(
//                        'type'=> $type[$value['type']],
//                        'stem'=> $this->getPurifyHtml($value['stem']),
//                        'difficulty'=> empty($value['difficulty'])?$difficulty['normal']:$difficulty[$value['difficulty']],
//                        'score'=> $value['score'],
//                        'analysis'=> empty($value['analysis'])? '':$this->getPurifyHtml($value['analysis']),
//                    );
//                    $question['answer'] = '';
//                    if (in_array($value['type'], array('choice', 'single_choice', 'uncertain_choice'))) {
//                        foreach ($value['answer'] as $answer) {
//                            $question['answer'] .= chr(65+(Int)$answer);
//                        }
//                        foreach ($value['metas']['choices'] as $key =>$choice) {
//                            $question['answer'.($key+1)] = $this->getPurifyHtml($choice);
//                        }
//                    }
//                    if ($value['type']=='determine') {
//                        if ((Int)($value['answer'][0]) >0) {
//                            $question['answer'] = '正确';
//                        } else {
//                            $question['answer'] = '错误';
//                        }
//                    }
//                    if ($value['type']=='essay') {
//                        $question['answer'] = $value['answer'][0];
//                    }
//                    if ($value['type']=='fill') {
//                        $fillAnswers = array();
//                        foreach ($value['answer'] as &$answers) {
//                            $fillAnswers[] = implode('|', $answers);
//                        }
//                        $question['answer'] = implode('##', $fillAnswers);
//                    }
//
//                    $data[] = $question;
//                }
//            }
//        }
//
//        $exportData[] = array(
//            'data' => $data,
//        );
//
//        return $exportData;
//    }
//
    public function getPurifyHtml($html)
    {
        if (empty($html)) {
            return '';
        }

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($html);
    }

//
//    /**
//     * @return QuestionService
//     */
//    protected function getQuestionService()
//    {
//        return $this->getServiceKernel()->createService('Question.QuestionService');
//    }
//}
//
//abstract class AbstractExporter
//{
//    protected $startRow = 1;
//
//    protected $objExcel;
//
//    protected $activeSheet;
//
//    protected $titleColNum;
//
//    protected $serviceContainer;
//
//    abstract public function canExport($parameters);
//
//    abstract public function getExportFileName();
//
//    abstract protected function getSortedHeadingRow($parameters);
//
//    abstract protected function buildExportData($parameters);
//
//    public function __construct()
//    {
//    }
//
//    public function writeToExcel($parameters)
//    {
//        $this->setTitleColNumber($parameters);
//        $exportData = $this->buildExportData($parameters);
//
//        $excel = new Excel();
//        $this->objExcel = $excel->createPHPExcelObject();
//
//        foreach ($exportData as $key => $sheetExportData) {
//            $this->objExcel->createSheet();
//            $this->activeSheet = $this->objExcel->setActiveSheetIndex($key);
//            $this->writeExcelHeadingRow($parameters);
//            $this->writeExportDataToSheet($sheetExportData['data']);
//            if (!empty($sheetExportData['sheetName'])) {
//                if (\PHPExcel_Shared_String::CountCharacters($sheetExportData['sheetName']) > 31) {
//                    $sheetExportData['sheetName'] = \PHPExcel_Shared_String::Substring(
//                        $sheetExportData['sheetName'],
//                        0,
//                        31
//                    );
//                }
//                $this->activeSheet->setTitle($sheetExportData['sheetName']);
//            }
//        }
//        $this->objExcel->setActiveSheetIndex(0);
//
//        return $excel->createWriter($this->objExcel);
//    }
//
//    public function getServiceContainer()
//    {
//        return $this->serviceContainer;
//    }
//
//    public function setServiceContainer($container)
//    {
//        return $this->serviceContainer = $container;
//    }
//
//    protected function writeExportDataToSheet($sheetExportData)
//    {
//        foreach ($sheetExportData as $key => $exportData) {
//            foreach ($exportData as $k => $v) {
//                if (array_key_exists($k, $this->titleColNum)) {
//                    $colNum = $this->titleColNum[$k]['colNumber'];
//                    $rowNum = $this->startRow + $key + 1;
//                    $this->activeSheet->setCellValue($colNum.$rowNum, $v);
//                }
//            }
//        }
//    }
//
//    protected function writeExcelHeadingRow($parameters)
//    {
//        $sortedHeadingRows = $this->getSortedHeadingRow($parameters);
//        foreach ($sortedHeadingRows as $row) {
//            $colNum = $this->titleColNum[$row['code']]['colNumber'];
//            $this->activeSheet->setCellValue($colNum.$this->startRow, $row['title']);
//        }
//    }
//
//    protected function setTitleColNumber($parameters)
//    {
//        $sortedHeadingRows = $this->getSortedHeadingRow($parameters);
//
//        $titleColNum = array();
//
//        foreach ($sortedHeadingRows as $key => $headingRow) {
//            $titleColNum[] = array(
//                'code' => $headingRow['code'],
//                'colNumber' => \PHPExcel_Cell::stringFromColumnIndex($key),
//            );
//        }
//
//        $this->titleColNum = ArrayToolkit::index($titleColNum, 'code');
//    }
//
//    protected function getServiceKernel()
//    {
//        return ServiceKernel::instance();
//    }
//}
//
//
//class Excel
//{
//    protected $phpExcelIO;
//
//    public function __construct($phpExcelIO = '\PHPExcel_IOFactory')
//    {
//        $this->phpExcelIO = $phpExcelIO;
//    }
//
//    public function createPHPExcelObject($filename = null)
//    {
//        return (null === $filename) ? new \PHPExcel() : call_user_func(array($this->phpExcelIO, 'load'), $filename);
//    }
//
//    public function createWriter(\PHPExcel $phpExcelObject, $type = 'Excel5')
//    {
//        return call_user_func(array($this->phpExcelIO, 'createWriter'), $phpExcelObject, $type);
//    }
//
//    public function createStreamedResponse(\PHPExcel_Writer_IWriter $writer, $status = 200, $headers = array())
//    {
//        return new StreamedResponse(
//            function () use ($writer) {
//                $writer->save('php://output');
//            },
//            $status,
//            $headers
//        );
//    }
}
