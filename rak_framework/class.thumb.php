<?php
/*********************************
ThumbnailCreator
Programmed by : Daniel Thul
E-mail        : Daniel.Thul@gmx.de
*********************************/
/*SoMe InFoS:
The index must be a number between 0 and 101
If you choose for example $thumb->index = "2", the Thumbnail will be as half as greate as the original picture
For savety is cared enough, because this class will work only with datas from the type image/jpeg or image/jpg
and it checks if the index is a number and between 0 and 101. If the variable picture is no image the script will
not work and end with exit(). If $index is not a number or not between 0 and 101 it will be set to 2.
Everybody can use this class freely and give it around, but with a note, so that everybody can see who is tha author and his E-Mail adress (Daniel Thul, Daniel.Thul@gmx.de)
If you use this class, I would like if you write me an E-Mail, because I would like to see if anybody uses my class.
Feel free to report me bugs or ideas for improvements. :-)*/

class thumb{
        var $picture;
        var $index;

        function create(){
        $picture = $this->picture;
        $index = $this->index;
        $test = pathinfo($picture);

        if (file_exists($picture)){
        $test[extension] = strtolower($test[extension]);
           if ($test[extension] == 'jpg' || $test[extension] == 'jpeg'){
           $do = TRUE;
           }
           else{
           $do = FALSE;
           }
        }
        else{
        $do = FALSE;
        }

        if ($do){
           if (isset($index) && $index > 0 && $index < 101){
              $index = str_replace(",", ".", $index);
           }
           else{
                $index = 2;
           }

        $imageinfo = GetImageSize($picture);
        $thumbheight = $imageinfo[1]/$index;
        $thumbwidth = $imageinfo[0]/$index;
        $height = explode(".", $thumbheight);
        $width = explode(".", $thumbwidth);
        header("Content-type: image/jpeg");
        $dest_img = ImageCreate($width[0], $height[0]);
        $src_img = ImageCreateFromJpeg($picture);
        ImageCopyResized($dest_img, $src_img, 0, 0, 0, 0, $width[0], $height[0], ImageSX($src_img), ImageSY($src_img));
        
		ImageJpeg($dest_img);
		ImageDestroy($dest_img);
		ImageDestroy($src_img);
		}
        else{
        exit();
        }
      }
}
?>