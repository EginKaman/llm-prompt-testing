<?php

declare(strict_types=1);

use App\Facades\Rake;
use App\Services\Llm\FeedbackAnalyzingService;
use App\Services\Stemmer\StemmerFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

test('сheck the response llm to hallucinations', function (): void {
    $expectedKeywords = collect([
        'відгук',
        'баг',
        'генератор',
        'зображен',
        'низьк',
        'low',
        'quality',
        'bug',
        'picture',
        'комах',
    ]);

    $imageContent = Storage::disk('local')->get('testing/bug.png');

    Storage::fake('local');

    $image = UploadedFile::fake()->createWithContent('test.jpg', $imageContent);

    $feedbackAnalyzing = new FeedbackAnalyzingService();
    $feedbackAnalyzing->message('Тестовий відгук про тестовий баг в нашому генераторі генераторів зображень.', $image);
    $response = $feedbackAnalyzing->analyze();

    $stemmer = StemmerFactory::create('uk');

    $keywords = collect(Rake::make($response->text)->keywords())
        ->map(fn(string $keyword) => Str::replaceMatches('/[*`+\d.\/\]\[]/m', '', $keyword));

    $intersectKeywords = $expectedKeywords->intersect($keywords->map(function (string $keyword) use ($stemmer) {
        try {
            return $stemmer->stem($keyword);
        } catch (Exception $e) {
            throw new RuntimeException("Stemmer error: {$e->getMessage()} with keyword: {$keyword}");
        }
    }));

    expect($intersectKeywords->count() / $expectedKeywords->count())
        ->toBeGreaterThanOrEqual(0.5);
});
