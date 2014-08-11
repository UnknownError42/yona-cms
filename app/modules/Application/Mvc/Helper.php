<?php

/**
 * Helper
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc;

class Helper extends \Phalcon\Mvc\User\Component
{

    private $translate = null;

    public function translate($string, $placeholders = null)
    {
        if (!$this->translate) {
            $this->translate = $this->getDi()->get('translate');
        }
        return $this->translate->query($string, $placeholders);

    }

    public function widget($id)
    {
        $widget = \Widget\Model\Widget::findFirst(array("id='{$id}'", "cache" => array("lifetime" => 30, "key" => "Widget::findFirst({$id})")));
        if ($widget) {
            return $widget->getHtml();
        }
    }

    public function langUrl($params)
    {
        $params['for'] .= LANG_SUFFIX;
        return $this->url->get($params);
    }

    public function cacheExpire($seconds)
    {
        $response = $this->getDi()->get('response');
        $expireDate = new \DateTime();
        $expireDate->modify("+$seconds seconds");
        $response->setExpires($expireDate);
        $response->setHeader('Cache-Control', "max-age=$seconds");
    }

    public function isAdminSession()
    {
        $session = $this->getDi()->get('session');
        $auth = $session->get('auth');
        if ($auth) {
            if ($auth->admin_session == true) {
                return true;
            }
        }
    }

    public function error($code = 404)
    {
        $helper = new \Application\Mvc\Helper\ErrorReporting();
        return $helper->{'error' . $code}();

    }

    public function title($title = null)
    {
        return \Application\Mvc\Helper\Title::getInstance($title);
    }

    public function meta()
    {
        return \Application\Mvc\Helper\Meta::getInstance();
    }

    public function activeMenu()
    {
        return \Application\Mvc\Helper\ActiveMenu::getInstance();
    }

    public function announce($incomeString, $num)
    {
        $object = new \Application\Mvc\Helper\Announce();
        return $object->announce($incomeString, $num);
    }

    public function dbProfiler()
    {
        $object = new \Application\Mvc\Helper\DbProfiler();
        return $object->DbOutput();
    }

    public function constant($name)
    {
        return get_defined_constants()[$name];
    }

    public function image($args, $attributes = array())
    {
        $imageFilter = new \Image\Filter($args, $attributes);
        return $imageFilter;
    }

}