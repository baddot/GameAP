<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Game AdminPanel (АдминПанель)
 *
 * 
 *
 * @package		Game AdminPanel
 * @author		Nikita Kuznetsov (ET-NiK)
 * @copyright	Copyright (c) 2013, Nikita Kuznetsov (http://hldm.org)
 * @license		http://www.gameap.ru/license.html
 * @link		http://www.gameap.ru
 * @filesource
*/

// ------------------------------------------------------------------------

/**
 * Minecraft Installer драйвер
 *
 * Драйвер для установки игровых серверов Minecraft
 *
 * @package		Game AdminPanel
 * @category	Drivers
 * @author		Nikita Kuznetsov (ET-NiK)
 * @sinse		0.8.3
*/

class Installer_minecraft extends CI_Driver {
	
	// ------------------------------------------------------------------------
	
	/**
	 * Список стандартных карт
	*/
	private function _get_default_map($game_code = '')
	{
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Получение параметра запуска игры
	*/
	public function get_start_command($game_code = '', $os = 'linux')
	{
		switch(strtolower($os)) {
			case 'windows':
				$start_command = "\"%ProgramFiles(x86)%\Java\jre7\bin\java.exe\" -Xmx1024M -Xms1024M -jar {dir}/craftbukkit.jar";
				break;
				
			default:
				$start_command = 'java -Xincgc -Xmx1G -jar {dir}/craftbukkit.jar';
				break;
		}
		
		return $start_command;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Получение настроек для сервера по умолчанию
	*/
	public function get_default_parameters($game_code = 'cstrike', $os = 'linux', $parameters = array())
	{
		//~ $parameters['maxplayers'] 	= isset($this->_parameters['maxplayers']) ? $this->_parameters['maxplayers'] : 32;
		
		return array();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Правка конфигурационных файлов
	*/
	public function change_config()
	{
		$CI =& get_instance();
		
		$file = 'server.properties';
		$file_contents = $CI->servers->read_file($file, $this->server_data);
		
		// Костыль. Меняет права файла на 666
		$CI->servers->command('chmod 666 {dir}/server.properties', $this->server_data);
		
		// Установка портов
		$file_contents = change_value_on_file($file_contents, 'server-port', $this->server_data['server_port']);
		$file_contents = change_value_on_file($file_contents, 'query.port', $this->server_data['query_port']);
		$file_contents = change_value_on_file($file_contents, 'rcon.port', $this->server_data['rcon_port']);
		
		// Включение query и rcon
		$file_contents = change_value_on_file($file_contents, 'enable-query', 'true');
		$file_contents = change_value_on_file($file_contents, 'enable-rcon', 'true');
		
		$write_result = $CI->servers->write_file($file, $file_contents, $this->server_data);
		
		return true;
	}
}
