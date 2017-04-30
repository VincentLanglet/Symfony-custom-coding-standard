<?php

/**
 * Ensures there are no spaces on increment / decrement statements or on +/- sign
 * operators or "!" boolean negators.
 */
class Symfony3Custom_Sniffs_WhiteSpace_UnaryOperatorSpacingSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
            T_DEC,
            T_INC,
            T_MINUS,
            T_PLUS,
            T_BOOLEAN_NOT,
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Check decrement / increment.
        if ($tokens[$stackPtr]['code'] === T_DEC || $tokens[$stackPtr]['code'] === T_INC) {
            $modifyLeft = substr($tokens[($stackPtr - 1)]['content'], 0, 1) === '$' ||
                $tokens[($stackPtr + 1)]['content'] === ';';

            if ($modifyLeft === true && $tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
                $error = 'There must not be a single space before a unary operator statement';
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'IncDecLeft');

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken($stackPtr - 1, '');
                }

                return;
            }

            if ($modifyLeft === false && substr($tokens[($stackPtr + 1)]['content'], 0, 1) !== '$') {
                $error = 'A unary operator statement must not be followed by a single space';
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'IncDecRight');

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
                }

                return;
            }
        }

        // Check "!" operator.
        if ($tokens[$stackPtr]['code'] === T_BOOLEAN_NOT && $tokens[$stackPtr + 1]['code'] === T_WHITESPACE) {
            $error = 'A unary operator statement must not be followed by a space';
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'BooleanNot');

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
            }

            return;
        }

        // Find the last syntax item to determine if this is an unary operator.
        $lastSyntaxItem = $phpcsFile->findPrevious(
            array(T_WHITESPACE),
            $stackPtr - 1,
            ($tokens[$stackPtr]['column']) * -1,
            true,
            null,
            true
        );
        $operatorSuffixAllowed = in_array(
            $tokens[$lastSyntaxItem]['code'],
            array(
                T_LNUMBER,
                T_DNUMBER,
                T_CLOSE_PARENTHESIS,
                T_CLOSE_CURLY_BRACKET,
                T_CLOSE_SQUARE_BRACKET,
                T_VARIABLE,
                T_STRING,
            )
        );

        // Check plus / minus value assignments or comparisons.
        if ($tokens[$stackPtr]['code'] === T_MINUS || $tokens[$stackPtr]['code'] === T_PLUS) {
            if ($operatorSuffixAllowed === false
                && $tokens[($stackPtr + 1)]['code'] === T_WHITESPACE
            ) {
                $error = 'A unary operator statement must not be followed by a space';
                $fix = $phpcsFile->addFixableError($error, $stackPtr);

                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken($stackPtr + 1, '');
                }
            }
        }
    }
}
