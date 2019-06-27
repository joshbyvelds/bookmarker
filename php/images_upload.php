<?php
    $image = pathinfo($_FILES['file']['name']);
    $ext = $image['extension']; // get the extension of the file
    $newname = "temp_bookmark_image" . ".jpg";
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
?>