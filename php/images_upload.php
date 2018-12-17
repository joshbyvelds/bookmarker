<?php
    $image = pathinfo($_FILES['file']['name']);
    $ext = $image['extension']; // get the extension of the file
    $newname = "temp_bookmark_image" . ".".$ext;
    $target = '../img/temp/'.$newname;



    $file_name = $_FILES['file']['tmp_name'];
    list($width, $height, $type, $attr) = getimagesize( $file_name );
    if ( $width !== 480 || $height !== 360 ) {
        $target_filename = $file_name;
        $ratio = $width/$height;

        $src = imagecreatefromstring( file_get_contents( $file_name ) );
        $dst = imagecreatetruecolor( 480, 360 );
        imagecopyresampled( $dst, $src, 0, 0, 0, 0, 480, 360, $width, $height );
        imagedestroy( $src );
        imagejpeg( $dst, $target_filename ); // adjust format as needed
        imagedestroy( $dst );
    }



    move_uploaded_file( $_FILES['file']['tmp_name'], $target);

function convertImage($originalImage, $outputImage, $quality)
{
    // jpg, png, gif or bmp?
    $exploded = explode('.',$originalImage);
    $ext = $exploded[count($exploded) - 1];

    if (preg_match('/jpg|jpeg/i',$ext))
        $imageTmp=imagecreatefromjpeg($originalImage);
    else if (preg_match('/png/i',$ext))
        $imageTmp=imagecreatefrompng($originalImage);
    else if (preg_match('/gif/i',$ext))
        $imageTmp=imagecreatefromgif($originalImage);
    else if (preg_match('/bmp/i',$ext))
        $imageTmp=imagecreatefrombmp($originalImage);
    else
        return 0;

    // quality is a value from 0 (worst) to 100 (best)
    imagejpeg($imageTmp, $outputImage, $quality);
    imagedestroy($imageTmp);

    return 1;
}
?>