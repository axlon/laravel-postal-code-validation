<?php

namespace Axlon\PostalCodeValidation\Build;

use Brick\VarExporter\VarExporter;

class FileWriter
{
    /**
     * Export the rules to a file.
     *
     * @param array $data
     * @return void
     * @throws \Brick\VarExporter\ExportException
     */
    public static function export(array $data): void
    {
        if (!$handle = @fopen(__DIR__ . '/../../resources/rules.php', 'w')) {
            echo 'Unable to write to file.';
            exit(1);
        }

        $options = VarExporter::ADD_RETURN
            | VarExporter::INLINE_NUMERIC_SCALAR_ARRAY
            | VarExporter::TRAILING_COMMA_IN_ARRAY;

        fwrite($handle, '<?php' . PHP_EOL . PHP_EOL);
        fwrite($handle, stripslashes(VarExporter::export($data, $options)));
        fclose($handle);
    }
}
