<?php

namespace ProductPackage\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ManageCorsOriginsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-package:cors {action : Action to perform (list, add, remove)} {origin? : Origin URL for add/remove actions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage CORS allowed origins for the product package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action');
        $origin = $this->argument('origin');

        // Path to the config file
        $configPath = config_path('product-package.php');
        
        // Check if config file exists
        if (!File::exists($configPath)) {
            $this->error('Config file not found. Please publish the config first:');
            $this->line('php artisan vendor:publish --tag=product-package-config');
            return 1;
        }

        // Load the config
        $config = include $configPath;

        switch ($action) {
            case 'list':
                $this->listOrigins($config);
                break;
                
            case 'add':
                if (!$origin) {
                    $this->error('Origin is required for add action.');
                    return 1;
                }
                $this->addOrigin($configPath, $config, $origin);
                break;
                
            case 'remove':
                if (!$origin) {
                    $this->error('Origin is required for remove action.');
                    return 1;
                }
                $this->removeOrigin($configPath, $config, $origin);
                break;
                
            default:
                $this->error('Invalid action. Use list, add, or remove.');
                return 1;
        }

        return 0;
    }

    /**
     * List all allowed origins
     *
     * @param array $config
     * @return void
     */
    private function listOrigins($config)
    {
        $origins = $config['cors']['allowed_origins'] ?? [];
        
        if (empty($origins)) {
            $this->info('No allowed origins found.');
            return;
        }
        
        $this->info('Allowed CORS origins:');
        foreach ($origins as $origin) {
            $this->line("- {$origin}");
        }
    }

    /**
     * Add a new allowed origin
     *
     * @param string $configPath
     * @param array $config
     * @param string $origin
     * @return void
     */
    private function addOrigin($configPath, $config, $origin)
    {
        // Validate URL
        if (!filter_var($origin, FILTER_VALIDATE_URL)) {
            $this->error('Invalid URL format.');
            return;
        }
        
        // Check if origin already exists
        if (in_array($origin, $config['cors']['allowed_origins'])) {
            $this->info("Origin '{$origin}' already exists in allowed origins.");
            return;
        }
        
        // Add new origin
        $config['cors']['allowed_origins'][] = $origin;
        
        // Save updated config
        $this->saveConfig($configPath, $config);
        
        $this->info("Origin '{$origin}' added successfully.");
    }

    /**
     * Remove an allowed origin
     *
     * @param string $configPath
     * @param array $config
     * @param string $origin
     * @return void
     */
    private function removeOrigin($configPath, $config, $origin)
    {
        // Check if origin exists
        $key = array_search($origin, $config['cors']['allowed_origins']);
        if ($key === false) {
            $this->error("Origin '{$origin}' not found in allowed origins.");
            return;
        }
        
        // Remove origin
        unset($config['cors']['allowed_origins'][$key]);
        $config['cors']['allowed_origins'] = array_values($config['cors']['allowed_origins']); // Reindex array
        
        // Save updated config
        $this->saveConfig($configPath, $config);
        
        $this->info("Origin '{$origin}' removed successfully.");
    }

    /**
     * Save config to file
     *
     * @param string $path
     * @param array $config
     * @return void
     */
    private function saveConfig($path, $config)
    {
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        File::put($path, $content);
    }
}