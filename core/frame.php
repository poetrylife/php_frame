<?php
/**
 * Created by PhpStorm.
 * User: Zheng
 * Date: 2017/9/1
 * Time: 0:40
 */
namespace core;

class frame
{
    public static $classMap = array();
    public $assign = array();
    public static function init()
    {
        spl_autoload_register('self::func_autoload');
        $route = new \core\lib\route();

        self::runAction($route->control, $route->action);

    }

    public static function runAction($control, $action)
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, ROOT . '/' . MODULE . '/control/' . $control . 'Controller.php');
        if (is_file($path))
        {
            include $path;
            $class = '\\' . MODULE . '\control\\' . $control . 'Controller';
            $obj = new $class();

            $obj->$action();
        }
        else
        {
            throw new \Exception('Control: ' . $control . ', Action: ' . $action . ' is not found');
        }
    }

    static function func_autoload($class)
    {
        if (isset(self::$classMap[$class])){
            return true;
        }

        $path = ROOT . $class . '.php';
        $class = str_replace('\\', '/', $class);
        if (false != strpos($class, 'core') >= 0) {
            if (false != strpos($class, 'common')) {
                $path = ROOT . $class . '.inc.php';
            }
        }

        if (is_file($path)) {
            include $path;
            self::$classMap[$class] = $class;
        } else{
            return false;
        }
    }

    public function assign($name, $value)
    {
        $this->assign[$name] = $value;
    }

    public function display($file)
    {
        $path = ROOT . MODULE . '/view/' . $file . '.html';
        if (is_file($path))
        {
            extract($this->assign);
            include $path;
        }
    }
}