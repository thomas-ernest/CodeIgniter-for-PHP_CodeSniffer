<?php
/**
 * CodeIgniter_Sniffs_Files_ClosingFileCommentSniff.
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
 * CodeIgniter_Sniffs_Files_ClosingFileCommentSniff.
 *
 * Checks to ensure that a comment containing the file name is available at the
 * end of the file. Only other comments are allowed to follow this specific comment.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodeIgniter_Sniffs_Files_ClosingFileCommentSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_OPEN_TAG,
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
        // We are only interested if this is the first open tag.
        if ($stackPtr !== 0) {
            if ($phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1)) !== false) {
                return;
            }
        }

        $fullFilename = $phpcsFile->getFilename();
        $filename = basename($fullFilename);
        $commentTemplate = "End of file $filename";

        $tokens = $phpcsFile->getTokens();
        $currentToken = count($tokens) - 1;
        $hasClosingFileComment = false;
        $isNotAWhitespaceOrAComment = false;
        while ($currentToken >= 0
            && ! $isNotAWhitespaceOrAComment
            && ! $hasClosingFileComment
        ) {
            $token = $tokens[$currentToken];
            $tokenCode = $token['code'];
            if (T_COMMENT === $tokenCode) {
                $commentString = self::_getCommentContent($token['content']);
                if (0 === strcmp($commentString, $commentTemplate)) {
                    $hasClosingFileComment = true;
                }
            } else if (T_WHITESPACE === $tokenCode) {
                // Whitespaces are allowed between the closing file comment,
                // other comments and end of file
            } else {
                $isNotAWhitespaceOrAComment = true;
            }
            $currentToken--;
        }

        if ( ! $hasClosingFileComment) {
            $error = 'No comment block marks the end of file instead of the closing PHP tag. Please add a comment block containing only "' . $commentTemplate . '".';
            $phpcsFile->addError($error, $currentToken);
        }
    }//end process()

    /**
     * Returns the comment without its delimiter(s) as well as leading
     * and traling whitespaces.
     *
     * It removes the first #, the two first / (i.e. //) or the first /*
     * and last \*\/. If a comment starts with /**, then the last * will remain
     * as well as whitespaces between this star and the comment content.
     *
     * @param string $comment Comment containing either comment delimiter(s) and
     * trailing or leading whitspaces to clean.
     *
     * @return string Comment without comment delimiter(s) and whitespaces.
     */
    private static function _getCommentContent ($comment)
    {
        if (self::_stringStartsWith($comment, '#')) {
            $comment = substr($comment, 1);
        } else if (self::_stringStartsWith($comment, '//')) {
            $comment = substr($comment, 2);
        } else if (self::_stringStartsWith($comment, '/*')) {
            $comment = substr($comment, 2, strlen($comment) - 2 - 2);
        }
        $comment = trim($comment);
        return $comment;
    }

    /**
     * Binary safe string comparison between $needle and
     * the beginning of $haystack. Returns true if $haystack starts with
     * $needle, false otherwise.
     *
     * @param string $haystack The string to search in.
     * @param string $needle   The string to search for.
     *
     * @return bool true if $haystack starts with $needle, false otherwise.
     */
    private static function _stringStartsWith ($haystack, $needle)
    {
        $startsWith = false;
        if (strlen($needle) <= strlen($haystack)) {
            $haystackBeginning = substr($haystack, 0, strlen($needle));
            if (0 === strcmp($haystackBeginning, $needle)) {
                $startsWith = true;
            }
        }
        return $startsWith;
    }

}//end class

?>