<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

use InvalidArgumentException;
use Serixa\Forms\Validation\Rules\AcceptedRule;
use Serixa\Forms\Validation\Rules\BetweenRule;
use Serixa\Forms\Validation\Rules\BooleanRule;
use Serixa\Forms\Validation\Rules\ConfirmedRule;
use Serixa\Forms\Validation\Rules\DateRule;
use Serixa\Forms\Validation\Rules\DifferentRule;
use Serixa\Forms\Validation\Rules\EmailRule;
use Serixa\Forms\Validation\Rules\InRule;
use Serixa\Forms\Validation\Rules\IntegerRule;
use Serixa\Forms\Validation\Rules\MaxRule;
use Serixa\Forms\Validation\Rules\MinRule;
use Serixa\Forms\Validation\Rules\NotInRule;
use Serixa\Forms\Validation\Rules\NullableRule;
use Serixa\Forms\Validation\Rules\NumericRule;
use Serixa\Forms\Validation\Rules\RegexRule;
use Serixa\Forms\Validation\Rules\RequiredRule;
use Serixa\Forms\Validation\Rules\SameRule;
use Serixa\Forms\Validation\Rules\UrlRule;

final class RuleFactory
{
    /** @var array<string, callable(list<string>): Rule> */
    private static array $extensions = [];

    private function __construct()
    {
    }

    /**
     * @param callable(list<string>): Rule $factory
     */
    public static function extend(string $name, callable $factory): void
    {
        self::$extensions[$name] = $factory;
    }

    public static function make(string $rule): Rule
    {
        $rule = trim($rule);

        if ($rule === '') {
            throw new InvalidArgumentException('Rule definition must not be empty.');
        }

        $name = $rule;
        $parameters = [];

        if (str_contains($rule, ':')) {
            [$name, $parameterString] = explode(':', $rule, 2);
            $parameters = self::parseParameters($name, $parameterString);
        }

        if (isset(self::$extensions[$name])) {
            return (self::$extensions[$name])($parameters);
        }

        return match ($name) {
            'required' => new RequiredRule(),
            'email' => new EmailRule(),
            'url' => new UrlRule(),
            'numeric' => new NumericRule(),
            'integer' => new IntegerRule(),
            'boolean' => new BooleanRule(),
            'date' => new DateRule(),
            'accepted' => new AcceptedRule(),
            'nullable' => new NullableRule(),
            'confirmed' => new ConfirmedRule(),
            'min' => new MinRule(self::numericParameter($parameters, 'min')),
            'max' => new MaxRule(self::numericParameter($parameters, 'max')),
            'between' => new BetweenRule(
                self::numericParameter($parameters, 'between', 0),
                self::numericParameter($parameters, 'between', 1),
            ),
            'same' => new SameRule(self::stringParameter($parameters, 'same')),
            'different' => new DifferentRule(self::stringParameter($parameters, 'different')),
            'regex' => new RegexRule(self::stringParameter($parameters, 'regex')),
            'in' => new InRule($parameters),
            'not_in' => new NotInRule($parameters),
            default => throw new InvalidArgumentException("Unknown validation rule [{$name}]."),
        };
    }

    /**
     * @param string|list<string|Rule>|Rule|RuleSet $definition
     */
    public static function parse(string|array|Rule|RuleSet $definition): RuleSet
    {
        if ($definition instanceof RuleSet) {
            return $definition;
        }

        $set = RuleSet::make();

        if ($definition instanceof Rule) {
            return $set->rule($definition);
        }

        if (is_string($definition)) {
            $parts = array_filter(
                array_map('trim', explode('|', $definition)),
                static fn (string $part): bool => $part !== '',
            );

            foreach ($parts as $part) {
                $set->rule(self::make($part));
            }

            return $set;
        }

        foreach ($definition as $item) {
            if ($item instanceof Rule) {
                $set->rule($item);
                continue;
            }

            if ($item instanceof RuleSet) {
                foreach ($item->rules() as $rule) {
                    $set->rule($rule);
                }
                continue;
            }

            if (! is_string($item)) {
                throw new InvalidArgumentException('Rule definitions must be strings, Rule instances, or RuleSet instances.');
            }

            $parts = array_filter(
                array_map('trim', explode('|', $item)),
                static fn (string $part): bool => $part !== '',
            );

            foreach ($parts as $part) {
                $set->rule(self::make($part));
            }
        }

        return $set;
    }

    /**
     * @return list<string>
     */
    private static function parseParameters(string $name, string $parameterString): array
    {
        if ($name === 'regex') {
            return [$parameterString];
        }

        if ($parameterString === '') {
            return [];
        }

        return explode(',', $parameterString);
    }

    /**
     * @param list<string> $parameters
     */
    private static function numericParameter(array $parameters, string $rule, int $index = 0): int|float
    {
        if (! isset($parameters[$index]) || $parameters[$index] === '' || ! is_numeric($parameters[$index])) {
            throw new InvalidArgumentException("The [{$rule}] rule requires a numeric parameter.");
        }

        $value = $parameters[$index];

        return str_contains($value, '.') ? (float) $value : (int) $value;
    }

    /**
     * @param list<string> $parameters
     */
    private static function stringParameter(array $parameters, string $rule, int $index = 0): string
    {
        if (! isset($parameters[$index]) || $parameters[$index] === '') {
            throw new InvalidArgumentException("The [{$rule}] rule requires a parameter.");
        }

        return $parameters[$index];
    }
}
