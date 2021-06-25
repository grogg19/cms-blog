<?php
namespace App\Parse;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Exception;

/**
 * Yaml helper class
 *
 * @package october\parse
 * @author Alexey Bobkov, Samuel Georges
 */
class Yaml
{
    /**
     * Parses supplied YAML contents in to a PHP array.
     * @param string $contents YAML contents to parse.
     * @return array The YAML contents as an array.
     */
    public function parse($contents)
    {
        $yaml = new Parser;
        return $yaml->parse($contents);
    }

    /**
     * Parses YAML file contents in to a PHP array.
     * @param string $fileName File to read contents and parse.
     * @return array The YAML contents as an array.
     */
    public function parseFile($fileName)
    {
        try {
            return $this->parse(file_get_contents($fileName));
        } catch (Exception $e) {
            throw new ParseException("A syntax error was detected in $fileName. " . $e->getMessage(), __LINE__, __FILE__);
        }
    }

}
