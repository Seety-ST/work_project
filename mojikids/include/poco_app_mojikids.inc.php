<?php
/**
 * POCO_APP �̳��˿�ܺ��Ļ��࣬��װ��Ӧ�ó���Ļ����������̺ͳ�ʼ����������ΪӦ�ó����ṩһЩ��������
 *
 * ��Ҫ�����������
 * - ��ʼ�����л���
 * - ΪӦ�ó����ṩ��������
 *
 * @author ERLDY
 * @package core
 */

class POCO_APP_MOJIKIDS
{
    /**
     * Ӧ�ó���Ļ�������
     *
     * @var array
     */
    protected $_app_config;

    /**
     * ��ǰӦ����Ŀ����
     *
     * @var string
     */
    public static $_app_name;

    /**
     * ���캯��
     *
     * @param array $app_config
     *
     * ����Ӧ�ó������
     */
    protected function __construct(array $app_config)
    {
        /**
         * ��ʼ�����л���
         */
        // ��ֹ magic quotes
        //set_magic_quotes_runtime(0);

        // ���� magic quotes �Զ�ת���������
        if (get_magic_quotes_gpc())
        {
            $in = array(& $_GET, & $_POST, & $_COOKIE, & $_REQUEST);
            while (list ($k, $v) = each($in))
            {
                foreach ($v as $key => $val)
                {
                    if (! is_array($val))
                    {
                        $in[$k][$key] = stripslashes($val);
                        continue;
                    }
                    $in[] = & $in[$k][$key];
                }
            }
            unset($in);
        }

        // �����쳣������
        set_exception_handler(array($this, 'exception_handler'));

        // ��ʼ��Ӧ�ó�������
        //self::cleanIni('/');
        $this->_app_config = $app_config;
        $this->_initConfig();
		self::$_app_name = $app_config['APP_NAME'];

        // ����������·��
        POCO::import(G_POCO_APP_PATH . '/model');
        POCO::import($app_config['INCLUDE_DIR']);

        // ����ģ�峣��
        if (!defined('G_POCO_APP_TEMPLATE_DIR'))
        {
        	define('G_POCO_APP_TEMPLATE_DIR', $app_config['ROOT_DIR']);
        }

        // ע��Ӧ�ó������
        POCO::register($this, 'app');
    }

    /**
     * ��������
     */
    public function __destruct()
    {

    }

    /**
	 * ����ģ����ͼ����
	 * ����Ҫ�Լ��ֶ�����ģ�����ֱ��ʹ�ø÷������ɻ��ģ�����
	 *
	 * @param string $tpl_filename ��ͼģ���ļ�
	 * @param boolean $new_obj     ǿ�������µĶ���
	 *
	 * @return object
	 */
	public function getView($tpl_filename = '', $new_obj = false)
	{
		if ($new_obj === true)
		{
			return new SmartTemplate($tpl_filename);
		}
		else
		{
			return POCO::singleton('SmartTemplate', $tpl_filename);
		}
	}

	/**
	 * ִ��ָ������ͼ
	 *
	 * @param string $tpl_filename ��ͼģ���ļ�
	 * @param array  $view_data    ģ�����
	 */
	public function executeView($tpl_filename, $view_data)
	{
		if (empty($tpl_filename))
		{
			throw new App_Exception('ģ���ļ�����Ϊ��');
		}
		if (empty($view_data) && !is_array($view_data))
		{
			throw new App_Exception('ģ���������Ϊ�ջ�������');
		}
		$tpl = $this->getView($tpl_filename);
		$tpl->assign($view_data);
		$tpl->output();
	}

	/**
	 * ����Smartyģ�����
	 * @param string $template_file
	 * @param string $template_dir
	 * @return object
	 */
	public function getSmartyTemplate($template_file, $template_dir='')
	{
	    $smarty = new Smarty();
	    $template_dir = trim($template_dir);
	    if( strlen($template_dir)>0 ) $smarty->setTemplateDir($template_dir);
	    $smarty->setCompileDir('./_smarty_templates_c/');
	    $smarty->setCompileCheck(true);
	    $smarty->setForceCompile(false);
	    $smarty->setCacheDir('./_smarty_cache/');
	    $smarty->setCaching(Smarty::CACHING_OFF);
	    $smarty->setLeftDelimiter('{%');
	    $smarty->setRightDelimiter('%}');
	    $smarty->setDebugging(false);
	    return $smarty->createTemplate($template_file);
	}


	/**
     * ����һ���ؼ��� HTML ����
     *
     * @param string $type    �ؼ�����
     * @param string $name    ���ɿؼ�������
     * @param array $attribs  �ؼ��������
     * @param boolean $return �Ƿ񷵻�HTML
     *
     * @return string
     */
	public function webControl($name, $attribs = null, $return = false)
	{
		if (empty($name))
		{
			throw new App_Exception('����ָ�����ÿؼ�������');
		}
		$web_control_obj = POCO::singleton('Web_Controls', array(array($this->_app_config['WEB_CONTROLS_EXTENDS_DIR'], self::ini('web_controls_dir'))));
		return $web_control_obj->control($name, $attribs, $return);
	}

    /**
     * ����Ӧ�ó������Ψһʵ��
     *
     * @param array $app_config
     *
     * @return MyApp
     */
    static function instance(array $app_config = null)
    {
    	static $instance;
        if (is_null($instance))
        {
            if (empty($app_config))
            {
                die('��������Ӧ�û���');
            }
            $instance = new self($app_config);
        }
        return $instance;
    }

    /**
     * ��д�����INI��������ȡ��ǰӦ����Ŀ��ָ������������
     *
     * @param string $option ����ָ��Ҫ��ȡ��������
     * @return mixed �����������ֵ
     */
    public static function ini($option)
    {
    	if (empty($option))
    	{
    		$option = '/';
    	}
    	elseif ($option == '/')
    	{
    		$option = self::$_app_name;
    	}
    	else
    	{
    		$option = self::$_app_name . "/{$option}";
    	}

    	return POCO::ini($option);
    }

    /**
     * ����Ӧ�ó���������õ�����
     *
     * ���û���ṩ $item �������򷵻��������õ�����
     *
     * @param string $item
     *
     * @return mixed
     */
    public function config($item = null)
    {
        if ($item)
        {
            return isset($this->_app_config[$item]) ? $this->_app_config[$item] : null;
        }
        return $this->_app_config;
    }

    /**
     * ���������ļ�����
     *
     * @param array $app_config
     *
     * @return array
     */
    static function loadConfigFiles(array $app_config)
    {
		/************** ������Ĭ������ *****************/
        $config = require(G_POCO_APP_PATH . '/config/default_config.php');
        $poco_api = require(G_POCO_APP_PATH . '/config/poco_api_config.php');
        $config['poco_api'] = $poco_api;
        /************** ������Ĭ������ *****************/

        /************** ������Ŀ���� *******************/
        $cfg = $app_config['CONFIG_DIR'];
        $files = $app_config['CUSTOM_ARRAY_SETTING'];
        foreach ($files as $filename => $scope)
        {
            if (!file_exists("{$cfg}/$filename")) continue;
            $contents = POCO::loadFile($filename, "{$cfg}/");
            if ($scope == 'global')
            {
                $config = array_merge($config, $contents);
            }
            else
            {
	            $config[$scope] = array();
	            $config[$scope] = $config[$scope] + $contents;
            }
        }
		/************** ������Ŀ���� *******************/
        return $config;
    }

    /**
     * ��ʼ��Ӧ�ó�������
     */
    protected function _initConfig()
    {
    	// ���û����KEY
		$cache_id = $this->_app_config['APP_NAME'] . '_poco_app_config_info';
        // ���������ļ�
        if ($this->_app_config['CONFIG_CACHED'])
        {
            /**
             * �ӻ������������ļ�����
             */

            // ���컺��������
            $backend = $this->_app_config['CONFIG_CACHE_BACKEND'];
            $settings = isset($this->_app_config['CONFIG_CACHE_SETTINGS'][$backend]) ? $this->_app_config['CONFIG_CACHE_SETTINGS'][$backend] : null;
            $cache = new $backend($settings);

            // ���뻺������
            $config = $cache->get($cache_id);
            if (!empty($config))
            {
            	POCO::replaceIni('poco_api', $config['poco_api']);
            	unset($config['poco_api']);
                POCO::replaceIni($this->_app_config['APP_NAME'], $config);
                return;
            }
        }

        // û��ʹ�û��棬�򻺴�����ʧЧ
        $config = self::loadConfigFiles($this->_app_config);
        $config['app_config'] = $this->_app_config;
        if ($this->_app_config['CONFIG_CACHED'])
        {
            $cache->set($cache_id, $config);
        }

        POCO::replaceIni('poco_api', $config['poco_api']);
        unset($config['poco_api']);
        POCO::replaceIni($this->_app_config['APP_NAME'], $config);
        return;
    }

	/**
	 * Ĭ�ϵ��쳣����
	 */
	public function exception_handler(Exception $ex)
	{
		if (!self::ini('error_display')) { return ; }
	    if (self::ini('error_display_friendly'))
	    {
	       echo $ex->getMessage();
	       dump('���Ҫ�ı���쳣�Ĵ������޸��ļ� "' . __FILE__ . '" �� exception_handler() ����');
	    }
	    else
	    {
	        App_Exception::dump($ex);
	    }
	}
}

