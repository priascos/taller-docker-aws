<?php
protected function execute($arguments = array(), $options = array()) {

  $s3Connection = new S3Connection('jobs-shared-repository');
  $s3FileManager = new S3FileManager($s3Connection);

  $fileName = 'ProductInvoices.txt';
  $path = sfConfig::get('sf_data_dir') . '/src_temp/facturacion/';
  $localFilePath = $path.$fileName;

  if ($s3FileManager->fileExist($fileName)) {
      $saveFileResponse = $s3FileManager->saveS3FileLocally($fileName, $path);
      if ($saveFileResponse) {
        $this->updateProdcutBilling($options, $localFilePath);
        if(unlink($localFilePath)){
          if($s3FileManager->deleteFile($fileName)){
            $this->logBlock('Archivos eliminados exitosamente', 'COMMENT');
          }else{
            $this->logBlock("Error eliminando el archivo $fileName de S3.", 'ERROR');
          }
        }else{
            $this->logBlock("Error eliminando el archivo $fileName del servidor.", 'ERROR');
        }
      } else {
          $this->logBlock("Error guardando el archivo $fileName en $path", 'ERROR');
      }
  } else {
      $this->logBlock("Error obteniendo el archivo $fileName de S3. Por favor verifique la existencia del mismo así como su conexión a internet", 'ERROR');
  }

}
