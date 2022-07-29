<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use Closure;
use pozitronik\helpers\PathHelper;
use pozitronik\helpers\ZipHelper;
use pozitronik\sys_exceptions\SysExceptionsModule;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;

/**
 * Class LogDownloader
 * @property string|Closure $baseDir Каталог с логами
 * @property string|Closure $fileMask Маска файлов, включаемых в выдачу
 * @property string|Closure $outFilename Имя файла архива
 */
class LogDownloader extends Component {

	public Closure|string $baseDir = '@app/runtime';

	public Closure|string $fileMask = '*';

	public Closure|string $outFilename = 'logs.zip';

	/**
	 * @inheritDoc
	 */
	public function __construct($config = []) {
		parent::__construct($config);
		$this->baseDir = SysExceptionsModule::param('baseDir', $this->baseDir);
		if (is_callable($this->baseDir)) $this->baseDir = call_user_func($this->baseDir);
		$this->fileMask = SysExceptionsModule::param('fileMask', $this->fileMask);
		if (is_callable($this->fileMask)) $this->fileMask = call_user_func($this->fileMask);
		$this->outFilename = SysExceptionsModule::param('outFilename', $this->outFilename);
		if (is_callable($this->outFilename)) $this->outFilename = call_user_func($this->outFilename);
	}

	/**
	 * @return Response
	 * @throws RangeNotSatisfiableHttpException
	 */
	public function download():Response {
		$files = FileHelper::findFiles(Yii::getAlias($this->baseDir), ['only' => [$this->fileMask]]);
		$files = array_combine(array_map(static fn($value) => PathHelper::ExtractBaseName($value), $files), $files);
		$tmpZipFile = ZipHelper::compressFiles($files);

		return Yii::$app->response->sendContentAsFile(file_get_contents($tmpZipFile), $this->outFilename, [
			'mimeType' => FileHelper::getMimeTypeByExtension($this->outFilename)
		]);
	}
}