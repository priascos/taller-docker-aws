<?php
require_once sfConfig::get('sf_root_dir').'/vendor/autoload.php';
use Aws\S3\S3Client;

class S3Connection{

    //----------------------------------------------------------------------------
    // Attributes
    //----------------------------------------------------------------------------

    private $bucket;
    private $s3;

    //----------------------------------------------------------------------------
    // Constructor
    //----------------------------------------------------------------------------

    public function __construct($bucket = NULL, $configuration){
        $this->bucket = $bucket;
        $this->s3 = new S3Client($configuration);
    }


    //----------------------------------------------------------------------------
    // Public functions
    //----------------------------------------------------------------------------

    public function getBucket(){
        return $this->bucket;
    }

    public function setBucket($bucket){
        $this->bucket = $bucket;
    }

    public function getObject($objectName){

        if ($this->bucket !== NULL) {
            $object = NULL;
            try {
              $object = $this->s3->getObject(array(
                  'Bucket' => $this->bucket,
                  'Key' => $objectName
              ));
            } catch (Exception $e) {
              $object = NULL;
            }
            return $object;

        } else {
            throw new Exception("Trying to get an Object from a NULL bucket. Use the 'setBucket' method or pass the bucket through the constructor");
        }
    }

    public function putObject($filePath, $fileName){
      $response = FALSE;

      if ($this->bucket !== NULL) {
        $response = $this->s3->putObject(array(
          'Bucket' => $this->getBucket(),
          'Key' => $fileName,
          'SourceFile' => $filePath
        ));

        if($response && $response->get('@metadata')['statusCode'] == 200){
          $response = TRUE;
        }else{
          $response = FALSE;
        }

        return $response;
        
      }else{
        throw new Exception("Trying to put an Object to a NULL bucket. Use the 'setBucket' method or pass the bucket through the constructor");
      }

    }

    public function putObjectPublic($filePath, $fileName){
      $response = FALSE;

      if ($this->bucket !== NULL) {
        $response = $this->s3->putObject(array(
          'Bucket' => $this->getBucket(),
          'Key' => $fileName,
          'SourceFile' => $filePath,
          'ACL' => 'public-read'
        ));

        if($response && $response->get('@metadata')['statusCode'] == 200){
          $response = TRUE;
        }else{
          $response = FALSE;
        }

        return $response;
        
      }else{
        throw new Exception("Trying to put an Object to a NULL bucket. Use the 'setBucket' method or pass the bucket through the constructor");
      }

    }

    public function deleteObject($objectName){

      if ($this->bucket !== NULL) {
          $result = FALSE;
          try {
            $this->s3->deleteObject(array(
              'Bucket' => $this->bucket,
              'Key'    => $objectName
            ));
            $result = TRUE;
          } catch (Exception $e) {
            $result = FALSE;
          }
          return $result;

      } else {
          throw new Exception("Trying to delete an Object from a NULL bucket. Use the 'setBucket' method or pass the bucket through the constructor");
      }
    }
}
