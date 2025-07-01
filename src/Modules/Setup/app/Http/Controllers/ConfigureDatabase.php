<?php

namespace Modules\Setup\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Modules\Setup\Http\Requests\ConfigureDatabaseRequest;
use PDOException;

class ConfigureDatabase
{
  public $dbConfig;
  public function __invoke(ConfigureDatabaseRequest $request): RedirectResponse
  {
    $this->createTempDatabaseConnection($request->validated());

    if ($this->databaseHasData() && !$request->has('overwrite_data')) {
      Session::put('error', trans('setup::setup.database.data_present'));
      Session::put('data_present', true);
      return redirect()->back()->withInput();
    }

    if ($request->overwrite_data !== 'on') return redirect()->back()->withInput();

    $migrationResult = $this->migrateDatabase();

    if ($migrationResult === false) {
      Session::put('data_present', true);
      return redirect()->back()->withInput();
    }

    $this->storeConfigurationInEnv();
    return redirect()->route('setup.account');
  }

  protected function createTempDatabaseConnection(array $credentials): void
  {
    $this->dbConfig = config('database.connections.mysql');

    $this->dbConfig['host'] = $credentials['db_host'];
    $this->dbConfig['port'] = $credentials['db_port'];
    $this->dbConfig['database'] = $credentials['db_name'];
    $this->dbConfig['username'] = $credentials['db_user'];
    $this->dbConfig['password'] = $credentials['db_password'];
    Config::set('database.connections.setup', $this->dbConfig);
  }

  protected function migrateDatabase(): bool
  {
    try {
      Artisan::call('migrate:fresh', [
        '--database' => 'setup', // Specify the correct connection
        '--force' => true, // Needed for itemion
        '--seed' => true,
        '--no-interaction' => true
      ]);
    } catch (Exception $e) {
      $alert = trans('setup::setup.database.config_error') . ' ' . $e->getMessage();
      Session::put('error', $alert);
      return false;
    }

    return true;
  }

  protected function storeConfigurationInEnv(): void
  {
    $envContent = File::get(base_path('.env'));
    $origin = $_SERVER ? $_SERVER['HTTP_ORIGIN'] : '';

    $envContent = preg_replace([
      '/APP_DEBUG=?(.*)\S/',
      '/APP_URL=?(.*)\S/',
      '/DB_HOST=?(.*)\S/',
      '/DB_PORT=?(.*)\S/',
      '/DB_DATABASE=?(.*)\S/',
      '/DB_USERNAME=?(.*)\S/',
      '/DB_PASSWORD=?(.*)\S/',
    ], [
      'APP_DEBUG=' . 'false',
      'APP_URL=' . $origin,
      'DB_HOST=' . $this->dbConfig['host'],
      'DB_PORT=' . $this->dbConfig['port'],
      'DB_DATABASE=' . $this->dbConfig['database'],
      'DB_USERNAME=' . $this->dbConfig['username'],
      'DB_PASSWORD=' . $this->dbConfig['password'],
    ], $envContent);

    if ($envContent !== null) {
      File::put(base_path('.env'), $envContent);
    }
  }

  protected function databaseHasData(): bool
  {
    try {
      $present_tables = Schema::connection('setup')
        ->getTableListing();
    } catch (PDOException $e) {
      Log::error($e->getMessage());
      return false;
    }

    return count($present_tables) > 0;
  }
}
