<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Textarea;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;
use Serixa\Rendering\Renderer;

$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
$defaults = [
    'name' => 'Ada Lovelace',
    'email' => 'ada@serixa.dev',
    'bio' => 'Building things with SERIXA.',
];

$result = Validator::make($data === [] ? $defaults : $data, [
    'name' => 'required|min:2|max:120',
    'email' => 'required|email|max:255',
    'bio' => 'nullable|max:500',
])->validate();

$state = FormState::make()
    ->withDefaults($defaults)
    ->withOldInput(OldInput::make($data === [] ? $defaults : $data))
    ->withErrors($data === [] ? \Serixa\Forms\State\ErrorsBag::make() : $result->toErrorsBag());

$form = FormBuilder::make()
    ->action('#')
    ->state($state)
    ->field(Input::make('name')->label('Display name')->required()->min(2)->max(120))
    ->field(Input::make('email')->label('Email')->type('email')->required()->email()->max(255))
    ->field(Textarea::make('bio')->label('Bio')->rows(4)->nullable()->max(500))
    ->submit('Save profile')
    ->toWidget();

$children = [];

if ($data !== [] && $result->failed()) {
    $children[] = ValidationSummary::make($result->toErrorsBag());
}

if ($data !== [] && $result->passed()) {
    $children[] = SuccessMessage::make('Profile looks valid (demo only — nothing was saved).');
}

$children[] = $form;

$document = Document::make()->title('Profile · SERIXA Forms')->body(Column::make(...$children)->gap(4));
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
