<?php

namespace Atom\Cms;

class Config
{
    /**
     * @var string Path to configuration directory
     */
    private string $configPath;
    
    /**
     * @var array|null Cached parameters
     */
    private ?array $params = null;
    
    /**
     * Constructor
     * 
     * @param string|null $configPath Custom configuration directory path
     */
    public function __construct(?string $configPath = null)
    {
        $this->configPath = $configPath ?? __DIR__ . '/../config';
    }
    
    /**
     * Get configuration value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $params = $this->loadParams();
        return $params[$key] ?? $default;
    }
    
    /**
     * Set configuration value
     * 
     * @param string $key
     * @param mixed $value
     * @param bool $saveImmediately Whether to save changes to file immediately
     * @return bool True if successful
     */
    public function set(string $key, $value, bool $saveImmediately = false): bool
    {
        $params = $this->loadParams();
        $params[$key] = $value;
        $this->params = $params;
        
        if ($saveImmediately) {
            return $this->saveParams();
        }
        
        return true;
    }
    
    /**
     * Check if configuration key exists
     * 
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $params = $this->loadParams();
        return isset($params[$key]);
    }
    
    /**
     * Get path to params configuration file
     * 
     * @return string Full path to params.php file
     */
    private function getParamsFilePath(): string
    {
        return $this->configPath . '/params.php';
    }
    
    /**
     * Load parameters from config/params.php file
     * 
     * @return array Parameters array
     */
    private function loadParams(): array
    {
        if ($this->params !== null) {
            return $this->params;
        }
        
        $paramsFile = $this->getParamsFilePath();
        
        if (!file_exists($paramsFile)) {
            $this->params = [];
            return $this->params;
        }
        
        $params = require $paramsFile;
        
        if (!is_array($params)) {
            $this->params = [];
            return $this->params;
        }
        
        $this->params = $params;
        return $this->params;
    }
    
    /**
     * Get all parameters as array
     * 
     * @return array All parameters
     */
    public function getAll(): array
    {
        return $this->loadParams();
    }
    
    /**
     * Save parameters to config/params.php file
     * 
     * @return bool True if successful
     */
    public function saveParams(): bool
    {
        $paramsFile = $this->getParamsFilePath();
        
        // Ensure directory exists
        $dir = dirname($paramsFile);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \RuntimeException(sprintf('Cannot create directory: %s', $dir));
        }
        
        // Format parameters as PHP array
        $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . $this->formatParams($this->params) . ';' . PHP_EOL;
        
        // Write to file with atomic operation
        $tmpFile = tempnam($dir, 'params_tmp_');
        if (false === $tmpFile) {
            throw new \RuntimeException('Cannot create temporary file');
        }
        
        try {
            if (false === file_put_contents($tmpFile, $content)) {
                throw new \RuntimeException('Cannot write to temporary file');
            }
            
            if (!rename($tmpFile, $paramsFile)) {
                throw new \RuntimeException('Cannot rename temporary file to target file');
            }
            
            // Change file permissions to be readable/writable by owner, readable by others
            chmod($paramsFile, 0644);
            
            return true;
        } catch (\Throwable $e) {
            // Clean up temporary file on error
            if (file_exists($tmpFile)) {
                unlink($tmpFile);
            }
            throw $e;
        }
    }
    
    /**
     * Format parameters array for PHP file
     * 
     * @param array $params
     * @param int $indentLevel
     * @return string
     */
    private function formatParams(array $params, int $indentLevel = 0): string
    {
        $indent = str_repeat('    ', $indentLevel);
        $output = '[' . PHP_EOL;
        
        foreach ($params as $key => $value) {
            $output .= $indent . '    ' . var_export($key, true) . ' => ';
            
            if (is_array($value)) {
                $output .= $this->formatParams($value, $indentLevel + 1);
            } else {
                $output .= var_export($value, true);
            }
            
            $output .= ',' . PHP_EOL;
        }
        
        $output .= $indent . ']';
        return $output;
    }
    
    /**
     * Remove configuration value
     * 
     * @param string $key
     * @param bool $saveImmediately Whether to save changes to file immediately
     * @return bool True if key existed and was removed
     */
    public function remove(string $key, bool $saveImmediately = false): bool
    {
        $params = $this->loadParams();
        
        if (!isset($params[$key])) {
            return false;
        }
        
        unset($params[$key]);
        $this->params = $params;
        
        if ($saveImmediately) {
            return $this->saveParams();
        }
        
        return true;
    }
}
