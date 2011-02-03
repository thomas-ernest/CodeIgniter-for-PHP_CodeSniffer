<?php
/**
 * CodeIgniter_Sniffs_Strings_VariableUsageSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * CodeIgniter_Sniffs_Strings_VariableUsageSniff.
 *
 * Ensures that variables parsed in double-quoted strings are enclosed with
 * braces to prevent greedy token parsing.
 * Single-quoted strings don't parse variables, so there is no risk of greedy
 * token parsing.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodeIgniter_Sniffs_Strings_VariableUsageSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_DOUBLE_QUOTED_STRING,
            T_CONSTANT_ENCAPSED_STRING,
        );
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $dbl_qt_string = $tokens[$stackPtr]['content'];
        // makes sure that it is about a double quote string,
        // since variables are not parsed out of double quoted string
        $open_dbl_qt_str = substr($dbl_qt_string, 0, 1);
        if (0 === strcmp($open_dbl_qt_str, '"')) {
            // I couldn't use token_get_all('<?php '.$dbl_qt_string); in a clever way
            $var_at = self::_getVariablePosition($dbl_qt_string, 0);
            $error_added = false;
            while ( ! $error_added && false !== $var_at) {
                if ('{' !== substr($dbl_qt_string, $var_at + 1, 1)) {
                    $error = 'It is prohibed to use a variable in a double quoted string without enclosing it in braces.';
                    $phpcsFile->addError($error, $stackPtr);
                    $error_added = true;
                }
                $var_at = self::_getVariablePosition($dbl_qt_string, $var_at + 1);
            }
        }
    }//end process()



    /**
     * Returns the position of first occurrence of a PHP variable starting with $
     * in $haystack from $offset.
     *
     * @param string $haystack The string to search in.
     * @param int    $offset   The optional offset parameter allows you to
     *                         specify which character in haystack to start
     *                         searching. The returned position is still
     *                         relative to the beginning of haystack.
     *
     * @return mixed The position as an integer
     *               or the boolean false, if no variable is found.
     */
    private static function _getVariablePosition($haystack, $offset = 0)
    {
        $var_starts_at = strpos($haystack, '$', $offset);
        $is_a_var = false;
        while (false !== $var_starts_at && ! $is_a_var) {
            // makes sure that $ is used for a variable and not as a symbol,
            // if $ is protected with the escape char, then it is a symbol.
            if (0 !== strcmp($haystack[$var_starts_at - 1], '\\')) {
                if (0 === strcmp($haystack[$var_starts_at + 1], '{')) {
                    // there is an opening brace in the right place
                    // so it looks for the closing brace in the right place
                    $hsChunk2 = substr($haystack, $var_starts_at + 2);
                    if (1 === preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\}/', $hsChunk2)) {
                        $is_a_var = true;
                    }
                } else {
                    $hsChunk1 = substr($haystack, $var_starts_at + 1);
                    if (1 === preg_match('/^[a-zA-Z_\x7f-\xff]/', $hsChunk1)) {
                        // $ is used for a variable and not as a symbol,
                        // since what follows $ matchs the definition of
                        // a variable label for PHP.
                        $is_a_var = true;
                    }
                }
            }
            // update $var_starts_at for the next variable
            // only if no variable was found, since it is returned otherwise.
            if ( ! $is_a_var) {
                $var_starts_at = strpos($haystack, '$', $var_starts_at + 1);
            }
        }
        if ($is_a_var) {
            return $var_starts_at;
        } else {
            return false;
        }
    }//end _getVariablePosition()

}//end class

?>