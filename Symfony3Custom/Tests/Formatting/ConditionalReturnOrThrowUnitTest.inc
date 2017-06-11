<?php

class Test
{
    public function tester($dummy, $mergedOptions)
    {
        if (true === $dummy) {
            return null;
        }

        if ('string' === $dummy) {
            if ('values' === $mergedOptions['some_default']) {
                return substr($dummy, 0, 5);
            }

            return ucwords($dummy);
        }

        if ('int' === $dummy) {
            if ('values' === $mergedOptions['some_default']) {
                return substr($dummy, 0, 5);
            } elseif (true) {
                return ucwords($dummy);
            }
        }

        if ('a' === $dummy) {
            $mergedOptions['a'] = true;
        } else {
            $mergedOptions['a'] = false;
        }

        if ('b' === $dummy) {
            $mergedOptions['a'] = true;

            throw new Error();
        } else {
            $mergedOptions['a'] = false;
        }

        switch (true) {
            case false:
                return null;
                break;
            default:
                break;
        }

        return null;
    }
}
