<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Game AdminPanel (АдминПанель)
 *
 * 
 *
 * @package		Game AdminPanel
 * @author		Nikita Kuznetsov (ET-NiK)
 * @copyright	Copyright (c) 2014, Nikita Kuznetsov (http://hldm.org)
 * @license		http://www.gameap.ru/license.html
 * @link		http://www.gameap.ru
 * @filesource
*/

/**
 * Files библиотека для работы с файлам через FTP, sFTP и локально.
 *
 * @package		Game AdminPanel
 * @category	Libraries
 * @author		Nikita Kuznetsov (ET-NiK)
 * @sinse		0.9
*/
class Files extends CI_Driver_Library {
	
	protected $CI;
	
	protected $driver 		= false;
	var $tmp_dir			= '';
	
	// --------------------------------------------------------------------
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('string');
		$this->CI->lang->load('server_command');
		$this->CI->lang->load('server_files');
		$this->CI->lang->load('ftp');
		$this->CI->lang->load('sftp');
		
		$this->valid_drivers = array('files_ftp', 'files_sftp', 'files_local');

		$this->_get_tmp_dir();
	}
	
	// ---------------------------------------------------------------------
	
	/**
	 * Получение директорири для записи временных файлов
	 */
	public function _get_tmp_dir()
	{
		$this->tmp_dir = sys_get_temp_dir();
		
		if (!is_writable($this->tmp_dir)) {
			
			if (file_exists(FCPATH . 'tmp') && is_writable(FCPATH . 'tmp')) {
				$this->tmp_dir = FCPATH . 'tmp';
			} else {
				show_error('Failed to set the tmp directory');
			}
		}
		
		return $this->tmp_dir;
	}

	// ---------------------------------------------------------------------
	
	public function set_driver($driver) 
	{
		if (!in_array('files_' . $driver, $this->valid_drivers)) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		$this->driver = $driver;
		$this->{$this->driver}->check();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Соединение с сервером
	 */
	public function connect($config = array())
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->connect($config);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Загрузка файла
	 */
	public function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->upload($locpath, $rempath, $mode, $permissions);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Поиск файла/файлов
	 */
	public function search($file, $dir = '/', $exclude_dirs = array(), $depth = 4)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->search($file, $dir, $exclude_dirs, $depth);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Удаление директории
	 */
	public function delete_dir($filepath)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->delete_dir($filepath);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Удаление файла
	 */
	public function delete_file($filepath)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->delete_file($filepath);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Загрузка файла с сервера
	 */
	public function download($rempath, $locpath, $mode = 'auto')
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->download($rempath, $locpath, $mode);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Список файлов
	 */
	public function list_files($path = '.', $recursive = false)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->list_files($path, $recursive);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Создание директории
	 */
	public function mkdir($path = '', $permissions = NULL)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->mkdir($path, $permissions);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Переименование файла/директории
	 */
	public function rename($old_file, $new_file, $move = FALSE)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->rename($old_file, $new_file, $move);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Перемещение файла/директории
	 */
	public function move($old_file, $new_file)
	{
		if (!$this->driver) {
			throw new Exception(lang('server_files_driver_not_set'));
		}
		
		return $this->{$this->driver}->move($old_file, $new_file);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Чтение файла
	 */
	public function read_file($remfile)
	{
		$temp_file = tempnam($this->tmp_dir, basename($remfile));
		$this->download($remfile, $temp_file);
		
		$file_contents = file_get_contents($temp_file);
		unlink($temp_file);
		
		return $file_contents;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Запись файла
	 */
	public function write_file($remfile, $data = '')
	{
		$temp_file = tempnam($this->tmp_dir, basename($remfile));
		
		if (file_put_contents($temp_file, $data)) {
			$upload_status = $this->upload($temp_file, $remfile);
			unlink($temp_file);
			return $upload_status;
		}
	}
	
}