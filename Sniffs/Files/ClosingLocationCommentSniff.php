<?php
/**
 * CodeIgniter_Sniffs_Files_ClosingLocationCommentSniff.
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
 * CodeIgniter_Sniffs_Files_ClosingLocationCommentSniff.
 *
 * Checks to ensure that a comment containing the file location is available at the
 * end of the file. Only other comments are allowed to follow this specific comment.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2006 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class CodeIgniter_Sniffs_Files_ClosingLocationCommentSniff implements PHP_CodeSniffer_Sniff
{
    public $appPath = '/application/';

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_OPEN_TAG
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

        $filePath = $phpcsFile->getFilename();
        $tokens = $phpcsFile->getTokens();
        // removes the application path from the beginning of the file path
        $locationPath = self::_getLocationPath($filePath, $this->appPath);
        // add an error, if application path doesn't exist in current file path
        if (false === $locationPath) {
            $error = 'Unable to find "' . $this->appPath . '" in file path "' . $filePath . '". Please set your project\'s application path in ruleset.xml.';
            $phpcsFile->addError($error, count($tokens) - 1);
            return;
        }
        // generates the expected comment
        $commentTemplate = "Location: $locationPath";

        $currentToken = count($tokens) - 1;
        $hasClosingLocationComment = false;
        $isNotAWhitespaceOrAComment = false;
        while ($currentToken >= 0
            && ! $isNotAWhitespaceOrAComment
            && ! $hasClosingLocationComment
        ) {
            $token = $tokens[$currentToken];
            $tokenCode = $token['code'];
            if (T_COMMENT === $tokenCode) {
                $commentString = self::_getCommentContent($token['content']);
                if (0 === strcmp($commentString, $commentTemplate)) {
                    $hasClosingLocationComment = true;
                }
            } else if (T_WHITESPACE === $tokenCode) {
                // Whitespaces are allowed between the closing file comment,
                //other comments and end of file
            } else {
                $isNotAWhitespaceOrAComment = true;
            }
            $currentToken--;
        }

        if ( ! $hasClosingLocationComment) {
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

    /**
     * Returns the relative path from $appPath to $filePath, or false if
     * $appPath cannot be found in $filePath, because $appPath is not a parent
     * of $filePath.
     *
     * @param string $filePath Full path to the file being proceed.
     * @param string $appPath  Partial or full path to the CodeIgniter
     * application directory of the file being proceed. It must not contain the
     * full path to the application directory, but at least the name of the
     * application root. Parent directory of the application root are allowed
     * but not mandatory.
     *
     * @return string|bool The relative path from $appPath to $filePath, or
     * false if $appPath cannot be found in $filePath.
     */
    private static function _getLocationPath ($filePath, $appPath)
    {
        // removes the application path from the beginning of the file path
        $AppPathAt = strpos($filePath, $appPath);
        if (false === $AppPathAt) {
            return false;
        }
        $localPath = substr($filePath, $AppPathAt + strlen($appPath));
        // ensures the location path to be a local path.
        if ( ! self::_stringStartsWith($localPath, './')) {
            $localPath = './' . $localPath;
        } else if ( ! self::_stringStartsWith($localPath, '.')
            && self::_stringStartsWith($localPath, '/')
        ) {
            $localPath = '.' . $localPath;
        }
        return $localPath;
    }
}//end class

?>