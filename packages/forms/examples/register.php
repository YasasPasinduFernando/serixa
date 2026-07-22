<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;
use Serixa\Rendering\Renderer;
use Serixa\Styling\StyleSheet;

$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];

$result = Validator::make($data, [
    'name' => 'required|min:2|max:120',
    'email' => 'required|email|max:255',
    'password' => 'required|min:8|confirmed',
    'terms' => 'accepted',
])->validate();

$state = FormState::make()
    ->withOldInput(OldInput::make($data))
    ->withErrors($data === [] ? \Serixa\Forms\State\ErrorsBag::make() : $result->toErrorsBag());

$form = FormBuilder::make()
    ->action('#')
    ->state($state)
    ->field(Input::make('name')->label('Name')->required()->min(2)->max(120))
    ->field(Input::make('email')->label('Email')->type('email')->required()->email()->max(255))
    ->field(Input::make('password')->label('Password')->type('password')->required()->min(8)->confirmed())
    ->field(Input::make('password_confirmation')->label('Confirm password')->type('password')->required())
    ->field(Checkbox::make('terms')->label('I accept the terms')->accepted())
    ->submit('Create account')
    ->toWidget();

$children = [];

if ($data !== [] && $result->failed()) {
    $children[] = ValidationSummary::make($result->toErrorsBag());
}

if ($data !== [] && $result->passed()) {
    $children[] = SuccessMessage::make('Registration looks valid (demo only).');
}

$children[] = $form;

$document = Document::make()->title('Register · SERIXA Forms')->body(Column::make(...$children)->gap(4));
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
