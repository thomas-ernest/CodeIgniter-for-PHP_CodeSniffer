<?php
/**
 * CodeIgniter_Sniffs_Operators_UppercaseLiteralLogicalOperatorsSniff.
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
 * CodeIgniter_Sniffs_Operators_UppercaseLiteralLogicalOperatorsSniff.
 *
 * Checks to ensure that the logical operators 'AND', 'OR' and 'XOR' are used
 * instead of their equivalents in lowercase or their symbolic equivalents.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodeIgniter_Sniffs_Operators_UppercaseLiteralLogicalOperatorsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Return the set of logical operators as literals.
     *
     * @return array The set of logical operators as literals.
     */
    private static function _getLiteralCodes()
    {
        return array(
                T_LOGICAL_AND,
                T_LOGICAL_OR,
                T_LOGICAL_XOR,
        );
    }

    /**
     * Return the set of logical operators as symbols.
     *
     * @return array The set of logical operators as symbols.
     */
    private static function _getSymbolicCodes()
    {
        return array(
                T_BOOLEAN_AND,
                T_BOOLEAN_OR,
        );
    }

    /**
     * Returns the literal corresponding to a logical operator provided
     * in the form of a symbol. If no parameter is provided or if it isn't
     * associated to a logical operator literal, then all association between
     * symbols and literal are returned.
     *
     * @param string $symbol .
     *
     * @return string|array The literal corresponding to a logical
     * operator symbol as a string, or all associations as an array.
     */
    private static function _getLiteralFromSymbol($symbol=null)
    {
        $symbol_to_literal = array(
                         '&&' => 'AND',
                         '||' => 'OR',
                         '^'  => 'XOR',
                        );
        if (is_null($symbol)) {
            return $symbol_to_literal;
        } else if ( ! array_key_exists($symbol, $symbol_to_literal)) {
            return $symbol_to_literal;
        } else {
            return $symbol_to_literal[$symbol];
        }
    }

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array_merge(
            self::_getLiteralCodes(),
            self::_getSymbolicCodes()
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

        $operator_token = $tokens[$stackPtr];
        $operator_string = $operator_token['content'];
        $operator_code = $operator_token['code'];

        if (in_array($operator_code, self::_getSymbolicCodes())) {
            $error_message = 'Logical operator "' . $operator_string
                . '" is prohibited; use "'
                . self::_getLiteralFromSymbol($operator_string) . '" instead';
            $phpcsFile->addError($error_message, $stackPtr);
        }

        if (in_array($operator_code, self::_getLiteralCodes())) {
            if ($operator_string !== strtoupper($operator_string)) {
                $error_message = 'All logical operators should be in upper case;'
                    . ' use "' . strtoupper($operator_string)
                    . '" instead of "' . $operator_string . '"';
                $phpcsFile->addError($error_message, $stackPtr);
            }
        }
    }//end process()


}//end class

?>