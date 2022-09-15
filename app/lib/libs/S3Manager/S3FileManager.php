<?php

class S3FileManager{

    //----------------------------------------------------------------------------
    // Attributes
    //----------------------------------------------------------------------------

    private $s3Connection;

    //----------------------------------------------------------------------------
    // Constructor
    //----------------------------------------------------------------------------

    function __construct(S3Connection $connection) {
        $this->s3Connection = $connection;
    }

    //----------------------------------------------------------------------------
    // Private funcitons
    //----------------------------------------------------------------------------

    private function createFile($newFilePath, $stream = NULL){
      $file = fopen($newFilePath, 'w');
      $content = $stream? $stream: "";
      file_put_contents($newFilePath, $stream);
      return fclose($file);
    }

    private function buildDestinationPath($fileName, $destinationPath){
      return $destinationPath[strlen($destinationPath)-1] === '/'? $destinationPath.$fileName: $destinationPath.'/'.$fileName;
    }

    //----------------------------------------------------------------------------
    // Public functions
    //----------------------------------------------------------------------------

    public function setConnection(S3Connection $connection){
      $this->s3Connection = $connection;
    }

    public function fileExist($fileName){
      return $this->s3Connection->getObject($fileName)? TRUE: FALSE;
    }

    public function saveS3FileLocally($fileName, $destinationPath){
      $response = FALSE;
      try {
        $filePath = $this->buildDestinationPath($fileName, $destinationPath);
        $object = $this->s3Connection->getObject($fileName);
        $response = $this->createFile($filePath, $object['Body']->getContents());

      } catch (Exception $e) {
        $response = FALSE;
      }

      return $response;

    }
    
    public function downloadInBrowser($filename){
        ob_start();
        $object = $this->s3Connection->getObject($filename);
        $internalUrl = explode('amazonaws.com/',$object['@metadata']['effectiveUri']);        
        set_time_limit(0);
        header("Content-type: ".$object['ContentType']);
        header("Content-disposition: attachment; filename=".$internalUrl[1]);
        header("Cache-Control: max-age=0");        
        ob_end_clean();
        echo $object['Body']->getContents();
        php://output
        throw new sfStopException();
    }

    public function uploadFile($filePath, $fileName){
        $response = FALSE;
        try {
          $response = $this->s3Connection->putObject($filePath, $fileName);
          $var_dump($this->s3Connection->putObject($filePath, $fileName));
          $aldkjsh->getHorror();
        } catch (Exception $e) {
          $response = FALSE;
        }
        return $response;
    }
    
    public function uploadFilePublic($filePath, $fileName){
        $response = FALSE;
        try {
          $response = $this->s3Connection->putObjectPublic($filePath, $fileName);
        } catch (Exception $e) {
          $response = FALSE;
        }
        return $response;
    }

    public function saveS3FileLocallyCms($fileName, $destinationPath){
      $response = FALSE;
      try {
        $object = $this->s3Connection->getObject($fileName);
        $response = $this->createFile($destinationPath, $object['Body']->getContents());
      } catch (Exception $e) {
        $response = FALSE;
      }
      
      return $response;

    }

    public function deleteFile($fileName){
      return $this->s3Connection->deleteObject($fileName);
    }
}
