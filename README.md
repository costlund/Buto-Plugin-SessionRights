# Buto-Plugin-SessionRights
Set rights params in session to protect elements and urls.
## Set in session
Consider this session params.
```
plugin:
  my:
    plugin:
      roles:
        user: true
        admin: false
rights: {}
```
Add this code to your plugin where user session params is set. 
```
wfPlugin::includeonce('session/rights');
$session_rights = new PluginSessionRights();
$session_rights->set_session_rights(array('file' => '/plugin/_folder_/_folder_/rights.yml'));
```
File rights.yml. This will protect url /user/list, /user/view, /user/form, /user/update.
```
rights:
  user_view:
    session:
      - 'plugin/my/plugin/roles/user'
  user_edit:
    session:
      - 'plugin/my/plugin/roles/admin'
protect:
  user:
    right: user_view
    method:
      list: user_view
      view: user_view
      form: user_edit
      update: user_edit
```
This rights will be added.
```
plugin:
  my:
    plugin:
      roles:
        admin: true
rights:
  user_view: true
  user_edit: false
```
## Event
This event settings is required to protect urls.
```
events:
  module_method_before:
    -
      plugin: 'session/rights'
      method: 'protect'
      data:
        file: '/plugin/_folder_/_folder_/rights.yml'
```
## Element
To protect an element.
```
type: p
settings:
  enabled: 'globals:_SESSION/rights/user_edit'
innerHTML: Protected element
```
