<?php

namespace frontend\utils;

use Exception;
use Vtiful\Kernel\Excel;
use app\models\Settings;
use Yii;
use app\models\SabanaReporteCambiosReemplazos;

class ExcelUtils
{
    const MEMORY_LIMIT_M = '2048M';
    const TIME_OUT = 600;
    const RIGHTS = 0777;
    const KEY_PATH_ROOT_DOCS = 'RUTARAIZDOCS';
    const TEMP_DIR = '/temp';

    public function excel2DB($object, $file, $skipRows = 0)
    {
        ini_set('memory_limit', ExcelUtils::MEMORY_LIMIT_M);
        try
        {
            $fpath = $this->getPathFile();
            $this->createPathPhysical($fpath);
            if (isset($file['file']['tmp_name'])) 
            {                
                $targetFile = $this->moveTemporalFile($_FILES['file']['tmp_name'],$_FILES['file']['name'], $fpath);
                set_time_limit(ExcelUtils::TIME_OUT);
                Yii::$app->db->createCommand()->truncateTable($object->tableName())->execute();                
                $data = $this->getDataTable($fpath, $file['file']['name']);                
                $indice = $this->saveData($object, $data, $skipRows);
                $totalRows = ($indice > $skipRows)? $indice - $skipRows : 0;

                return ['data' => 'ok', 'error' => $targetFile . ' - (' . $totalRows . ') Registros Procesados. '];                
            }
        }catch(Exception $ex)
        {
            throw $ex;
            return ['data' => 'notok', 'error' => $ex->getMessage()];
        }
    }

    private function createPathPhysical($fpath)
    {        
        $dirs = explode('/', $fpath);
        $dir = '';
        foreach ($dirs as $part)
        {
            $dir .= $part . '/';
            if (!is_dir($dir) && strlen($dir) > 0)
            {
                mkdir($dir, ExcelUtils::RIGHTS);
            }
        }

        return $fpath;
    }

    private function getPathFile()
    {
        $keyfolderraiz = Settings::find()->where(['key' => ExcelUtils::KEY_PATH_ROOT_DOCS])->one();

        return $keyfolderraiz->value.ExcelUtils::TEMP_DIR;
    }

    private function getDataTable($fpath, $fileName)
    {
        $config = ['path' => $fpath];
        $excel = new Excel($config);
        $data = $excel->openFile($fileName)
        ->openSheet()
        ->getSheetData();

        return $data;
    }

    private function saveRow($object, $row)
    {        
        $indice = 0;
        $obj = clone $object;
        $attributes = $obj->attributes();
        foreach($attributes as $attribute)
        {
            if($indice > 0)
            {
                $obj[$attribute] = $row[$indice - 1];
            }
            ++$indice;            
        }
       $obj->save(false);
       
    }

    private function saveData($object, $data, $skipRows)
    {
        $indice = 0;
        //TODO quitar cabeceras
        foreach($data as $row)
        {   
            if($indice >= $skipRows)
            {
                $this->saveRow($object, $row);
            }
            ++$indice;
        }

        return $indice;
    }

    private function moveTemporalFile($fileTemporal, $file, $fpath)
    {
        $tempFile = $fileTemporal;
        $targetFile = $fpath.'/'.$file;
        move_uploaded_file($tempFile, $targetFile);

        return $targetFile;
    }

    public function export($filename, $header, $data)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream'); 
        $config = ['path' => '/app/web'];                   
        $excel = new  Excel($config);
        $temporalFileName = "tempfile".date('YMdHis').".xlsx";
        $file =  $excel->fileName($temporalFileName, 'sheet1')
        ->header($header)
        ->data($data)
        ->output();
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
    }
}

?>