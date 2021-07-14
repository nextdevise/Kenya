<?php
namespace cocolait\sql;
class Backup{
    protected static $instance;

    protected $content;

    protected $pdo;

    protected $options;

    protected $fileName = '';

    const DIR_SEP = DIRECTORY_SEPARATOR;

    protected function __construct($options = []){
        if (!extension_loaded('pdo')) {
            $this->throwException("pdo 扩展未加载");
        }
        $this->pdo = \cocolait\sql\driver\Database::instance($options);
        $this->options = $options;
    }

    /**
     * 外部调用获取实列
     * @param array $options
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }

    //获取单表的基本信息
    public function getTableInfo($table=''){
        $result = $this->pdo->query('SHOW TABLE STATUS FROM '. $this->options['database'].' WHERE Name=\''.$table.'\'');
        $num_rows = count($result);
        if($num_rows>0){
            return $num_rows;
        }else{
            return false;
        }
    }

    //获取所有的表名
    public function getMysqlTableNameArray(){
        return $this->pdo->query("SHOW TABLE STATUS FROM {$this->options['database']}");
    }

    //获取创建表的信息
    public function getCreateTableInfo($table=''){
        return $this->pdo->query("SHOW CREATE TABLE ".$table);
    }

    //获取表插入的数据
    protected function getTableField($table) {
        $data = $this->pdo->query("SELECT * FROM {$table}");
        $str = "\r\n /* 插入 {$table} 表的数据 */";
        if ($data) {
            foreach ($data as $v) {
                $field = '';
                foreach ($v as $vs) {
                    $field .= "'$vs'" . ",";
                }
                $field = rtrim($field,",");
                $str .= "\r\n INSERT INTO {$table} VALUES ({$field});";
            }
            return $str;
        } else {
            return '';
        }
    }


    /**
     * 备份
     * @param String $path 备份路径
     * @param array $tableArray 需要备份的表集合 不传递备份所有表
     * @param bool $bool  是否同时备份数据 默认备份
     * @param array $options 扩展参数 ['is_compress' => '是否写入内容文件进行压缩','is_download' => '是否进行下载']
     * @return string
     * @throws \Exception
     */
    public function backUp($path, $tableArray = [], $bool = false, $options = []){
        $start_time = time();
        if (!$tableArray) {
            $tableArray = $this->getMysqlTableNameArray();
            $new_data = [];
            foreach ($tableArray as $k => $v) {
                $new_data[] = $v['Name'];
            }
            $tableArray = $new_data;
        }
        if (!$path) {
            $this->throwException("请传递备份路径-path参数");
        }

        //文件注释区域
        $this->content ='-- 文登税务资产管理系统'."\n";
        $this->content.='-- 数据库备份'."\n";
        $this->content.='-- 字符集 UTF-8' . "\n";
        $backUpdate = date("Y 年 m 月 d 日 H:i:s");
        $this->content.= '-- 生成日期: '. $backUpdate. ';' . "/* MySQLReback Separation */". "\n\n";

        foreach($tableArray as $table){
            $this->content .= "DROP TABLE IF EXISTS ". $table .";" . "/* MySQLReback Separation */"."\n";
            //获取表的基本信息
            if($this->getTableInfo($table)){
                $CreateTableInfo = $this->getCreateTableInfo($table);
                if($CreateTableInfo){
                    foreach($CreateTableInfo as $v){
                        $this->content.= $v['Create Table'].';' . "/* MySQLReback Separation */" ."\n";
                        $this->content.= "\n";
                    }
                }
            }
            //是否备份数据
            if ($bool) {
                //备份数据
                $this->getTableField($table);
                $this->content .= $this->getTableField($table);
                $this->content .= "\n\n";
            }
        }

        //写入文件
        $this->writeContentToFile($path,$options);
        return ['code' => 200, 'msg' => '备份成功','time' => (time()-$start_time) . "秒",'fileName' => $this->fileName];
    }

    /**
     * 获取内容
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 递归创建目录
     * @param $dir
     * @return bool
     */
    protected  function  directory($dir)
    {
        return  is_dir ($dir) or $this->directory(dirname($dir)) and  mkdir ($dir, 0777);
    }


    /**
     * 获取文件内容
     * @param string $fileName 文件名
     * @param string $dirName  目录名
     * @return $this
     * @throws \Exception
     */
    public function getFileContent($fileName, $dirName) {
        $this->content = '';
        $fileName = $this->trimPath($dirName . self::DIR_SEP . $fileName);
        if (is_file($fileName)) {
            $ext = strrchr($fileName, '.');
            if ($ext == '.sql') {
                $this->content = file_get_contents($fileName);
            } elseif ($ext == '.gz') {
                $this->content = implode('', gzfile($fileName));
            } else {
                $this->throwException('无法识别的文件格式!');
            }
        } else {
            $this->throwException('文件不存在!');
        }
        return $this;
    }

    /**
     * 替换路径的符号
     * @param $path
     * @return mixed
     */
    protected function trimPath($path) {
        return str_replace(array('/', '\\', '//', '\\\\'), self::DIR_SEP, $path);
    }

    /**
     * 内容写入到文件或者写入压缩包
     * @param string $path 需要被写入的路径
     * @param array $options  扩展参数
     * @throws \Exception
     */
    protected function writeContentToFile($path, $options = []) {
        $recognize = $this->options['database'];
        $dir = $this->trimPath($path);
        $return_file_name = self::DIR_SEP . $recognize . '_' . date('YmdHis') . '_' . mt_rand(100000000, 999999999);
        $fileName = $dir . $return_file_name;
        $path = $this->directory($dir);
        if ($path !== true) {
            $this->throwException("无法创建备份目录目录 '$fileName'");
        }
        // 是否对文件进行压缩
        $isCompress = isset($options['is_compress']) ? $options['is_compress'] : 0;
        if ($isCompress == 0) {
            $fileName .= '.sql';
            if (!file_put_contents($fileName, $this->content, LOCK_EX)) {
                $this->throwException('写入文件失败,请检查磁盘空间或者权限!');
            }
            $this->fileName = $return_file_name;
        } else {
            if (function_exists('gzwrite')) {
                $fileName .= '.gz';
                if ($gz = gzopen($fileName, 'wb')) {
                    gzwrite($gz, $this->content);
                    gzclose($gz);
                } else {
                    $this->throwException('写入文件失败,请检查磁盘空间或者权限!');
                }
            } else {
                $this->throwException('没有开启gzip扩展!');
            }
            $this->fileName = $return_file_name;
        }
        // 是否进行下载
        $isDownload = isset($options['is_download']) ? $options['is_download'] : 0;
        if ($isDownload) {
            $this->downloadFile($fileName);
        }
        $this->fileName = $return_file_name;
    }

    /**
     * 还原数据库
     * @param string $fileName 文件名
     * @param string $dirName  备份的目录名
     * @return array
     * @throws \Exception
     */
    public function recover($fileName,$dirName) {
        $start_time = time();
        $this->getFileContent($fileName, $dirName);
        if (!empty($this->content)) {
            $bool = $this->pdo->query("SELECT * FROM information_schema.SCHEMATA where SCHEMA_NAME='{$this->options['database']}'");
            if (!$bool) $this->throwException('不存在的数据库!' . $this->options['database']);

            $content = explode(';/* MySQLReback Separation */', $this->content);
            if (is_array($content) && isset($content[1])) {
                foreach ($content as $i => $sql) {
                    if ($i > 0) {
                        $sql = trim($sql);
                        if (!empty($sql)) {
                            try {
                                $rs = $this->pdo->query($sql);
                                if ($rs) {
                                    if (strstr($sql, 'CREATE DATABASE')) {
                                        $dbNameArr = sscanf($sql, 'CREATE DATABASE %s');
                                        $dbName = trim($dbNameArr[0], '`');
                                        $this->pdo->query($dbName);
                                    }
                                }
                            }catch(\PDOException $e){
                                $this->throwException('备份文件被损坏-' . "ERROR!:".$e->getMessage());
                            }
                        }
                    }
                }
            } else {
                $this->throwException('读取备份文件无备份内容!');
            }

        } else {
            $this->throwException('无法读取备份文件!');
        }
        return ['code' => 200, 'msg' => '还原成功','time' => (time()-$start_time) . "秒",'fileName' => $fileName];
    }


    /**
     * 下载文件
     * @param $fileName
     */
    protected function downloadFile($fileName) {
        ob_end_clean();
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($fileName));
        header('Content-Disposition: attachment; filename=' . basename($fileName));
        readfile($fileName);
    }

    /**
     * 抛出异常
     * @param $error
     * @throws \Exception
     */
    protected function throwException($error) {
        throw new \Exception($error);
    }
}