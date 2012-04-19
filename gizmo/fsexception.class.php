<?php
/**
 * @package WebGizmo
 * @author Alexander R B Whillas
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 **/

/**
 * If you intend on creating a lot of custom exceptions, you may find this code
 * useful. I've created an interface and an abstract exception class that ensures
 * that all parts of the built-in Exception class are preserved in child classes. It
 * also properly pushes all information back to the parent constructor ensuring that
 * nothing is lost. This allows you to quickly create new exceptions on the fly. It
 * also overrides the default __toString method with a more thorough one.
 * 
 * Now you can create new exceptions in one line: 
<code>
<?php
class TestException extends CustomException {}
?>
</code>
 *
 * @package WebGizmo
 * @see http://www.php.net/manual/en/language.exceptions.php#91159
 **/
interface IException
{
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace
   
    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}

/**
 * undocumented class
 *
 * @package WebGizmo
 * @abstract
 **/
abstract class FSException extends Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }
   
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
}