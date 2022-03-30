<?php

namespace App\Controller;

use Lib\Abstracts\AbstractController;
use App\Repository\PostRepository;

class UploadFile extends AbstractController
{

    public function UpdatePost($post)
    {
        $file = $_FILES;
        if($file['file']['error'] > 0 && $file['file']['error'] != 4){
            switch($file['file']['error']){
                case UPLOAD_ERR_INI_SIZE:
                    printf( "Le fichier dépasse la taille maximale par PHP (".ini_get('upload_max_filesize').").");
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    printf( "Le fichier dépasse la taille maximale du formulaire(".ini_get('post_max_size').").");
                    break;
                case UPLOAD_ERR_PARTIAL:
                    printf( "Le fichier est transféré partiellement.");
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                printf( "Une erreur s'est produite lors de l'importation du fichier.");
                    break;
            }
        }
        /* Getting file name */
        $filename = $file['file']['name'];

        /* Location */
        $location = "./build/imgs/upload/".$filename;
        $uploadOk = 1;

        if($uploadOk == 0){
            echo 0;
        }else{
            /* Upload file */
            if(move_uploaded_file($file['file']['tmp_name'], $location)){
                PostRepository::setUpdatePostimg($post,$filename);
                echo $location;
            }else{
                printf( 0);
            }
        }
    }
}