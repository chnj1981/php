<?php declare(strict_types=1);

/**
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any
 * means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @see  https://github.com/nirvarnia/autoloader
 * @see  https://www.nirvarnia.org/
 */

namespace Nirvarnia;

/**
 * Minimalist PSR-4 compatible autoloading mechanism.
 */
final class Autoloader
{
    /**
     * @var string
     *
     * The base directory for all autoloadable class paths.
     */
    private $base_dir = null;

    /**
     * @var array
     *
     * An associative array where the key is a namespace prefix and the value
     * is an array of directories for classes in that namespace. Directories
     * are relative to $base_dir.
     */
    private $prefixes = [];

    /**
     * Constructor.
     *
     * Sets the base directory for all autoloadable class paths, and registers
     * the load() method with the SPL's autoloader stack.
     *
     * If a base directory is not provided, the base directory will be set to
     * PHP's include path.
     *
     * @params  string|null  $base_dir
     */
    public function __construct(string $base_dir = null)
    {
        $this->base_dir = ($base_dir ? $base_dir : get_include_path());
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param   string        $prefix     The namespace prefix.
     * @param   string|array  $directory  One or more directories for class files in the namespace.
     *
     * @return  void
     */
    public function register(string $prefix, $directory)
    {
        // Normalize the namespace prefix and the directory paths.

        $prefix = trim($prefix, '\\').'\\';
        $directories = (array) $directory;
        foreach ($directories as &$directory) {
            $directory = rtrim($directory, DIRECTORY_SEPARATOR).'/';
        }

        $this->prefixes[$prefix] = $directories;
    }

    /**
     * Class autoloading method.
     *
     * Takes a fully-qualified class name as its only parameter.
     *
     * Returns the mapped file name on success, or boolean false if the class
     * could not be autoloaded.
     *
     * @param   string  $class
     *
     * @return  string|bool
     */
    public function load(string $class)
    {
        // Work backwards through the namespace parts of the fully-qualified
        // class name, until find a mapped file name.

        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {

            // Retain the trailing namespace separator in the prefix.

            $prefix = substr($class, 0, $pos + 1);

            // The rest is the relative class name.

            $relative_class = substr($class, $pos + 1);

            // Try to load a mapped file for the prefix + relative class.

            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {

                return $mapped_file;
            }

            // Remove the trailing namespace separator for the next iteration
            // of strrpos().

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * Loads the mapped file for a namespace prefix and relative class.
     *
     * If a mapped file is successfully loaded, the name of the mapped file
     * is returned. If no mapped file can be loaded, boolean false is returned.
     *
     * @param   string  $prefix          The namespace prefix.
     * @param   string  $relative_class  The relative class name.
     *
     * @return  string|bool
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        if (!array_key_exists($prefix, $this->prefixes)) {

            return false;
        }

        // Look through the base directories for this namespace prefix.
        // If a mapped file exists, require it and exit.

        foreach ($this->prefixes[$prefix] as $directory) {

            $file = $this->base_dir
                  .$directory
                  .str_replace('\\', '/', $relative_class)
                  .'.php';

            if ($this->requireFile($file)) {

                return $file;
            }
        }

        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param   string  $file  The file to require.
     *
     * @return  bool           True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;

            return true;
        }

        return false;
    }
}
