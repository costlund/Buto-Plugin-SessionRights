<?php
class PluginSessionRights{
  function __construct() {
    wfPlugin::includeonce('wf/yml');
  }
  public function set_session_rights($data){
    $data = new PluginWfArray($data);
    $data_rights = new PluginWfYml($data->get('file'));
    $user = wfUser::getSession();
    $temp = new PluginWfArray();
    if($data_rights->get('rights')){
      foreach($data_rights->get('rights') as $k => $v){
        $temp->set($k, false);
        $i = new PluginWfArray($v);
        if($i->get('session')){
          foreach($i->get('session') as $v2){
            if($user->get($v2)){
              $temp->set($k, true);
              break;
            }
          }
        }
      }
    }
    $_SESSION['rights'] = $temp->get();
    return null;
  }
  public function event_protect($data){
    $data = new PluginWfArray($data);
    $data_rights = new PluginWfYml($data->get('data/file'));
    /**
     * 
     */
    if($data_rights->get('protect/'.wfGlobals::get('class').'/right')){
      /**
       * Class is protected.
       */
      if(!wfUser::getSession()->get('rights/'.$data_rights->get('protect/'.wfGlobals::get('class').'/right'))){
        /**
         * User does not have proper rights.
         */
        exit('Rights issue (class)!');
      }
      if($data_rights->get('protect/'.wfGlobals::get('class').'/method/'.wfGlobals::get('method'))){
        if(!wfUser::getSession()->get('rights/'.$data_rights->get('protect/'.wfGlobals::get('class').'/method/'.wfGlobals::get('method')))){
          /**
           * User does not have proper right for this method.
           */
          exit('Rights issue (method exist)!');
        }
      }else{
        /**
         * No match in method but methods exist.
         */
        if($data_rights->get('protect/'.wfGlobals::get('class').'/method')){
          exit('Rights issue (method missing)!');
        }
      }
    }
    return null;
  }
}
