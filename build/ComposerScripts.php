<?php

declare(strict_types=1);

namespace Axlon\PostalCodeValidation\Build;

use Axlon\PostalCodeValidation\Build\Http\AddressValidationDataClient;
use Axlon\PostalCodeValidation\Build\Php\CountryDataProviderBuilder;
use Axlon\PostalCodeValidation\Build\Php\PatternResourceBuilder;
use Axlon\PostalCodeValidation\Build\Php\PhpPrinter;
use Closure;
use PhpParser\Node\Name;
use PhpParser\PhpVersion;
use Webmozart\Assert\Assert;

final class ComposerScripts
{
    /**
     * Build the package files.
     *
     * @return void
     * @throws \JsonException
     */
    public static function build(): void
    {
        $data = self::renderTask('Downloading data', static function () {
            return (new AddressValidationDataClient())->getAllCountries();
        });

        self::renderTask('Writing files', static function () use ($data) {
            $files = [
                __DIR__ . '/../resources/patterns.php' => new PatternResourceBuilder(),
                __DIR__ . '/../tests/Generated/CountryDataProvider.php' => new CountryDataProviderBuilder(
                    className: new Name('Tests\Generated\CountryDataProvider'),
                ),
            ];

            $printer = new PhpPrinter([
                'phpVersion' => PhpVersion::fromComponents(8, 2),
            ]);

            foreach ($files as $pathname => $builder) {
                $stmt = $builder->build($data);
                $contents = $printer->prettyPrintFile([$stmt]);

                Assert::notFalse(file_put_contents($pathname, $contents));
                Assert::notFalse(shell_exec("vendor/bin/phpcbf $pathname 2>&1"));
            }
        });
    }

    /**
     * Run a task, rendering its progress.
     *
     * @template TReturn
     * @param non-empty-string $description
     * @param \Closure(): TReturn $task
     * @return TReturn
     */
    private static function renderTask(string $description, Closure $task): mixed
    {
        echo "  $description ";

        $result = $task();

        $dots = max(80 - mb_strlen($description) - 10, 0);

        echo "\033[90m" . str_repeat('.', $dots) . "\033[39m";
        echo " \033[32;1mDONE\033[39;22m" . PHP_EOL;

        return $result;
    }
}
