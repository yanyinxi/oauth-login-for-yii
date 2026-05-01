<?php
/**
 * oauth login for yii 
 * 
 * @author windsdeng@gmail.com http://www.dlf5.com
 * @copyright Copyright &copy; 2010 dlf5.com
 */

Yii::import('ext.oauthLogin.qq.qqConnect',true);
Yii::import('ext.oauthLogin.sina.sinaWeibo',true);

class oauthLogin extends CWidget 
{
	/***** widget options  *****/
	
	/******* widget public vars *******/
	public $baseUrl			= null;
	
	public $cssFile = array(
							'css/oauth_login_yii.css',
			   		);
	
	public $data = array();
	
    /**
     *
     * @var  small_login and medium_login big_login
     */
    public $itemView = 'small_login';

    public $sina_code_url = null;

    public $qq_code_url = null;
    
    public $back_url = null;


    /**
	* Initialize the widget
	*/
	public function init()
	{
		parent::init();        
		//Publish assets
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		$this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
		
		//Register the widget css files
		$cs=Yii::app()->clientScript;
		foreach($this->cssFile as $css) {
			
			$oauthCssFile = $this->baseUrl . $css;
			$cs->registerCssFile($oauthCssFile);
		}
        
        $this->sinaLogin();
        $this->qqLogin();
	}

    /**
     * @return string[]
     */
    public static function getSessionKeys() {
        return array('sina_state', 'qq_state', 'sina_back_url', 'qq_back_url');
    }
	
	
    /**
     * sinaLogin
     */
    public function sinaLogin()
    {
        $state = function_exists('random_bytes')
            ? bin2hex(random_bytes(16))
            : md5(uniqid(rand(), true));
        Yii::app()->session->add('sina_state', $state);
        $weiboService = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
        $this->sina_code_url = $weiboService->getAuthorizeURL(WB_CALLBACK_URL, 'code', $state);
        $backUrl = $this->back_url ?: Yii::app()->createAbsoluteUrl('/');
		Yii::app()->session->add('sina_back_url', $backUrl . '?state=' . $state);
    }
    
    /**
     * qqLogin
     */
    public function qqLogin()
    {
        $state = function_exists('random_bytes')
            ? bin2hex(random_bytes(16))
            : md5(uniqid(rand(), true));
        Yii::app()->session->add('qq_state', $state);
        $qqService = new qqConnectAuthV2(QQ_APPID, QQ_APPKEY);
        $this->qq_code_url = $qqService->getAuthorizeURL(QQ_CALLBACK_URL, 'code', $state);
        $backUrl = $this->back_url ?: Yii::app()->createAbsoluteUrl('/');
        Yii::app()->session->add('qq_back_url', $backUrl . '?state=' . $state);
    }


    /**
	* Run the widget
	*/
	public function run()
	{
		parent::run();
		$this->render($this->itemView, array('data' => $this->data));
	}

}	