<?php

declare(strict_types=1);

namespace Serixa\Forms\Binding;

use ArrayAccess;
use ArrayObject;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Traversable;

final class FormBinder
{
    /** @var array<string, mixed> */
    private array $defaults = [];

    /** @var array<string, mixed> */
    private array $bound = [];

    /** @var array<string, mixed> */
    private array $filled = [];

    private bool $hasFill = false;

    private OldInput $old;

    private function __construct()
    {
        $this->old = OldInput::make();
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * @param array<string, mixed> $defaults
     */
    public function defaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @param object|array<string, mixed> $model
     */
    public function bind(object|array $model): self
    {
        $this->bound = $this->extractModel($model);

        return $this;
    }

    /**
     * @param RequestData|array<string, mixed> $data
     */
    public function fill(RequestData|array $data): self
    {
        $this->filled = $data instanceof RequestData ? $data->all() : $data;
        $this->hasFill = true;

        return $this;
    }

    /**
     * @param OldInput|array<string, mixed>|null $old
     */
    public function oldInput(OldInput|array|null $old = null): self
    {
        if ($old === null) {
            $this->old = $this->hasFill
                ? OldInput::make($this->filled)
                : OldInput::make();
        } elseif (is_array($old)) {
            $this->old = OldInput::make($old);
        } else {
            $this->old = $old;
        }

        return $this;
    }

    public function toFormState(): FormState
    {
        return FormState::make()
            ->withDefaults($this->defaults)
            ->withValues($this->mergeLayers($this->bound, $this->filled))
            ->withOldInput($this->old);
    }

    /**
     * Merged values with order: defaults < bind < fill < old (old wins).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->mergeLayers(
            $this->defaults,
            $this->bound,
            $this->filled,
            $this->old->all(),
        );
    }

    /**
     * @param object|array<string, mixed> $model
     * @return array<string, mixed>
     */
    private function extractModel(object|array $model): array
    {
        if (is_array($model)) {
            return $model;
        }

        if ($model instanceof ArrayObject) {
            /** @var array<string, mixed> $copy */
            $copy = $model->getArrayCopy();

            return $copy;
        }

        if ($model instanceof Traversable) {
            /** @var array<string, mixed> $items */
            $items = iterator_to_array($model);

            return $items;
        }

        if ($model instanceof ArrayAccess) {
            return get_object_vars($model);
        }

        return get_object_vars($model);
    }

    /**
     * @param array<string, mixed> ...$layers
     * @return array<string, mixed>
     */
    private function mergeLayers(array ...$layers): array
    {
        $merged = [];

        foreach ($layers as $layer) {
            foreach ($layer as $key => $value) {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
