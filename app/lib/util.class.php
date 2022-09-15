<?php

class Util{

    public static function formatNumber($numero,$n=2,$decimal='.',$miles=''){
        return number_format ($numero,$n,$decimal,$miles);
    }

    public static  function formatNumberMillion(
            $number,
            $n=2,
            $decimal='.',
            $thousand=',',
            $million = "´"
        ){

            if(intval($number) > 1000000){                
                $comas = number_format ($number,$n,$decimal,$thousand);            
                $sortCommas =explode(',', $comas);
                $posAp = count($sortCommas)-3;
                $strNumber = "";
                $cant = count($sortCommas);
                foreach ($sortCommas as $i => $groupThree){
                    if($i == $posAp)
                        $strNumber.=$groupThree."'";
                    else{
                        if( ($i+1) == $cant )
                            $strNumber.=$groupThree;
                        else
                            $strNumber.=$groupThree.",";
                    }
                        
                }
                return $strNumber;
            }else{
                $number = number_format((float)$number, 2, '.', ',');
                return $number;                
            }
    }

    public static function cleanString($string = ""){
        return str_replace("'", "\'", str_replace("\0",NULL,trim($string))) ;
    }

    public static function roundUp($numero, $decimales) {
        $factor = pow(10, $decimales);
        $numero = $numero * $factor;
        $numero = ceil($numero) / $factor;

        return $numero;
    }

    public static function cleanStringNumber($string = "") {
        return str_replace(',', '.', self::cleanString($string));
    }

    public static function cleanStringWithQuote($string = "") {
        return str_replace('"', null, self::cleanString($string));
    }

    public static function getSiebelDate($fecha = "") {
        $partes = explode(" ", $fecha);
        $partesFecha = explode("/", $partes[0]);
        return $partesFecha[2] . "-" . $partesFecha[1] . "-" . $partesFecha[0];
    }

    public static function dateFormatSql($fecha = ""){
        if($fecha == "")
            return date('Y-m-d');
        else            
            return substr($fecha,0,4).'-'.substr($fecha, 4,2).'-'.substr($fecha, 6,2);
    }
    /*
     * @require Doctrine Object
     * @return Return an string with Ids  
     */
    public static function getIdsStr($objectsDoctrine) {
        $ids = array();
        if (is_array($objectsDoctrine)) {
            foreach ($objectsDoctrine as $obj)
                $ids[] = $obj;
        } else {
            foreach ($objectsDoctrine as $obj)
                $ids[] = $obj->getId();
        }
        return implode(',', $ids);
    }

    /*
     * @require Doctrine Object
     * @return Return an array with Ids  
     */
    public static function getIdsArray($objectsDoctrine) {
        $ids = array(0);
        if (is_array($objectsDoctrine)) {
            foreach ($objectsDoctrine as $obj)
                $ids[] = $obj;
        } else {
            foreach ($objectsDoctrine as $obj)
                $ids[] = $obj->getId();
        }
        return $ids;
    }    
    //Solo funciona con colecciones de Doctrine que tengan:
    // 1. columna id en primary key
    // 2. columna nombre
    public static function getArrayKeyAndValue($objectsDoctrine) {

        $keyVsValue=array();
        foreach($objectsDoctrine as $object)
            $keyVsValue[$object->getId()] = $object->getNombre();
        return $keyVsValue;
    }

    public static function array_insert(&$array, $value, $index){
        return $array = array_merge(array_splice($array, max(0, $index - 1)), array($value), $array);
    }

    public static function nombremes($mes){
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
        return ucwords($nombre);
    }

    public static function viewArray($array,$type=NULL){
        if($type == 'json'){
            echo json_encode($array);
        }else{
            print "<pre>";
            echo print_r($array);
            print "</pre>";
            echo "linea del error ".__LINE__." en el archivo ".__FILE__;
            viewArrayInfo();
        }
    }

    public function getMaestroReferencias(){
        $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getMaestroReferencias");
        $curl->createCurl();
        $maestroData = $curl->getDataResult();
        return $maestroData;
    }

    public function getClientes($value){
        $dataPost = "value=$value";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getClientes");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $clientes = $curl->getDataResult();
        return $clientes;
    }

    public function getGrupoPais($value, $value2){
        $dataPost = "value=$value&value2=$value2";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getGrupoPais");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $grupoPais = $curl->getDataResult();
        return $grupoPais;
    }

    public function getGrupoGlobal($value){
        
        $dataPost = "value=$value&";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getGrupoGlobal");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $grupoGlobal = $curl->getDataResult();
        return $grupoGlobal;
    }

    public function getCountries($value){
        
        $dataPost = "value=$value&";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getCountries");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $Countries = $curl->getDataResult();
        return $Countries;
    }

    public function getIndustries($value){
        
        $dataPost = "value=$value&";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getIndustries");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $Countries = $curl->getDataResult();
        return $Countries;
    }

    public function getPlantasCliente($value, $idCliente){
        $dataPost = "value=$value&idCliente=$idCliente";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getPlantasCliente");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $plantas = $curl->getDataResult();
        return $plantas;
    }

    public function getUsuariosIngVentas(){
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getUsuariosIngVentas");
        $curl->createCurl();
        $usuarios = $curl->getDataResult();
        return $usuarios;
    }

    public function getListadoIndustrias(){
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getListadoIndustrias");
        $curl->createCurl();
        $industrias = $curl->getDataResult();
        return $industrias;
    }

    public function getUsuarioIngVentas($id){
        $dataPost = "id=$id";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getUsuarioIngVentas");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $usuario = $curl->getDataResult();
        return $usuario;
    }

    public function getNombrePlantaCliente($id){
        $dataPost = "id=$id";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') ."/extranet/getNombrePlantaCliente");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $usuario = $curl->getDataResult();
        return $usuario;
    }


    public function getNombresPlantas($idConvenio,$codigoDistribuidor){
        $dataPost ="idConvenio=$idConvenio&codigoDistribuidor=$codigoDistribuidor&";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getNombresPlantas");
        $curl->setPost($dataPost);
        $curl->createCurl();
        $nombresPlantas = $curl->getDataResult();
        return $nombresPlantas;
    }

    public function getDateInWords(){
        setlocale(LC_ALL, 'es_ES.UTF-8');
        $rawDate = date('d.F.Y');
        $finalDate = str_replace('.', ' de ', $rawDate);
        return $finalDate;
    }

    public function getClientesDataForBaseClientesPlantasReport($codeDistribuidor){
        $postParams = "codeDistribuidor=$codeDistribuidor";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getClientesDataByDistribuidorCode");
        $curl->setPost($postParams);
        $curl->createCurl();
        $data = $curl->getDataResult();

        return $data;
    }

    public function getClientesWithPlantasByDistribuidorCode($codeDistribuidor){
      $postParams = "codeDistribuidor=$codeDistribuidor";
      $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getClientesWithPlantasByDistribuidorCode");
      $curl->setPost($postParams);
      $curl->createCurl();

      $rawClientesPlantasArray = $curl->getDataResult();
      $clientesPlantasArray = array();
      $currentClienteNombre = NULL;

      foreach ($rawClientesPlantasArray as $clientePlanta) {
        if(!$currentClienteNombre || $clientePlanta['nombre_cliente'] != $currentClienteNombre){
          $currentClienteNombre = $clientePlanta['nombre_cliente'];
          $clientesPlantasArray[$currentClienteNombre] = array(
            'id' => $clientePlanta['id_cliente'],
            'plantas' => array()
          );
        }
        $clientesPlantasArray[$currentClienteNombre]['plantas'][$clientePlanta['nombre_planta']] = $clientePlanta['id_planta'];
      }
      return $clientesPlantasArray;
    }

    public function getTipoConvenios(){
      $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getTiposConvenio");
      $curl->createCurl();

      return $curl->getDataResult();
    }

    public function getRegisteredBillingDd($codeDistribuidor, $fechaIni, $fechaFin){
        $postParams = "codeDistribuidor=$codeDistribuidor&fechaIni=$fechaIni&fechaFin=$fechaFin";
        $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/getRegisteredBillingDd");
        $curl->setPost($postParams);
        $curl->createCurl();

        return $curl->getDataResult();
    }

    
    public function getFromBi($module, $action, $params = array()){
        $postParams = "";
        foreach ($params as $key => $value) 
            $postParams .= $key. "=" .$value;
        
        $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/$module/$action");
        $curl->setPost($postParams);
        $curl->createCurl();
        return $curl->getDataResult();
    }

    public function loadSalesBI($code, $fileName, $currency){
      $postParams = "code=$code&filename=$fileName&moneda=$currency";
      $curl = new Curl(sfConfig::get('app_skf_bi_url') . "/extranet/loadSales");
      $curl->setPost($postParams);
      $curl->createCurl();

      return $curl->getResponseService();
    }

    public function arrayColumns($input = null, $columnKey = null, $indexKey = null){
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */

        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }
    
    public function getConnectionBucketS3($nameBucket){
        if($nameBucket != '')
            return new S3Connection($nameBucket, array(
                'region' => sfConfig::get('app_aws_region'),
                'version' => sfConfig::get('app_aws_version'),
                    'credentials' => array(
                        'key' => sfConfig::get('app_aws_key'),
                        'secret' => sfConfig::get('app_aws_secret')
                    )
                ));
        else
           throw new Exception('Name Bucket REQUIRED! ', 0);            
    }

    public function getDataFromFileTxt($filename , $extension, $local = TRUE){
        if($local){
            if (($handle = fopen(sfConfig::get('sf_upload_dir') . '/' . $filename . $extension, "r")) !== FALSE) {
                $data = array();
                while (!feof($handle)) {
                    $data[] = explode("\t", fgets($handle));
                }
                return $data;
            }else{
                return array();
            }    
        }else{
            return array();
        }
        

    }

/**
 * function xml2array
 *
 * This function is part of the PHP manual.
 *
 * The PHP manual text and comments are covered by the Creative Commons 
 * Attribution 3.0 License, copyright (c) the PHP Documentation Group
 *
 * @author  k dot antczak at livedata dot pl 
 * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
 * @license http://www.php.net/license/index.php#doc-lic
 * @license http://creativecommons.org/licenses/by/3.0/
 * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
 */
function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? self::xml2array ( $node ) : $node;

    return $out;
}























































}
?>
