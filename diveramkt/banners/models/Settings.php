<?php namespace Diveramkt\Banners\Models;

use Model;
use Db;

class Settings extends Model
{
	public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
	public $settingsCode = 'diveramkt_banners_settings';

    // Reference to field configuration
	public $settingsFields = 'fields.yaml';
}
