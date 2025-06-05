<?php

declare(strict_types=1);

namespace App\Services\Llm;

use Illuminate\Http\UploadedFile;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Text\PendingRequest;
use Prism\Prism\Text\Response;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class FeedbackAnalyzingService
{
    protected PendingRequest $prism;

    public function __construct()
    {
        $this->prism = Prism::text()
            ->using(Provider::Gemini, 'gemini-2.0-flash-lite')
            ->withSystemPrompt(
                'Створи короткий підсумок цього відгуку. Відгук стосується аналізу [**опис зображення/документа або ключові результати його аналізу**]. Узагальни основні зауваження та пропозиції з відгуку, враховуючи контекст наданого [зображення/документа].'
            );
    }

    public function message(string $text, UploadedFile $uploadedFile): static
    {
        $this->prism->withMessages([
            new UserMessage(
                $text,
                [
                    Image::fromPath($uploadedFile->path()),
                ]
            ),
        ]);

        return $this;
    }

    public function analyze(): Response
    {
        return $this->prism->asText();
    }
}
