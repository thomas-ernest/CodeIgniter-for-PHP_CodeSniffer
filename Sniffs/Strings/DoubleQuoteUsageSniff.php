<?php
/**
 * CodeIgniter_Sniffs_Strings_DoubleQuoteUsageSniff.
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
 * CodeIgniter_Sniffs_Strings_DoubleQuoteUsageSniff.
 *
 * Ensures that variables parsed in double-quoted strings are enclosed with
 * braces to prevent greedy token parsing.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodeIgniter_Sniffs_Strings_DoubleQuoteUsageSniff implements PHP_CodeSniffer_Sniff
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
        $qt_string = $tokens[$stackPtr]['content'];
        // makes sure that it is about a double quote string,
        // since variables are not parsed out of double quoted string
        $open_qt_str = substr($qt_string, 0, 1);
        // clean the enclosing quotes
        $qt_string = substr($qt_string, 1, strlen($qt_string) - 1 - 1);
        // compute some values used in both cases
        $dbl_qt_at = strpos($qt_string, '"');
        $smpl_qt_at = strpos($qt_string, "'");
        if (0 === strcmp($open_qt_str, '"')) {
            // it is about a double quote string,
            // so there should be at least a variable or a single quote
            $has_a_smpl_qt_or_a_var = false;
            $variable_at = self::_getVariablePosition($qt_string);
            if (false !== $smpl_qt_at) {
                $has_a_smpl_qt_or_a_var = true;
                // if there is a mix of single and double quotes without variables,
                // then users are invited to use single quoted strings.
                if (false !== $dbl_qt_at && false === $variable_at) {
                    $warning = 'It is encouraged to use singled quote string, since this string doesn\'t contain any variable though it mixes single and double quotes.';
                    $phpcsFile->addWarning($warning, $stackPtr);
                }
            } else if (false !== $variable_at) {
                $has_a_smpl_qt_or_a_var = true;
            }
            if ( ! $has_a_smpl_qt_or_a_var) {
                $error = 'Single quoted strings should be used unless the string contains variables or single quotes.';
                $phpcsFile->addError($error, $stackPtr);
            }
        } else {
            // if (0 === strcmp($open_qt_str, "'")) {
            // it is about a single quoted string,
            // if there is single quotes without additional double quotes,
            // then user is allowed to use double quote to avoid having to
            // escape single quotes.
            if (false !== $smpl_qt_at && false === $dbl_qt_at) {
                $warning = 'You may also use double-quoted strings if the string contains single quotes, so you do not have to use escape characters.';
                $phpcsFile->addWarning($warning, $stackPtr);
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