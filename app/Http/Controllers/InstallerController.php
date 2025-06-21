<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class InstallerController extends Controller
{
    public function showWelcome()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        return view('installer.welcome');
    }
    
    public function showRequirements()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        $requirements = $this->checkRequirements();
        $allRequirementsMet = !in_array(false, $requirements);
        
        return view('installer.requirements', compact('requirements', 'allRequirementsMet'));
    }
    
    public function showDatabaseSetup()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        return view('installer.database');
    }
    
    public function setupDatabase(Request $request)
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'database_type' => 'required|in:sqlite,mysql',
            'database_name' => 'required_if:database_type,mysql',
            'database_host' => 'required_if:database_type,mysql',
            'database_port' => 'required_if:database_type,mysql',
            'database_username' => 'required_if:database_type,mysql',
            'database_password' => 'nullable',
        ]);
        
        try {
            // Update .env file with database settings
            $envContent = File::get(base_path('.env'));
            
            if ($request->database_type === 'sqlite') {
                // Create SQLite database file if it doesn't exist
                $databasePath = database_path('database.sqlite');
                if (!File::exists($databasePath)) {
                    File::put($databasePath, '');
                }
                
                // Update .env file
                $envContent = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=sqlite', $envContent);
                $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $databasePath, $envContent);
            } else {
                // MySQL configuration
                $envContent = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $envContent);
                $envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=' . $request->database_host, $envContent);
                $envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=' . $request->database_port, $envContent);
                $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . $request->database_name, $envContent);
                $envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=' . $request->database_username, $envContent);
                $envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=' . $request->database_password, $envContent);
            }
            
            File::put(base_path('.env'), $envContent);
            
            // Test database connection
            DB::connection()->getPdo();
            
            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            return redirect()->route('installer.admin');
        } catch (\Exception $e) {
            return back()->with('error', 'Database connection failed: ' . $e->getMessage());
        }
    }
    
    public function showAdminSetup()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        return view('installer.admin');
    }
    
    public function setupAdmin(Request $request)
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        try {
            // Create admin user
            $authController = new AuthController();
            $authController->createAdmin($request->email, $request->password);
            
            // Mark as installed
            $this->markAsInstalled();
            
            // Create storage link
            Artisan::call('storage:link');
            
            return redirect()->route('installer.complete');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create admin user: ' . $e->getMessage());
        }
    }
    
    public function showComplete()
    {
        return view('installer.complete');
    }
    
    private function isInstalled()
    {
        return File::exists(storage_path('installed'));
    }
    
    private function markAsInstalled()
    {
        File::put(storage_path('installed'), 'Installation completed on ' . date('Y-m-d H:i:s'));
    }
    
    private function checkRequirements()
    {
        return [
            'php_version' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'pdo_extension' => extension_loaded('pdo'),
            'mbstring_extension' => extension_loaded('mbstring'),
            'tokenizer_extension' => extension_loaded('tokenizer'),
            'xml_extension' => extension_loaded('xml'),
            'ctype_extension' => extension_loaded('ctype'),
            'json_extension' => extension_loaded('json'),
            'bcmath_extension' => extension_loaded('bcmath'),
            'storage_writable' => is_writable(storage_path()),
            'bootstrap_cache_writable' => is_writable(base_path('bootstrap/cache')),
        ];
    }
}
