import(common.tpl)

.settings input[type="text"]|value = <?php 
	$_setting = '@@__name:\[(.*)\]__@@';
	echo $_POST['settings'][$_setting] ?? Vvveb\getSetting('chatgpt',$_setting, null, $this->site_id) ?? $this->defaults[$_setting] ?? '';
	//name="settings[setting-name] > get only setting-name
?>

.settings textarea = <?php 
	$_setting = '@@__name:\[(.*)\]__@@';
	echo $_POST['settings'][$_setting] ?? Vvveb\getSetting('chatgpt',$_setting, null, $this->site_id) ?? $this->defaults[$_setting] ?? '';
?>
