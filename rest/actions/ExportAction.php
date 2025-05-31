<?php

declare(strict_types=1);

namespace app\rest\actions;

use app\common\db\ActiveRecord;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Response;

/**
 *
 * @property-read void $data
 */
class ExportAction extends IndexAction
{
    public const FORMAT_DEFAULT = 'xlsx';

    public string $tmpPath = '@runtime/export';

    /**
     * @var bool
     */
    public bool $writeHeader = true;

    /**
     * @var int
     */
    public int $rowStart = 0;

    /**
     * @var string|null
     */
    public ?string $templateFile = null;

    /**
     * @var string|null
     */
    public ?string $nameExport = null;

    /**
     * @var array
     */
    public array $exportAttribute = [];

    /**
     * @var string
     */
    protected string $format = self::FORMAT_DEFAULT;

    /**
     * @var Spreadsheet|null
     */
    protected ?Spreadsheet $spreadsheet = null;

    /**
     * @var array
     */
    protected array $nameColumn = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF',
        'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN',
        'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV',
        'AW', 'AX', 'AY', 'AZ'
    ];

    /**
     * @return null
     * @throws InvalidConfigException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function run()
    {
        if (empty($this->exportAttribute)) {
            throw new InvalidConfigException('Не определены атрибуты для экспорта.');
        }
        $this->getData();
    }

    /**
     * @param $params
     * @return mixed|null
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function runWithParams($params): mixed
    {
        $this->format = ArrayHelper::getValue($params, 'format', self::FORMAT_DEFAULT);
        return parent::runWithParams($params);
    }

    /**
     * @return Spreadsheet|null
     */
    public function getSpreadsheet(): ?Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * @return void
     * @throws InvalidConfigException
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws Exception
     */
    protected function getData(): void
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $result = $this->prepareDataProvider();
        $data = ArrayHelper::getValue($result, 'items');

        $this->spreadsheet = new Spreadsheet();

        if ($this->writeHeader) {
            /** @var ActiveRecord $model */
            $model = Yii::createObject($this->modelClass);
            $this->writeHeader($model, $this->exportAttribute);
        }

        $this->writeData(
            $data,
            $this->exportAttribute
        );

        $objWriter = new Xlsx($this->getSpreadsheet());

        $dir = Yii::getAlias($this->tmpPath);

        $nameExport = $this->nameExport ?? 'Export-' . date('d.m.Y_H:i:s',time()) . '.' . $this->format;
        $fileName = $dir . DIRECTORY_SEPARATOR . $nameExport;

        if (!is_dir($dir)) {
            FileHelper::createDirectory($dir);
        }

        $objWriter->save($fileName);

        Yii::$app->getResponse()->sendFile($fileName, $nameExport , ['mimeType' => 'application/octet-stream'])
            ->on(Response::EVENT_AFTER_SEND, function() use ($fileName) {
                unlink($fileName);
            });
    }

    /**
     * @param ActiveRecord $searchModel
     * @param array $attributes
     * @return void
     * @throws NotSupportedException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    protected function writeHeader(ActiveRecord $searchModel, array $attributes): void
    {
        $boldStyles = [
            'font' => [
                'bold' => true
            ],
        ];
        foreach ($attributes as $column => $property) {
            if (is_array($property)) {
                $attribute = ArrayHelper::getValue($property, 'attribute');
            } elseif (is_string($property)) {
                $attribute = $property;
            } else {
                throw new NotSupportedException('The "attributes" not support.');
            }
            if ($attribute == null) {
                $attribute = ArrayHelper::getValue($property, 'header');
                $label = $attribute == null ? 'not empty' : $attribute;
            } else {
                $label = $searchModel->getAttributeLabel($attribute);
            }
            if (array_key_exists('header', $property)) {
                $label = ArrayHelper::getValue($property, 'header');
            }
            $this->getSpreadsheet()->getActiveSheet()
                ->getStyle($this->nameColumn[$column] . '1')
                ->applyFromArray($boldStyles);
            $this->getSpreadsheet()->getActiveSheet()->setCellValue(
                $this->nameColumn[$column] . '1',
                $label
            );
        }
    }

    /**
     * @param ActiveDataProvider|ArrayDataProvider $dataProvider
     * @param array $attributes
     * @return void
     * @throws NotSupportedException
     * @throws Exception
     */
    protected function writeData(ActiveDataProvider|ArrayDataProvider $dataProvider, array $attributes): void
    {
        $index = $this->rowStart;
        foreach ($dataProvider->getModels() as $row => $model) {
            foreach ($attributes as $column => $property) {
                $value = $this->getValue($property, $model, $column);
                $this->getSpreadsheet()->getActiveSheet()->setCellValue(
                    $this->nameColumn[$column] . $index + 2,
                    $value
                );
            }
            $index++;
        }
    }

    /**
     * @param $property
     * @param $model
     * @param $column
     * @return mixed
     * @throws NotSupportedException
     * @throws Exception
     */
    protected function getValue($property, $model, $column): mixed
    {
        if (is_array($property)) {
            $attribute = ArrayHelper::getValue($property, 'value');
            if ($attribute === null) {
                $attribute = ArrayHelper::getValue($property, 'attribute');
            }
        } elseif (is_string($property)) {
            $attribute = $property;
        } else {
            throw new NotSupportedException('The "attributes" not support.');
        }
        return is_callable($attribute) ? call_user_func($attribute, $model, $column) :
            ArrayHelper::getValue($model, $attribute);
    }
}
