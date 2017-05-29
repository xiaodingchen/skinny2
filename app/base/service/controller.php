<?php 
namespace App\base\service;

use App\base\service\view;
use App\base\service\tool;
use request;
use response;
use Skinny\Component\Config as config;

class controller
{
    private $_layout = 'main';
    private $_title = 'skinny';
    private $_theme = 'default';

    public function __construct()
    {
        tool::setExcepitonHandler();
        $this->checktoken();

    }

    public function fetch($tpl, array $data = [])
    {
        $tplpath = APP_DIR . '/' . $tpl;

        return view::instance()->make($tplpath, $data, true);
    }

    public function render($tpl, array $data = [])
    {
        $pagedata['title'] = $this->_title;
        $pagedata['view'] = APP_DIR . '/' . $tpl;
        $pagedata['data'] = $data;
        $pagedata['token'] = tool::token();

        $viewpath = THEME_DIR . '/' . $this->_theme . '/' . $this->_layout . '.html';

        return view::instance()->make($viewpath, $pagedata);
    }

    public function display($tpl, array $data = [])
    {
        $tplpath = APP_DIR . '/' . $tpl;

        return view::instance()->make($tplpath, $data);
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function setTheme($theme)
    {
        $this->_theme = $theme;
    }

    public function checktoken()
    {
        try {
            tool::checkToken();
        } catch (\Exception $e) {
            $data['errmsg'] = $e->getMessage();
            $data['errcode'] = 500;
            if(request::ajax())
            {
                return response::json($data)->send();
            }

            throw $e;
        }
    }
}
