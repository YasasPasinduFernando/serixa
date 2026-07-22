<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Input;
use Serixa\Forms\State\ErrorsBag;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;
use Serixa\Rendering\Renderer;
use Serixa\Styling\StyleSheet;

$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
$result = Validator::make($data, [
    'email' => 'required|email',
    'password' => 'required|min:8',
])->validate();

$state = FormState::make()
    ->withOldInput(OldInput::make($data))
    ->withErrors($data === [] ? ErrorsBag::make() : $result->toErrorsBag());

$form = FormBuilder::make()
    ->action('#')
    ->state($state)
    ->field(Input::make('email')->label('Email')->type('email')->required()->email()->autocomplete('email'))
    ->field(Input::make('password')->label('Password')->type('password')->required()->min(8)->autocomplete('current-password'))
    ->submit('Sign in')
    ->toWidget();

$children = [];

if ($result->failed() && $data !== []) {
    $children[] = ValidationSummary::make($result->toErrorsBag());
}

$children[] = $form;

$document = Document::make()->title('Login · SERIXA Forms')->body(
    Column::make(...$children)->gap(4),
);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
