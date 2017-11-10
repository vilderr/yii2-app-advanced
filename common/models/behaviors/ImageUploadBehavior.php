<?php


namespace common\models\behaviors;
use PHPThumb\GD;
use yii\helpers\FileHelper;

/**
 * Переопределеям методы родительского класса
 * - Добавлена возможность выбора способа сжатия картинок
 * - Добавляем опциональную возможность удаления оригиналов картинок
 *
 * Class ImageUploadBehavior
 * @package linex\modules\catalog\behaviors
 */
class ImageUploadBehavior extends \yiidreamteam\upload\ImageUploadBehavior
{
    public $resizeType = 'resize';

    /**
     * После создания превью в методе родительского класса, удаляем оригиналы картинок
     * (Вынесем возможность опционального удаления оригиналов в отдельную настройку)
     */
    public function createThumbs()
    {
        $path = $this->getUploadedFilePath($this->attribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = static::getThumbFilePath($this->attribute, $profile);
            if (is_file($path) && !is_file($thumbPath)) {

                // setup image processor function
                if (isset($config['processor']) && is_callable($config['processor'])) {
                    $processor = $config['processor'];
                    unset($config['processor']);
                } else {
                    $processor = function (GD $thumb) use ($config) {
                        $thumb->{$this->resizeType}($config['width'], $config['height']);
                    };
                }

                $thumb = new GD($path, $config);
                call_user_func($processor, $thumb, $this->attribute);
                FileHelper::createDirectory(pathinfo($thumbPath, PATHINFO_DIRNAME), 0775, true);
                $thumb->save($thumbPath);
            }
        }

        $path = $this->getUploadedFilePath($this->attribute);
        @unlink($path);
    }
}