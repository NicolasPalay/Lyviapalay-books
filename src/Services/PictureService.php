<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture,
                        ?string      $folder = '',
                        ?int         $width = 300,
                        ?int         $height = 300): string
    {
        // Generate a unique name
        $filename = uniqid('lyviapalay-') . '.jpg';

        //Information about the picture
        $pictureInfos = getImageSize($picture);

        if ($pictureInfos === false) {
            throw new \Exception('The file is not an image');
        }

        //control the type of the picture
        switch ($pictureInfos['mime']) {
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
                break;
            case 'image/gif':
                $pictureSource = imagecreatefromgif($picture);
                break;
            default:
                throw new \Exception('The file is not an image');
        }

        // Resize the picture
        $pictureWidth = $pictureInfos[0];
        $pictureHeight = $pictureInfos[1];

        //orientation of the picture
        switch ($pictureWidth <=> $pictureHeight) {
            case -1:
                $squareSize = $pictureWidth;
                $srcX = 0;
                $srcY = ($pictureHeight - $squareSize) / 2;
                break;
            case 0:
                $squareSize = $pictureWidth;
                $srcX = 0;
                $srcY = 0;
                break;
            case 1:
                $squareSize = $pictureHeight;
                $srcX = ($pictureWidth - $squareSize) / 2;
                $srcY = 0;
                break;
        }

        // Resize the new picture
        $resizedPicture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $srcX, $srcY, $width, $height,
            $squareSize, $squareSize);
        $path = $this->params->get('image_directory') . $folder;

        // create the folder if it does not exist
        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }
        imagejpeg($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $filename, 75);
        $picture->move($path . '/', $filename);
        return $filename;
    }

    public function delete(string $filename, ?string $folder = '', ?int $width = 250,
                           ?int   $height = 250): string
    {
        if ($filename !== 'default.jpg') {
            $success = false;
            $path = $this->params->get('image_directory') . $folder;
            $mini = $path . '/mini' . $width . 'x' . $height . '-' . $filename;
            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }
            $original = $path . '/' . $filename;
            if (file_exists($original)) {
                unlink($mini);
                $success = true;
            }
            return $success;
        }

        return false;
    }

}