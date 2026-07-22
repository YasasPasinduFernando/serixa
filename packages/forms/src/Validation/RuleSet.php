<?php

declare(strict_types=1);

namespace Serixa\Forms\Validation;

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

final class RuleSet
{
    /** @var list<Rule> */
    private array $rules = [];

    private bool $nullable = false;

    private bool $required = false;

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function rule(Rule $rule): self
    {
        if ($rule instanceof NullableRule) {
            $this->nullable = true;
        }

        if ($rule instanceof RequiredRule) {
            $this->required = true;
        }

        $this->rules[] = $rule;

        return $this;
    }

    public function required(): self
    {
        return $this->rule(new RequiredRule());
    }

    public function email(): self
    {
        return $this->rule(new EmailRule());
    }

    public function url(): self
    {
        return $this->rule(new UrlRule());
    }

    public function numeric(): self
    {
        return $this->rule(new NumericRule());
    }

    public function integer(): self
    {
        return $this->rule(new IntegerRule());
    }

    public function boolean(): self
    {
        return $this->rule(new BooleanRule());
    }

    public function date(): self
    {
        return $this->rule(new DateRule());
    }

    public function accepted(): self
    {
        return $this->rule(new AcceptedRule());
    }

    public function nullable(): self
    {
        return $this->rule(new NullableRule());
    }

    public function min(int|float $min): self
    {
        return $this->rule(new MinRule($min));
    }

    public function max(int|float $max): self
    {
        return $this->rule(new MaxRule($max));
    }

    public function between(int|float $min, int|float $max): self
    {
        return $this->rule(new BetweenRule($min, $max));
    }

    public function confirmed(): self
    {
        return $this->rule(new ConfirmedRule());
    }

    public function same(string $other): self
    {
        return $this->rule(new SameRule($other));
    }

    public function different(string $other): self
    {
        return $this->rule(new DifferentRule($other));
    }

    public function regex(string $pattern): self
    {
        return $this->rule(new RegexRule($pattern));
    }

    /**
     * @param list<mixed> $values
     */
    public function in(array $values): self
    {
        return $this->rule(new InRule(array_values($values)));
    }

    /**
     * @param list<mixed> $values
     */
    public function notIn(array $values): self
    {
        return $this->rule(new NotInRule(array_values($values)));
    }

    /**
     * @return list<Rule>
     */
    public function rules(): array
    {
        return $this->rules;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function hasRequired(): bool
    {
        return $this->required;
    }
}
