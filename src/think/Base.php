<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think;

use Opis\Closure\SerializableClosure;
use think\exception\ClassNotFoundException;
use think\exception\HttpException;

/**
 * App 基础类
 */
abstract class Base extends Container
{
    const VERSION = '5.2.0RC1';

    /**
     * 应用名称
     * @var string
     */
    protected $name;

    /**
     * 应用调试模式
     * @var bool
     */
    protected $appDebug = true;

    /**
     * 是否多应用模式
     * @var bool
     */
    protected $multi = false;

    /**
     * 是否自动多应用
     * @var bool
     */
    protected $auto = false;

    /**
     * 应用映射
     * @var array
     */
    protected $map = [];

    /**
     * 应用开始时间
     * @var float
     */
    protected $beginTime;

    /**
     * 应用内存初始占用
     * @var integer
     */
    protected $beginMem;

    /**
     * 应用类库顶级命名空间
     * @var string
     */
    protected $rootNamespace = 'app';

    /**
     * 当前应用类库命名空间
     * @var string
     */
    protected $namespace = '';

    /**
     * 应用根目录
     * @var string
     */
    protected $rootPath = '';

    /**
     * 框架目录
     * @var string
     */
    protected $thinkPath = '';

    /**
     * 应用基础目录
     * @var string
     */
    protected $basePath = '';

    /**
     * 应用类库目录
     * @var string
     */
    protected $appPath = '';

    /**
     * 运行时目录
     * @var string
     */
    protected $runtimePath = '';

    /**
     * 配置目录
     * @var string
     */
    protected $configPath = '';

    /**
     * 路由目录
     * @var string
     */
    protected $routePath = '';

    /**
     * URL
     * @var string
     */
    protected $urlPath = '';

    /**
     * 配置后缀
     * @var string
     */
    protected $configExt = '.php';

    /**
     * 是否需要事件响应
     * @var bool
     */
    protected $withEvent = true;

    /**
     * 设置是否使用事件机制
     * @access public
     * @param  bool $event
     * @return $this
     */
    public function withEvent(bool $event)
    {
        $this->withEvent = $event;
        return $this;
    }

    /**
     * 设置应用路径
     * @access public
     * @param  string $path 应用目录
     * @return $this
     */
    public function path(string $path)
    {
        $this->appPath = $path;
        return $this;
    }

    /**
     * 开启应用调试模式
     * @access public
     * @param  bool $debug 开启应用调试模式
     * @return $this
     */
    public function debug(bool $debug = true)
    {
        $this->appDebug = $debug;
        return $this;
    }

    /**
     * 是否为调试模式
     * @access public
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->appDebug;
    }

    /**
     * 设置应用名称
     * @access public
     * @param  string $name 应用名称
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 设置应用命名空间
     * @access public
     * @param  string $namespace 应用命名空间
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * 设置应用根命名空间
     * @access public
     * @param  string $rootNamespace 应用命名空间
     * @return $this
     */
    public function setRootNamespace(string $rootNamespace)
    {
        $this->rootNamespace = $rootNamespace;
        return $this;
    }

    /**
     * 获取框架版本
     * @access public
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * 获取应用名称
     * @access public
     * @return string
     */
    public function getName(): string
    {
        return $this->name ?: '';
    }

    /**
     * 获取应用根目录
     * @access public
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * 获取应用基础目录
     * @access public
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * 获取当前应用目录
     * @access public
     * @return string
     */
    public function getAppPath(): string
    {
        return $this->appPath;
    }

    /**
     * 获取应用运行时目录
     * @access public
     * @return string
     */
    public function getRuntimePath(): string
    {
        return $this->runtimePath;
    }

    /**
     * 获取核心框架目录
     * @access public
     * @return string
     */
    public function getThinkPath(): string
    {
        return $this->thinkPath;
    }

    /**
     * 获取路由目录
     * @access public
     * @return string
     */
    public function getRoutePath(): string
    {
        return $this->routePath;
    }

    /**
     * 获取应用配置目录
     * @access public
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->configPath;
    }

    /**
     * 获取配置后缀
     * @access public
     * @return string
     */
    public function getConfigExt(): string
    {
        return $this->configExt;
    }

    /**
     * 获取应用类基础命名空间
     * @access public
     * @return string
     */
    public function getRootNamespace(): string
    {
        return $this->rootNamespace;
    }

    /**
     * 获取应用类库命名空间
     * @access public
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * 获取应用开启时间
     * @access public
     * @return float
     */
    public function getBeginTime(): float
    {
        return $this->beginTime;
    }

    /**
     * 获取应用初始内存占用
     * @access public
     * @return integer
     */
    public function getBeginMem(): int
    {
        return $this->beginMem;
    }

    /**
     * 初始化应用
     * @access public
     * @return $this
     */
    public function initialize()
    {
        $this->beginTime = microtime(true);
        $this->beginMem  = memory_get_usage();

        $this->parse();

        $this->init();

        return $this;
    }

    /**
     * 分析应用（参数）
     * @access protected
     * @return void
     */
    protected function parse(): void
    {
        if (is_file($this->rootPath . '.env')) {
            $this->env->load($this->rootPath . '.env');
        }

        $this->parseAppName();

        $this->parsePath();

        if (!$this->namespace) {
            $this->namespace = $this->multi ? $this->rootNamespace . '\\' . $this->name : $this->rootNamespace;
        }

        $this->configExt = $this->env->get('config_ext', '.php');
    }

    /**
     * 分析当前请求的应用名
     * @access protected
     * @return void
     */
    protected function parseAppName(): void
    {
        $this->urlPath = $this->request->path();

        if ($this->auto && $this->urlPath) {
            // 自动多应用识别
            $name = current(explode('/', $this->urlPath));

            if (isset($this->map[$name])) {
                if ($this->map[$name] instanceof \Closure) {
                    call_user_func_array($this->map[$name], [$this]);
                } else {
                    $this->name = $this->map[$name];
                }
            } elseif ($name && false !== array_search($name, $this->map)) {
                throw new HttpException(404, 'app not exists:' . $name);
            } else {
                $this->name = $name ?: $this->defaultApp;
            }
        } elseif ($this->multi) {
            $this->name = $this->name ?: $this->getScriptName();
        }

        $this->request->setApp($this->name ?: '');
    }

    /**
     * 分析应用路径
     * @access protected
     * @return void
     */
    protected function parsePath(): void
    {
        if ($this->multi) {
            $this->runtimePath = $this->rootPath . 'runtime' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR;
            $this->routePath   = $this->rootPath . 'route' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR;
        } else {
            $this->runtimePath = $this->rootPath . 'runtime' . DIRECTORY_SEPARATOR;
            $this->routePath   = $this->rootPath . 'route' . DIRECTORY_SEPARATOR;
        }

        if (!$this->appPath) {
            $this->appPath = $this->multi ? $this->basePath . $this->name . DIRECTORY_SEPARATOR : $this->basePath;
        }

        $this->configPath = $this->rootPath . 'config' . DIRECTORY_SEPARATOR;

        $this->env->set([
            'think_path'   => $this->thinkPath,
            'root_path'    => $this->rootPath,
            'app_path'     => $this->appPath,
            'runtime_path' => $this->runtimePath,
            'route_path'   => $this->routePath,
            'config_path'  => $this->configPath,
        ]);
    }

    /**
     * 初始化应用
     * @access public
     * @return void
     */
    public function init(): void
    {
        // 加载初始化文件
        if (is_file($this->runtimePath . 'init.php')) {
            include $this->runtimePath . 'init.php';
        } else {
            $this->load();
        }

        if ($this->config->get('app.exception_handle')) {
            Error::setExceptionHandler($this->config->get('app.exception_handle'));
        }

        // 设置开启事件机制
        $this->event->withEvent($this->withEvent);

        // 监听AppInit
        $this->event->trigger('AppInit');

        $this->debugModeInit();

        date_default_timezone_set($this->config->get('app.default_timezone', 'Asia/Shanghai'));
    }

    /**
     * 加载应用文件和配置
     * @access protected
     * @return void
     */
    protected function load(): void
    {
        if ($this->multi && is_file($this->basePath . 'event.php')) {
            $this->loadEvent(include $this->basePath . 'event.php');
        }

        if (is_file($this->appPath . 'event.php')) {
            $this->loadEvent(include $this->appPath . 'event.php');
        }

        if ($this->multi && is_file($this->basePath . 'common.php')) {
            include_once $this->basePath . 'common.php';
        }

        if (is_file($this->appPath . 'common.php')) {
            include_once $this->appPath . 'common.php';
        }

        include $this->thinkPath . 'helper.php';

        if ($this->multi && is_file($this->basePath . 'middleware.php')) {
            $this->middleware->import(include $this->basePath . 'middleware.php');
        }

        if (is_file($this->appPath . 'middleware.php')) {
            $this->middleware->import(include $this->appPath . 'middleware.php');
        }

        if ($this->multi && is_file($this->basePath . 'provider.php')) {
            $this->bind(include $this->basePath . 'provider.php');
        }

        if (is_file($this->appPath . 'provider.php')) {
            $this->bind(include $this->appPath . 'provider.php');
        }

        $files = [];

        if (is_dir($this->configPath)) {
            $files = glob($this->configPath . '*' . $this->configExt);
        }

        if ($this->multi) {
            if (is_dir($this->appPath . 'config')) {
                $files = array_merge($files, glob($this->appPath . 'config' . DIRECTORY_SEPARATOR . '*' . $this->configExt));
            } elseif (is_dir($this->configPath . $this->name)) {
                $files = array_merge($files, glob($this->configPath . $this->name . DIRECTORY_SEPARATOR . '*' . $this->configExt));
            }
        }

        foreach ($files as $file) {
            $this->config->load($file, pathinfo($file, PATHINFO_FILENAME));
        }
    }

    /**
     * 调试模式设置
     * @access protected
     * @return void
     */
    protected function debugModeInit(): void
    {
        // 应用调试模式
        if (!$this->appDebug) {
            $this->appDebug = $this->env->get('app_debug', false);
        }

        if (!$this->appDebug) {
            ini_set('display_errors', 'Off');
        } elseif (PHP_SAPI != 'cli') {
            //重新申请一块比较大的buffer
            if (ob_get_level() > 0) {
                $output = ob_get_clean();
            }
            ob_start();
            if (!empty($output)) {
                echo $output;
            }
        }
    }

    /**
     * 注册应用事件
     * @access protected
     * @return void
     */
    protected function loadEvent(array $event): void
    {
        if (isset($event['bind'])) {
            $this->event->bind($event['bind']);
        }

        if (isset($event['listen'])) {
            $this->event->listenEvents($event['listen']);
        }

        if (isset($event['subscribe'])) {
            $this->event->subscribe($event['subscribe']);
        }
    }

    /**
     * 获取自动多应用模式下的实际URL Path
     * @access public
     * @return string
     */
    public function getRealPath(): string
    {
        $path = $this->urlPath;

        if ($path && $this->auto) {
            $path = substr_replace($path, '', 0, strpos($path, '/') ? strpos($path, '/') + 1 : strlen($path));
        }

        return $path;
    }

    abstract public function run();

    /**
     * 解析应用类的类名
     * @access public
     * @param  string $layer  层名 controller model ...
     * @param  string $name   类名
     * @return string
     */
    public function parseClass(string $layer, string $name): string
    {
        $name  = str_replace(['/', '.'], '\\', $name);
        $array = explode('\\', $name);
        $class = self::parseName(array_pop($array), 1);
        $path  = $array ? implode('\\', $array) . '\\' : '';

        return $this->namespace . '\\' . $layer . '\\' . $path . $class;
    }

    /**
     * 获取应用根目录
     * @access protected
     * @return string
     */
    protected function getDefaultRootPath(): string
    {
        $path = dirname(dirname(dirname(dirname($this->thinkPath))));

        return $path . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取当前运行入口名称
     * @access protected
     * @return string
     */
    protected function getScriptName(): string
    {
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $file = $_SERVER['SCRIPT_FILENAME'];
        } elseif (isset($_SERVER['argv'][0])) {
            $file = realpath($_SERVER['argv'][0]);
        }

        return isset($file) ? pathinfo($file, PATHINFO_FILENAME) : $this->defaultApp;
    }

    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @access public
     * @param  string  $name 字符串
     * @param  integer $type 转换类型
     * @param  bool    $ucfirst 首字母是否大写（驼峰规则）
     * @return string
     */
    public static function parseName(string $name = null, int $type = 0, bool $ucfirst = true): string
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);
            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }

        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }

    /**
     * 获取类名(不包含命名空间)
     * @access public
     * @param  string|object $class
     * @return string
     */
    public static function classBaseName($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }

    /**
     * 创建工厂对象实例
     * @access public
     * @param  string $name         工厂类名
     * @param  string $namespace    默认命名空间
     * @return mixed
     */
    public static function factory(string $name, string $namespace = '', ...$args)
    {
        $class = false !== strpos($name, '\\') ? $name : $namespace . ucwords($name);

        if (class_exists($class)) {
            return Container::getInstance()->invokeClass($class, $args);
        }

        throw new ClassNotFoundException('class not exists:' . $class, $class);
    }

    public static function serialize($data): string
    {
        SerializableClosure::enterContext();
        SerializableClosure::wrapClosures($data);
        $data = \serialize($data);
        SerializableClosure::exitContext();
        return $data;
    }

    public static function unserialize(string $data)
    {
        SerializableClosure::enterContext();
        $data = \unserialize($data);
        SerializableClosure::unwrapClosures($data);
        SerializableClosure::exitContext();
        return $data;
    }
}