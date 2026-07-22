<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Assets\StyleSheet;

use Serixa\Component\Column;
use Serixa\Document\Document;
use Serixa\Forms\Builder\FormBuilder;
use Serixa\Forms\Components\SuccessMessage;
use Serixa\Forms\Components\ValidationSummary;
use Serixa\Forms\Field\Checkbox;
use Serixa\Forms\Field\Input;
use Serixa\Forms\Field\Select;
use Serixa\Forms\State\FormState;
use Serixa\Forms\State\OldInput;
use Serixa\Forms\Validation\Validator;
use Serixa\Rendering\Renderer;

$data = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
$defaults = [
    'workspace_name' => 'SERIXA Admin',
    'support_email' => 'support@serixa.dev',
    'timezone' => 'UTC',
    'allow_invites' => '1',
];

$result = Validator::make($data === [] ? $defaults : $data, [
    'workspace_name' => 'required|min:2|max:120',
    'support_email' => 'required|email',
    'timezone' => 'required|in:UTC,Asia/Colombo,America/New_York,Europe/London',
    'allow_invites' => 'boolean',
])->validate();

$state = FormState::make()
    ->withDefaults($defaults)
    ->withOldInput(OldInput::make($data === [] ? $defaults : $data))
    ->withErrors($data === [] ? \Serixa\Forms\State\ErrorsBag::make() : $result->toErrorsBag());

$form = FormBuilder::make()
    ->action('#')
    ->state($state)
    ->field(Input::make('workspace_name')->label('Workspace name')->required()->min(2)->max(120))
    ->field(Input::make('support_email')->label('Support email')->type('email')->required()->email())
    ->field(Select::make('timezone')->label('Timezone')->required()->options([
        'UTC' => 'UTC',
        'Asia/Colombo' => 'Asia/Colombo',
        'America/New_York' => 'America/New_York',
        'Europe/London' => 'Europe/London',
    ]))
    ->field(Checkbox::make('allow_invites')->label('Allow admins to invite new users')->boolean())
    ->submit('Save settings')
    ->toWidget();

$children = [];

if ($data !== [] && $result->failed()) {
    $children[] = ValidationSummary::make($result->toErrorsBag());
}

if ($data !== [] && $result->passed()) {
    $children[] = SuccessMessage::make('Settings look valid (demo only).');
}

$children[] = $form;

$document = Document::make()->title('Settings · SERIXA Forms')->body(Column::make(...$children)->gap(4));
StyleSheet::register($document->assets());

echo (new Renderer())->render($document), PHP_EOL;
