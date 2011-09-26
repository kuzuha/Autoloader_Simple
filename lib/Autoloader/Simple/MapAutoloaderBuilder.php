<?php

class Autoloader_Simple_MapAutoloaderBuilder {

    /**
     * @param string $directoryPath
     */
    static function build($directoryPath) {
        file_put_contents("$directoryPath/Autoloader.php", self::getOutput($directoryPath));
    }

    /**
     * @param string $directoryPath
     * @return string
     */
    static function getOutput($directoryPath) {
        $directoryPath = realpath($directoryPath);
        $output = file_get_contents(dirname(__FILE__) . '/MapAutoloader.php');
        $output .= "\nAutoloader_Simple_MapAutoloader::add(array(\n";
        $iterator = new RecursiveDirectoryIterator(realpath($directoryPath));
        $iterator = new RecursiveIteratorIterator($iterator);
        foreach ($iterator as $path => $info) {
            if ($info->isFile() && preg_match('/\.php$/', $path)) {
                foreach (self::getClassNames($path) as $className) {
                    $relative_path = mb_substr($path, mb_strlen($directoryPath));
                    $className = preg_replace('/\\\\/', '\\\\', $className);
                    $output .= "    '$className' => dirname(__FILE__) . '$relative_path',\n";
                }
            }
        }
        $output .= "));\n";
        return $output;
    }

    /**
     * @param string $filePath
     * @return array
     */
    static function getClassNames($filePath) {
        $classNames = array();

        $tokens = token_get_all(file_get_contents($filePath));
        $supportNamespace = defined('T_NAMESPACE');
        $namespaceFound = false;
        $classFound = false;
        $currentNamespace = '';

        foreach ($tokens as $token) {
            if ($namespaceFound) {
                if (is_array($token)) {
                    if ($token[0] === T_STRING ||
                            ($supportNamespace && $token[0] === T_NS_SEPARATOR)) {
                        $currentNamespace .= $token[1];
                    }
                } else {
                    //semicolon
                    $namespaceFound = false;
                }
                continue;
            }
            if ($classFound) {
                if (is_array($token) && $token[0] === T_STRING) {
                    $className = $currentNamespace ? "$currentNamespace\\" : '';
                    $className .= $token[1];
                    array_push($classNames, $className);
                    $classFound = false;
                }
                continue;
            }
            if (is_array($token)) {
                $classFound = $token[0] === T_CLASS || $token[0] === T_INTERFACE;
                if ($supportNamespace && $token[0] === T_NAMESPACE) {
                    $namespaceFound = true;
                    $currentNamespace = '';
                }
            }
        }
        return $classNames;
    }

}
