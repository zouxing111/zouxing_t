<?php

/**
 *
 * Haypi Inc,.
 *
 */
class Filewriter_Model extends Model {

    private static $object;

    static function getInstance() {
        if (!self::$object)
            self::$object = new Filewriter_Model();
        return self::$object;
    }

    /**
     * 把一系列变量写入一个PHP文件
     * @param <string> $VariableNames, 变量的名称
     * @param <array> $Variables， $VariableNames，变量的值
     * @return boolean
     */
    static function phpVariablesWriter($VariableName, $Variables, $path) {
        $result = false;
        $content = "<?php" . "\r";
        $content.='$' . $VariableName . "=array();\r";
        foreach ($Variables as $key1 => $value1) {
            if (!is_numeric($key1)) {
                $key1 = "'" . $key1 . "'";
            }
            if (!is_array($value1)) {
                if (is_bool($value1)) {
                    $content.=($value1) ? ('$' . $VariableName . "[$key1]=true;\r") : ('$' . $VariableName . "[$key1]=false;\r");
                } else {
                    if (!is_numeric($value1)) {
                        $value1 = "'" . $value1 . "'";
                    }
                    $content.='$' . $VariableName . "[$key1]=" . $value1 . ';' . "\r";
                }
            } else {
                $content.="\r" . '$' . $VariableName . "[$key1]=array();\r";
                foreach ($value1 as $key2 => $value2) {
                    if (!is_numeric($key2)) {
                        $key2 = "'" . $key2 . "'";
                    }
                    if (!is_array($value2)) {
                        if (is_bool($value2)) {
                            $content.=($value2) ? ('$' . $VariableName . "[$key1][$key2]=true;\r") : ('$' . $VariableName . "[$key1][$key2]=false;\r");
                        } else {
                            if (!is_numeric($value2)) {
                                $value2 = "'" . $value2 . "'";
                            }
                            $content.='$' . $VariableName . "[$key1][$key2]=" . $value2 . ';' . "\r";
                        }
                    } else {
                        $content.="\r" . '$' . $VariableName . "[$key1][$key2]=array();\r";
                        foreach ($value2 as $key3 => $value3) {
                            if (!is_numeric($key3)) {
                                $key3 = "'" . $key3 . "'";
                            }
                            if (!is_array($value3)) {
                                if (is_bool($value3)) {
                                    $content.=($value3) ? ('$' . $VariableName . "[$key1][$key2][$key3]=true;\r") : ('$' . $VariableName . "[$key1][$key2][$key3]=false;\r");
                                } else {
                                    if (!is_numeric($value3)) {
                                        $value3 = "'" . $value3 . "'";
                                    }
                                    $content.='$' . $VariableName . "[$key1][$key2][$key3]=" . $value3 . ';' . "\r";
                                }
                            } else {
                                $content.="\r" . '$' . $VariableName . "[$key1][$key2][$key3]=array();\r";
                                foreach ($value3 as $key4 => $value4) {
                                    if (!is_numeric($key4)) {
                                        $key4 = "'" . $key4 . "'";
                                    }
                                    if (!is_array($value4)) {
                                        if (is_bool($value4)) {
                                            $content.=($value4) ? ('$' . $VariableName . "[$key1][$key2][$key3][$key4]=true;\r") : ('$' . $VariableName . "[$key1][$key2][$key3][$key4]=false;\r");
                                        } else {
                                            if (!is_numeric($value4)) {
                                                $value4 = "'" . $value4 . "'";
                                            }
                                            $content.='$' . $VariableName . "[$key1][$key2][$key3][$key4]=" . $value4 . ';' . "\r";
                                        }
                                    } else {
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $content.="\r";
        }
        $content.="?>";
        if (file_put_contents($path, $content)) {
            $result = true;
        }
        return $result;
    }

}

?>
