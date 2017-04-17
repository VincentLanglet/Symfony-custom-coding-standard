<?php

/**
 * Throws errors if comments are not grouped by type with one blank line between them.
 */
class Symfony3Custom_Sniffs_Commenting_DocCommentGroupSameTypeSniff
    implements PHP_CodeSniffer_Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOC_COMMENT_OPEN_TAG);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $previousType = '';
        foreach ($tokens[$stackPtr]['comment_tags'] as $commentTag) {
            $currentType = $tokens[$commentTag]['content'];

            $previousString = $phpcsFile->findPrevious(
                T_DOC_COMMENT_STRING,
                $commentTag,
                $stackPtr
            );

            if (false !== $previousString) {
                $previousStringLine = $tokens[$previousString]['line'];
                $commentTagLine = $tokens[$commentTag]['line'];
                if ($previousType === $currentType) {
                    if ($previousStringLine !== $commentTagLine - 1) {
                        $fix = $phpcsFile->addFixableError(
                            'Expected no empty lines '
                            .'between annotations of the same type',
                            $commentTag,
                            'SameType'
                        );
                        if ($fix) {
                            $phpcsFile->fixer->beginChangeset();
                            $this->removeLines(
                                $phpcsFile,
                                $previousString,
                                $previousStringLine + 1,
                                $commentTagLine - 1
                            );
                            $phpcsFile->fixer->endChangeset();
                        }
                    }
                } else {
                    if ($previousStringLine !== $commentTagLine - 2) {
                        $fix = $phpcsFile->addFixableError(
                            'Expected exactly one empty line '
                            .'between annotations of different types',
                            $commentTag,
                            'DifferentType'
                        );
                        if ($fix) {
                            $phpcsFile->fixer->beginChangeset();
                            if ($previousStringLine === $commentTagLine - 1) {
                                $firstOnLine = $phpcsFile->findFirstOnLine(
                                    array(),
                                    $commentTag,
                                    true
                                );
                                $star = $phpcsFile->findNext(
                                    T_DOC_COMMENT_STAR,
                                    $firstOnLine
                                );
                                $content = $phpcsFile->getTokensAsString(
                                    $firstOnLine,
                                    $star - $firstOnLine + 1
                                );
                                $phpcsFile->fixer->addContentBefore(
                                    $firstOnLine,
                                    $content.$phpcsFile->eolChar
                                );
                            } else {
                                $this->removeLines(
                                    $phpcsFile,
                                    $previousString,
                                    $previousStringLine + 2,
                                    $commentTagLine - 1
                                );
                            }
                            $phpcsFile->fixer->endChangeset();
                        }
                    }
                }
            }

            $previousType = $currentType;
        }
    }

    /**
     * Remove all tokens on lines (inclusively).
     *
     * Note: this method does not start or end changeset.
     *
     * @param PHP_CodeSniffer_File $phpcsFile File to make changes in
     * @param int                  $fromPtr   Start searching tokens from here
     * @param int                  $fromLine  First line to delete tokens from
     * @param int                  $toLine    Last line to delete tokens from
     *
     * @return void
     */
    protected function removeLines(
        PHP_CodeSniffer_File $phpcsFile, $fromPtr, $fromLine, $toLine
    ) {
        $tokens = $phpcsFile->getTokens();
        for ($i = $fromPtr; ; $i++) {
            if ($tokens[$i]['line'] > $toLine) {
                break;
            }

            if ($fromLine <= $tokens[$i]['line']) {
                $phpcsFile->fixer->replaceToken($i, '');
            }
        }
    }
}