<?php
#region ModulpruefungAusgeben
class ModulpruefungAusgeben
{
    public static $name = 'checkModules';
    public static $installed = false;
    public static $page = 0;
    public static $rank = 50;
    public static $enabledShow = true;  

    public static $onEvents = array('check'=>array('name'=>'checkModules','event'=>array('actionCheckModules','page','install', 'update')));  

    public static function show($console, $result, $data)
    {
        $text = '';
        $text .= Design::erstelleBeschreibung($console,Language::Get('modules','description'));  

        if (isset($result[self::$onEvents['check']['name']]) && $result[self::$onEvents['check']['name']]!=null){
           $result =  $result[self::$onEvents['check']['name']];
        } else 
            $result = array('content'=>null,'fail'=>false,'errno'=>null,'error'=>null);  

        $fail = $result['fail'];
        $error = $result['error'];
        $errno = $result['errno'];
        $content = $result['content'];  

        if ($content!=null){
            foreach ($content as $moduleName => $status){
                if (!$console){
                    $text .= Design::erstelleZeile($console, $moduleName, 'e', ($status ? Language::Get('main','ok') : "<font color='red'>".Language::Get('main','fail')."</font>"), 'v');
                } else 
                    $text .= $moduleName.' '.($status ? Language::Get('main','ok') : Language::Get('main','fail'))."\n";
            }
        } else
            $text .= Design::erstelleZeile($console, "<font color='red'>".Language::Get('main','fail')."</font>", 'e');  

        echo Design::erstelleBlock($console, Language::Get('modules','title'), $text);
        return null;
    }  

    public static function install($data, &$fail, &$errno, &$error)
    {
        if (constant('ISCLI')){
            return json_decode(Request::get($data['PL']['url'].'/install/install.php/checkModulesExtern',array(),'')['content'],true);
        } else {
            return ModulpruefungAusgeben::checkModules($data,$fail,$errno,$error);
        }
    }  

    public static function checkModules($data, &$fail, &$errno, &$error)
    {
        $result = array();  

        // check if apache modules are existing
        $result['mod_php5'] = self::apache_module_exists('mod_php5');
        $result['mod_rewrite'] = self::apache_module_exists('mod_rewrite');
        $result['mod_deflate'] = self::apache_module_exists('mod_deflate');  
        $result['mod_headers(win)'] = self::apache_module_exists('mod_headers');  
        $result['mod_filter(win)'] = self::apache_module_exists('mod_filter');  
        $result['mod_expires(win)'] = self::apache_module_exists('mod_expires');    

        return $result;
    }  

    public static function apache_module_exists($module)
    {
        //if (!function_exists('apache_get_modules')) return false;
        return in_array($module, apache_get_modules());
    }
}
#endregion ModulpruefungAusgeben