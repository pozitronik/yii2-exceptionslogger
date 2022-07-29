<?php
declare(strict_types = 1);

namespace pozitronik\sys_exceptions\models;

use pozitronik\helpers\ZipHelper;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;

/**
 * Class LogDownloader
 */
class LogDownloader extends Component {

	public string $baseDir = '@app/runtime';

	public string $fileMask = '*';

	public string $outFilename = 'logs.zip';

	/**
	 * @return Response|null
	 * @throws RangeNotSatisfiableHttpException
	 */
	public function download():?Response {
		$files = FileHelper::findFiles($this->baseDir, ['only' => $this->fileMask]);
		$tmpZipFile = ZipHelper::compressFiles($files);

		return Yii::$app->response->sendContentAsFile(readfile($tmpZipFile), $this->outFilename, [
			'mimeType' => FileHelper::getMimeTypeByExtension($this->outFilename)
		]);
	}
}