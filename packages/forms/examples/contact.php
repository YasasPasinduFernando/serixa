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

$result = Validator::make($data, [
    'name' => 'required|min:2',
    'email' => 'required|email',
    'subject' => 'required|max:120',
    'message' => 'required|min:10|max:2000',
])->validate();

$state = FormState::make()
    ->withOldInput(OldInput::make($data))
    ->withErrors($data === [] ? \Serixa\Forms\State\ErrorsBag::make() : $result->toErrorsBag());

$form = FormBuilder::make()
    ->action('#')
    ->state($state)
    ->field(Input::make('name')->label('Name')->required()->min(2))
    ->field(Input::make('email')->label('Email')->type('email')->required()->email())
    ->field(Input::make('subject')->label('Subject')->required()->max(120))
    ->field(Textarea::make('message')->label('Message')->required()->min(10)->max(2000)->rows(6))
    ->submit('Send message')
    ->toWidget();

$children = [];

if ($data !== [] && $result->failed()) {
    $children[] = ValidationSummary::make($result->toErrorsBag());
}

if ($data !== [] && $result->passed()) {
    $children[] = SuccessMessage::make('Message looks valid (demo only — nothing was sent).');
}

$children[] = $form;

$document = Document::make()->title('Contact · SERIXA Forms')->body(Column::make(...$children)->gap(4));
$document->assets()->js('https://cdn.tailwindcss.com');

echo (new Renderer())->render($document), PHP_EOL;
