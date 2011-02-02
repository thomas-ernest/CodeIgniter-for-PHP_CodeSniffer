<?php

/**
 * Code Igniter Coding Standard.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2010 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 * @link      http://codeigniter.com/user_guide/general/styleguide.html
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * Code Ignite Coding Standard.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Thomas Ernest <thomas.ernest@baobaz.com>
 * @copyright 2010 Thomas Ernest
 * @license   http://thomas.ernest.fr/developement/php_cs/licence GNU General Public License
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 * @link      http://codeigniter.com/user_guide/general/styleguide.html
 */
class PHP_CodeSniffer_Standards_CodeIgniter_CodeIgniterCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{


    /**
     * Return a list of external sniffs to include with this standard.
     *
     * @return array
     */
    public function getIncludedSniffs()
    {
        return array(
                // Files should be saved with Unicode (UTF-8) encoding.
                // 'CodeIgniter/Sniffs/Files/Utf8EncodingSniff.php',
                // The BOM should not be used.
                // 'CodeIgniter/Sniffs/Files/ByteOrderMarkSniff.php',
                // Unix line endings should be used (LF)
                'PEAR/Sniffs/Files/LineEndingsSniff.php',
                // All PHP files should OMIT the closing PHP tag
                'Zend/Sniffs/Files/ClosingTagSniff.php',
                // instead of the closing PHP tag use a comment block to mark the end of file and it's location relative to the application root.
                //'CodeIgniter/Sniffs/Files/ClosingFileCommentSniff.php',
                //'CodeIgniter/Sniffs/Files/ClosingLocationCommentSniff.php',
                // Always use full PHP opening tags
                'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
                // Class names should always have their first letter uppercase. Multiple words should be separated with an underscore, and not CamelCased.
                // 'CodeIgniter/Sniffs/NamingConventions/ValidClassNameSniff.php',
                // The constructor method should match identically.
                // 'CodeIgniter/Sniffs/NamingConventions/ConstructorNameSniff.php',
                // Use separate files for each class. Classes have to have the same name than files containing them too.
                'Squiz/Sniffs/Classes/ClassFileNameSniff.php',
                // All other class methods should be entirely lowercased and named to clearly indicate their function, preferably including a verb
                // Methods (and variables) that are only accessed internally by your class, such as utility and helper functions that your public methods use for code abstraction, should be prefixed with an underscore.
                // 'CodeIgniter/Sniffs/NamingConventions/ValidMethodNameSniff.php',
                // Variables should contain only lowercase letters, use underscore separators
                // Very short, non-word variables should only be used as iterators [in for() loops].
                // (Methods and) variables that are only accessed internally by your class, such as utility and helper functions that your public methods use for code abstraction, should be prefixed with an underscore.
                // 'CodeIgniter/Sniffs/NamingConventions/ValidVariableNameSniff.php',
                // Constants follow the same guidelines as do variables, except constants should always be fully uppercase.
                'Generic/Sniffs/NamingConventions/UpperCaseConstantNameSniff.php',
                // TRUE, FALSE, and NULL keywords should always be fully uppercase.
                'Generic/Sniffs/PHP/UpperCaseConstantSniff.php',
                // Use of || is discouraged as its clarity on some output devices is low (looking like the number 11 for instance). && is preferred over AND but either are acceptable, and a space should always precede and follow !. [IMO It should only be OR and AND]
                // 'CodeIgniter_Sniffs_Operators_UppercaseLiteralLogicalOperatorsSniff',
                // 'CodeIgniter_Sniffs_Operators_LogicalNotSpacingSniff',
                // Be explicit by comparing the variable type when using these return values in conditionals. Use the same stringency in returning and checking your own variables. Use === and !== as necessary.
                // 'CodeIgniter/Sniffs/Operators/StrictComparisonOperatorSniff.php',
                // Never combine statements on one line.
                'Generic/Sniffs/Formatting/DisallowMultipleStatementsSniff.php',
                // Always use single quoted strings unless you need variables parsed, and in cases where you do need variables parsed, use braces to prevent greedy token parsing. You may also use double-quoted strings if the string contains single quotes, so you do not have to use escape characters.
                // 'CodeIgniter/Sniffs/Strings/DoubleQuoteUsageSniff.php',
                // 'CodeIgniter/Sniffs/Strings/VariableUsageSniff.php',

                // Code should be commented prolifically. DocBlock style comments preceding class and method declarations
                'PEAR/Sniffs/Commenting/ClassCommentSniff.php',
                // @todo 'Squiz/Sniffs/Commenting/ClassCommentSniff.php',
                'PEAR/Sniffs/Commenting/FunctionCommentSniff.php',
                // @todo 'Squiz/Sniffs/Commenting/FunctionCommentSniff.php',
                // Use single line comments within code, leaving a blank line between large comment blocks and code.
                'PEAR/Sniffs/Commenting/InlineCommentSniff.php',
                // @todo 'Squiz/Sniffs/Commenting/InlineCommentSniff.php'

                // No whitespace can precede the opening PHP tag or follow the closing PHP tag
                // 'CodeIgniter/Sniffs/WhiteSpace/DisallowWitheSpaceAroundPhpTagsSniff.php'
                // Use tabs for whitespace in your code, not spaces.
                // 'CodeIgniter/Sniffs/WhiteSpace/DisallowSpaceIndentSniff.php'
                // Use Allman style indenting. With the exception of Class declarations, braces are always placed on a line by themselves, and indented at the same level as the control statement that "owns" them.
                // @todo based on Squiz_Sniffs_Functions_FunctionDeclarationArgumentSpacingSniff check that there is no space before funciton arguments. In general, parenthesis and brackets should not use any additional spaces. The exception is that a space should always follow PHP control structures that accept arguments with parenthesis (declare, do-while, elseif, for, foreach, if, switch, while), to help distinguish them from functions and increase readability.
/*
                'Squiz/Sniffs/Arrays/ArrayBracketSpacingSniff.php',
                'Squiz/Sniffs/WhiteSpace/CastSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_ControlStructureSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_FunctionClosingBraceSpaceSniff.php',
                'Squiz_Sniffs_WhiteSpace_FunctionOpeningBraceSpaceSniff.php',
                'Squiz_Sniffs_WhiteSpace_FunctionSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_MemberVarSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_ObjectOperatorSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_OperatorSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_PropertyLabelSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_ScopeClosingBraceSniff.php',
                'Squiz_Sniffs_WhiteSpace_ScopeIndentSniff.php',
                'Squiz_Sniffs_WhiteSpace_ScopeKeywordSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_SemicolonSpacingSniff.php',
                'Squiz_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff.php',
*/
                // Any text that is output in the control panel should use language variables in your module's lang file to allow localization.

                // Any tables that your add-on might use must use the 'exp_' prefix, followed by a prefix uniquely identifying you as the developer or company
                // MySQL keywords are always capitalized: SELECT, INSERT, UPDATE, WHERE, AS, JOIN, ON, IN, etc.
                // Break up long queries into multiple lines for legibility, preferably breaking for each clause.
               );

    }//end getIncludedSniffs()


}//end class
?>
