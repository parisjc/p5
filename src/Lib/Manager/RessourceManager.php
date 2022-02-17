<?php


namespace Lib\Manager;


class  RessourceManager
{
    private static array $css = array();
    private static array $js = array();

    public static function getRessources($type, $render)
    {
        $list = explode('\n', $render);
        $add = array();
        foreach ($list as $res) {
            if ($type == 'css')
            {
                preg_match_all('/<link href="([^;]*?)" rel="stylesheet"[\/]?>/',trim($res),$matches);
                $result = isset($matches[1]) ? $matches[1] : $matches[0];
                if(count($result) > 0) {
                    foreach ($result as $css)
                    {
                        self::addCSSRessource($css);
                        $add[] = '<link href="' . $css . '" rel="stylesheet">';
                    }
                }
            }elseif($type == 'js'){
                preg_match_all('/<script src="([\w\W|\/]*?)"><\/script>/',trim($res),$matches);
                $result = isset($matches[1]) ? $matches[1] : $matches[0];
                if(count($result) > 0) {
                    foreach ($result as $js)
                    {
                        self::addJSRessource($js);
                        $add[] = '<script src="' . $js . '"></script>';
                    }
                }
            }
        }
        return $add;
    }

    public static function addCSSRessource($res)
    {
        if(!in_array($res, self::$css)){
            self::$css[] = $res;
        }
    }

    public static function addJSRessource($res)
    {
        if(!in_array($res, self::$js)){
            self::$js[] = $res;
        }
    }
}