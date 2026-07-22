<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

use Serixa\Forms\Validation\Rules\NullableRule;

final class Validator
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, RuleSet|list<string|Rule>|string|Rule> $rules
     */
    private function __construct(
        private readonly array $data,
        private readonly array $rules,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, RuleSet|list<string|Rule>|string|Rule> $rules
     */
    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    public function validate(): ValidationResult
    {
        /** @var list<ValidationError> $errors */
        $errors = [];

        foreach ($this->rules as $attribute => $definition) {
            $ruleSet = RuleFactory::parse($definition);
            $value = $this->data[$attribute] ?? null;

            if ($ruleSet->isNullable() && $this->isEmpty($value)) {
                continue;
            }

            foreach ($ruleSet->rules() as $rule) {
                if ($rule instanceof NullableRule) {
                    continue;
                }

                if ($rule->passes($attribute, $value, $this->data)) {
                    continue;
                }

                $errors[] = ValidationError::make(
                    $attribute,
                    $rule->message($attribute, $value, $this->data),
                    $this->ruleName($rule),
                );
            }
        }

        if ($errors === []) {
            return ValidationResult::pass();
        }

        return ValidationResult::fail(...$errors);
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '' || $value === [];
    }

    private function ruleName(Rule $rule): string
    {
        $class = (new \ReflectionClass($rule))->getShortName();

        if (str_ends_with($class, 'Rule')) {
            $class = substr($class, 0, -4);
        }

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class) ?? $class);
    }
}
