<?php

/**
 * Checks that there is no white space before a closing bracket, for ")" and "}".
 * Square Brackets are handled by Squiz_Sniffs_Arrays_ArrayBracketSpacingSniff.
 */
class Symfony3Custom_Sniffs_WhiteSpace_CloseBracketSpacingSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_CLOSE_CURLY_BRACKET,
            T_CLOSE_PARENTHESIS,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[($stackPtr - 1)]) === true
            && T_WHITESPACE === $tokens[($stackPtr - 1)]['code']
        ) {
            $before = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if (false !== $before
                && $tokens[$stackPtr]['line'] === $tokens[$before]['line']
            ) {
                $error = 'There should be no space before a closing "%s"';
                $fix = $phpcsFile->addFixableError(
                    $error,
                    ($stackPtr - 1),
                    'ClosingWhitespace',
                    array($tokens[$stackPtr]['content'])
                );

                if (true === $fix) {
                    $phpcsFile->fixer->replaceToken($stackPtr - 1, '');
                }
            }
        }
    }
}
