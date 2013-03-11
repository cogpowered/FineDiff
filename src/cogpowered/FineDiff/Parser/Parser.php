<?php

namespace cogpowered\FineDiff\Parser;

use cogpowered\FineDiff\Granularity\GranularityInterface;
use cogpowered\FineDiff\Exceptions\GranularityCountException;
use cogpowered\FineDiff\Parser\Operations\Copy;
use cogpowered\FineDiff\Parser\Operations\Delete;
use cogpowered\FineDiff\Parser\Operations\Insert;
use cogpowered\FineDiff\Parser\Operations\Replace;

class Parser implements ParserInterface
{
    /**
     * @var rcrowe\FineDiff\GranularityInterface
     */
    protected $granularity;

    /**
     * @var string Text we are comparing against.
     */
    protected $from_text;

    /**
     * @var int Position of the $from_text we are at.
     */
    protected $from_offset = 0;

    /**
     * @var rcrowe\FineDiff\Operations\OperationInterface
     */
    protected $last_edit;

    /**
     * @var int Current position in the granularity array.
     */
    protected $stackpointer = 0;


    public function __construct(GranularityInterface $granularity)
    {
        $this->granularity = $granularity;
    }

    public function getGranularity()
    {
        return $this->granularity;
    }

    public function setGranularity(GranularityInterface $granularity)
    {
        $this->granularity = $granularity;
    }

    public function getDiffParser()
    {
        return $this->diffParser;
    }

    /**
     * @throws rcrowe\FineDiff\Exceptions\GranularityCountException
     */
    public function parse($from_text, $to_text)
    {
        // Ensure the granularity contains some delimiters
        if (count($this->granularity) === 0) {
            throw new GranularityCountException('Granularity contains no delimiters');
        }

        // Reset internal parser properties
        $this->from_text    = $from_text;
        $this->from_offset  = 0;
        $this->last_edit    = null;
        $this->stackpointer = 0;

        // Parse the two string
        $this->process($from_text, $to_text);

        // Return processed diff
        return new Opcodes($this->edits);
    }

    protected function process($from_text, $to_text)
    {
        // Lets get parsing
        $delimiters     = $this->granularity[$this->stackpointer++];
        $has_next_stage = $this->stackpointer < count($this->granularity);

        // Actually perform diff
        $diff = $this->diff($from_text, $to_text, $delimiters);
        $diff = (is_array($diff)) ? $diff : array();

        foreach ($diff as $fragment) {

            // increase granularity
            if ($fragment instanceof Replace && $has_next_stage) {
                $this->process(
                    substr($this->from_text, $this->from_offset, $fragment->getFromLen()),
                    $fragment->getText()
                );
            }
            // fuse copy ops whenever possible
            elseif ($fragment instanceof Copy && $this->last_edit instanceof Copy) {
                $this->edits[count($this->edits)-1]->increase($fragment->getFromLen());
                $this->from_offset += $fragment->getFromLen();
            }
            else {
                /* $fragment instanceof Copy */
                /* $fragment instanceof Delete */
                /* $fragment instanceof Insert */
                $this->edits[] = $this->last_edit = $fragment;
                $this->from_offset += $fragment->getFromLen();
            }
        }

        $this->stackpointer--;
    }

    // Core parsing function
    protected function diff($from_text, $to_text, $delimiters)
    {
        // Empty delimiter means character-level diffing.
        // In such case, use code path optimized for character-level diffing.
        if (empty($delimiters)) {
            return $this->charDiff($from_text, $to_text);
        }


        $result = array();

        // fragment-level diffing
        $from_text_len  = strlen($from_text);
        $to_text_len    = strlen($to_text);
        $from_fragments = $this->extractFragments($from_text, $delimiters);
        $to_fragments   = $this->extractFragments($to_text, $delimiters);

        $jobs              = array(array(0, $from_text_len, 0, $to_text_len));
        $cached_array_keys = array();


        while ($job = array_pop($jobs)) {

            // get the segments which must be diff'ed
            list($from_segment_start, $from_segment_end, $to_segment_start, $to_segment_end) = $job;

            // catch easy cases first
            $from_segment_length = $from_segment_end - $from_segment_start;
            $to_segment_length   = $to_segment_end - $to_segment_start;

            if (!$from_segment_length || !$to_segment_length ) {

                if ( $from_segment_length ) {
                    $result[$from_segment_start * 4] = new Delete($from_segment_length);
                } else if ( $to_segment_length ) {
                    $result[$from_segment_start * 4 + 1] = new Insert(substr($to_text, $to_segment_start, $to_segment_length));
                }

                continue;
            }

            // find longest copy operation for the current segments
            $best_copy_length = 0;

            $from_base_fragment_index = $from_segment_start;
            $cached_array_keys_for_current_segment = array();

            while ( $from_base_fragment_index < $from_segment_end ) {

                $from_base_fragment        = $from_fragments[$from_base_fragment_index];
                $from_base_fragment_length = strlen($from_base_fragment);

                // performance boost: cache array keys
                if (!isset($cached_array_keys_for_current_segment[$from_base_fragment])) {

                    if ( !isset($cached_array_keys[$from_base_fragment]) ) {
                        $to_all_fragment_indices = $cached_array_keys[$from_base_fragment] = array_keys($to_fragments, $from_base_fragment, true);
                    }
                    else {
                        $to_all_fragment_indices = $cached_array_keys[$from_base_fragment];
                    }

                    // get only indices which falls within current segment
                    if ($to_segment_start > 0 || $to_segment_end < $to_text_len) {

                        $to_fragment_indices = array();

                        foreach ($to_all_fragment_indices as $to_fragment_index) {

                            if ($to_fragment_index < $to_segment_start) {
                                continue;
                            }

                            if ($to_fragment_index >= $to_segment_end) {
                                break;
                            }

                            $to_fragment_indices[] = $to_fragment_index;
                        }

                        $cached_array_keys_for_current_segment[$from_base_fragment] = $to_fragment_indices;

                    } else {
                        $to_fragment_indices = $to_all_fragment_indices;
                    }

                } else {
                    $to_fragment_indices = $cached_array_keys_for_current_segment[$from_base_fragment];
                }

                // iterate through collected indices
                foreach ($to_fragment_indices as $to_base_fragment_index) {

                    $fragment_index_offset = $from_base_fragment_length;

                    // iterate until no more match
                    for (;;) {

                        $fragment_from_index = $from_base_fragment_index + $fragment_index_offset;

                        if ($fragment_from_index >= $from_segment_end) {
                            break;
                        }

                        $fragment_to_index = $to_base_fragment_index + $fragment_index_offset;

                        if ($fragment_to_index >= $to_segment_end) {
                            break;
                        }

                        if ($from_fragments[$fragment_from_index] !== $to_fragments[$fragment_to_index]) {
                            break;
                        }

                        $fragment_length = strlen($from_fragments[$fragment_from_index]);
                        $fragment_index_offset += $fragment_length;
                    }

                    if ($fragment_index_offset > $best_copy_length) {
                        $best_copy_length = $fragment_index_offset;
                        $best_from_start  = $from_base_fragment_index;
                        $best_to_start    = $to_base_fragment_index;
                    }
                }

                $from_base_fragment_index += strlen($from_base_fragment);

                // If match is larger than half segment size, no point trying to find better
                // TODO: Really?
                if ($best_copy_length >= $from_segment_length / 2) {
                    break;
                }

                // no point to keep looking if what is left is less than
                // current best match
                if ( $from_base_fragment_index + $best_copy_length >= $from_segment_end ) {
                    break;
                }
            }

            if ($best_copy_length) {
                $jobs[] = array($from_segment_start, $best_from_start, $to_segment_start, $best_to_start);
                $result[$best_from_start * 4 + 2] = new Copy($best_copy_length);
                $jobs[] = array($best_from_start + $best_copy_length, $from_segment_end, $best_to_start + $best_copy_length, $to_segment_end);
            } else {
                $result[$from_segment_start * 4 ] = new Replace($from_segment_length, substr($to_text, $to_segment_start, $to_segment_length));
            }
        }

        ksort($result, SORT_NUMERIC);
        return array_values($result);
    }

    protected function charDiff($from_text, $to_text)
    {
        $result = array();
        $jobs   = array(array(0, strlen($from_text), 0, strlen($to_text)));

        while ($job = array_pop($jobs)) {

            // get the segments which must be diff'ed
            list($from_segment_start, $from_segment_end, $to_segment_start, $to_segment_end) = $job;

            $from_segment_len = $from_segment_end - $from_segment_start;
            $to_segment_len   = $to_segment_end - $to_segment_start;

            // catch easy cases first
            if (!$from_segment_len || !$to_segment_len) {

                if ($from_segment_len) {
                    $result[$from_segment_start * 4 + 0] = new Delete($from_segment_len);
                } else if ( $to_segment_len ) {
                    $result[$from_segment_start * 4 + 1] = new Insert(substr($to_text, $to_segment_start, $to_segment_len));
                }

                continue;
            }

            if ($from_segment_len >= $to_segment_len) {

                $copy_len = $to_segment_len;

                while ($copy_len) {

                    $to_copy_start     = $to_segment_start;
                    $to_copy_start_max = $to_segment_end - $copy_len;

                    while ($to_copy_start <= $to_copy_start_max) {

                        $from_copy_start = strpos(substr($from_text, $from_segment_start, $from_segment_len), substr($to_text, $to_copy_start, $copy_len));

                        if ($from_copy_start !== false) {
                            $from_copy_start += $from_segment_start;
                            break 2;
                        }

                        $to_copy_start++;
                    }

                    $copy_len--;
                }
            } else {

                $copy_len = $from_segment_len;

                while ($copy_len) {

                    $from_copy_start     = $from_segment_start;
                    $from_copy_start_max = $from_segment_end - $copy_len;

                    while ($from_copy_start <= $from_copy_start_max) {

                        $to_copy_start = strpos(substr($to_text, $to_segment_start, $to_segment_len), substr($from_text, $from_copy_start, $copy_len));

                        if ($to_copy_start !== false) {
                            $to_copy_start += $to_segment_start;
                            break 2;
                        }

                        $from_copy_start++;
                    }

                    $copy_len--;
                }
            }

            // match found
            if ( $copy_len ) {
                $jobs[] = array($from_segment_start, $from_copy_start, $to_segment_start, $to_copy_start);
                $result[$from_copy_start * 4 + 2] = new Copy($copy_len);
                $jobs[] = array($from_copy_start + $copy_len, $from_segment_end, $to_copy_start + $copy_len, $to_segment_end);
            }
            // no match,  so delete all, insert all
            else {
                $result[$from_segment_start * 4] = new Replace($from_segment_len, substr($to_text, $to_segment_start, $to_segment_len));
            }
        }

        ksort($result, SORT_NUMERIC);
        return array_values($result);
    }

    /**
    * Efficiently fragment the text into an array according to
    * specified delimiters.
    * No delimiters means fragment into single character.
    * The array indices are the offset of the fragments into
    * the input string.
    * A sentinel empty fragment is always added at the end.
    * Careful: No check is performed as to the validity of the
    * delimiters.
    */
    protected function extractFragments($text, $delimiters)
    {
        // special case: split into characters
        if (empty($delimiters)) {
            $chars                = str_split($text, 1);
            $chars[strlen($text)] = '';

            return $chars;
        }

        $fragments = array();
        $start     = 0;
        $end       = 0;

        for (;;) {

            $end += strcspn($text, $delimiters, $end);
            $end += strspn($text, $delimiters, $end);

            if ($end === $start) {
                break;
            }

            $fragments[$start] = substr($text, $start, $end - $start);
            $start             = $end;
        }

        $fragments[$start] = '';

        return $fragments;
    }
}