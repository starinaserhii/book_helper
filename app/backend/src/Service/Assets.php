<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class Assets
{
    const ASSET_TYPES = [
        'css',
        'js',
    ];

    public function __construct(private string $rootDir)
    {}

    public function getAppAssetsUrls(): array
    {
        $result = [];

        $tmpFolderPath = $this->rootDir.'/var/tmp';
        $assetsFilePath = $tmpFolderPath.'/assets';

        if (!is_dir($tmpFolderPath)) {
            mkdir($tmpFolderPath);
        }

        if (is_dir($tmpFolderPath)) {
            if (file_exists($assetsFilePath)) {
                $content = file_get_contents($assetsFilePath);
                $resultTmp = json_decode($content, true);

                if (is_array($resultTmp)) {
                    $result = $resultTmp;
                }
            } else {
                foreach (self::ASSET_TYPES as $assetType) {
                    $assetPath = $this->rootDir.'/public/static/'.$assetType;

                    foreach (scandir($assetPath) as $item) {
                        if (in_array($item, ['.', '..'])) {
                            continue;
                        }

                        $pathInfo = pathinfo($assetPath.'/'.$item);

                        if ($pathInfo['extension'] == $assetType) {
                            $result['assets'][$assetType][] = '/static/'.$assetType.'/'.$item;
                        }
                    }
                }

                file_put_contents($assetsFilePath, json_encode($result));
            }

            $result['version'] = filemtime($assetsFilePath);
        }

        return $result;
    }
}