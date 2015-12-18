<?php namespace App;

/**
 * Remove the extra __METHOD__.(.__LINE__.).': '
 * Truncate long class names
 *
 * See: bootstrap/app.php for logging format configurations
 */

use Monolog\Formatter\LineFormatter;

/**
 * Remove the extra __METHOD__.(.__LINE__.).': '
 * Truncate long class names
 */
class LineNormalizer extends LineFormatter {

    /**
     * @param string $format                     The format of the message
     * @param string $dateFormat                 The format of the timestamp: one supported by DateTime::format
     * @param bool   $allowInlineLineBreaks      Whether to allow inline line breaks in log entries
     * @param bool   $ignoreEmptyContextAndExtra
     */
    public function __construct($format = null, $dateFormat = null, $allowInlineLineBreaks = false, $ignoreEmptyContextAndExtra = false) {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record) {
        if (isset($record['message'])) {
            //preg_replace('/^[^:]+::[^\(]+\([\d]+\):[ ]*/', '', $record['message']);
            $record['message'] = preg_replace('/^[^:]+::[^\(]+\([\d]+\):[ ]*/', '', $record['message']);
        }
        if (isset($record['extra']) and isset($record['extra']['class']) and strlen($record['extra']['class']) > 40) {
            $record['extra']['class'] = '..'.substr($record['extra']['class'], strlen($record['extra']['class']) - 38);
        }

        return parent::format($record);
    }
}