<?php

namespace Symfony3Custom\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks whether there are else(if) or break statements after return or throw
 */
class ConditionalReturnOrThrowSniff implements Sniff
{
    /**
     * @var array
     */
    private $openers = [
        T_IF,
        T_CASE,
    ];

    /**
     * @var array
     */
    private $conditions = [
        T_ELSEIF,
        T_ELSE,
        T_BREAK,
    ];

    /**
     * Registers the tokens that this sniff wants to listen for.
     */
    public function register()
    {
        return [
            T_THROW,
            T_RETURN,
        ];
    }

    /**
     * Called when one of the token types that this sniff is listening for is found.
     *
     * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
     * @param int $stackPtr   The position in the PHP_CodeSniffer file's token stack
     *                        where the token was found.
     *
     * @return void|int Optionally returns a stack pointer. The sniff will not be
     *                  called again on the current file until the returned stack
     *                  pointer is reached. Return (count($tokens) + 1) to skip
     *                  the rest of the file.
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $elem = $tokens[$stackPtr];

        $opener = $phpcsFile->findPrevious($this->openers, $stackPtr);

        if ($opener && $elem['line'] <= $tokens[$tokens[$opener]['scope_closer']]['line']) {
            $condition = $phpcsFile->findNext($this->conditions, $stackPtr + 1);

            if (false !== $condition) {
                $next = $phpcsFile->findNext($this->openers, $stackPtr + 1);

                if (false !== $next) {
                    $err = (isset($tokens[$condition]['scope_closer']) && isset($tokens[$next]['scope_opener']))
                        ? $tokens[$condition]['scope_closer'] < $tokens[$next]['scope_opener']
                        : $tokens[$condition]['line'] <= $tokens[$next]['line'];
                } else {
                    $err = false;
                }

                if (false === $next || true === $err) {
                    $phpcsFile->addError(
                        'Do not use else, elseif, break after if and case conditions which return or throw something',
                        $condition,
                        'Invalid'
                    );
                }
            }
        }
    }
}