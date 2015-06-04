<?php

namespace Wpcc;

class PngToJpg
{
    public static $picturesInExt = '.png';
    public static $picturesOutExt = '.jpg';
    public static $miniExt = '-mini';


    /**
     * This function commpress Png file to Jpg
     *
     * @param string $path
     * @param string $pathThumbnail
     * @param string $filename
     * @param bool $thumb
     */
    public static function compress($path, $pathThumbnail, $filename, $thumb = true)
    {
        try {
            $originalFile = $path . $filename . self::$picturesInExt;
            $outputFile = $path . $filename . self::$picturesOutExt;
            $image = imagecreatefrompng($originalFile);
            imagejpeg($image, $outputFile, 60);
            imagedestroy($image);
            if (true === $thumb) {
                self::imagethumb(
                    $outputFile,
                    $pathThumbnail . $filename . self::$miniExt . self::$picturesOutExt,
                    200
                );
            }
            unlink($originalFile);

        } catch (\Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }


    /**
     *
     * @param string	$image_src
     * @param string	$image_dest
     * @param int	$max_size
     * @param boolean   $expand
     * @param boolean	$square
     */
    public static function imagethumb( $image_src , $image_dest = NULL , $max_size = 100, $expand = FALSE, $square = FALSE )
    {
        if ( !file_exists($image_src) )
	{ 
	   return FALSE;
	}
        // Récupère les infos de l'image
        $fileinfo = getimagesize($image_src);
        if( !$fileinfo ) return FALSE;

        $width     = $fileinfo[0];
        $height    = $fileinfo[1];
        $type_mime = $fileinfo['mime'];
        $type      = str_replace('image/', '', $type_mime);

        if( !$expand && max($width, $height)<=$max_size && (!$square || ($square && $width==$height) ) )
        {
            if ($image_dest)
            {
                return copy($image_src, $image_dest);
            }
            else
            {
                header('Content-Type: '. $type_mime);

                return (boolean) readfile($image_src);
            }
        }
        $ratio = $width / $height; // Update dimentions
        if ( $square )
        {
            $new_width = $new_height = $max_size;
            if ( $ratio > 1 )
            {
                $src_y = 0;
                $src_x = round( ($width - $height) / 2 );
                $src_w = $src_h = $height;
            }
            else
            {               
                $src_x = 0;
                $src_y = round( ($height - $width) / 2 );
                $src_w = $src_h = $width;
            }
        }
        else
        {
            $src_x = $src_y = 0;
            $src_w = $width;
            $src_h = $height;
            if ( $ratio > 1 )
            {
                $new_width  = $max_size;
                $new_height = round( $max_size / $ratio );
            }
            else
            {               
                $new_height = $max_size;
                $new_width  = round( $max_size * $ratio );
            }
        }
        $func = 'imagecreatefrom' . $type; // Open original picture
        if ( !function_exists($func) ) 
	{
	   return FALSE;
	}
        $image_src = $func($image_src);
        $new_image = imagecreatetruecolor($new_width,$new_height);
        if ( $type=='png' )
        {
            imagealphablending($new_image,false);
            if ( function_exists('imagesavealpha') )
	    {
                imagesavealpha($new_image,true);
	    }
        }
        elseif ( $type=='gif' && imagecolortransparent($image_src) >= 0 )
        {
            $transparent_index = imagecolortransparent($image_src);
            $transparent_color = imagecolorsforindex($image_src, $transparent_index);
            $transparent_index = imagecolorallocate(
							$new_image, 
							$transparent_color['red'], 
							$transparent_color['green'], 
							$transparent_color['blue']
						);
            imagefill($new_image, 0, 0, $transparent_index);
            imagecolortransparent($new_image, $transparent_index);
        }
        imagecopyresampled(
            $new_image, $image_src,
            0, 0, $src_x, $src_y,
            $new_width, $new_height, $src_w, $src_h
        );
        $func = 'image'. $type;	// Saving pictures
        if ($image_dest)
        {
            $func($new_image, $image_dest);
        }
        else
        {
            header('Content-Type: '. $type_mime);
            $func($new_image);
        }
        imagedestroy($new_image); // Memory cleaning

        return TRUE;
    }

}
