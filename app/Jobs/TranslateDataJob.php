<?php

namespace App\Jobs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class TranslateDataJob implements ShouldQueue
{
    use Queueable;

    public $model;
    public $language;
    /**
     * Create a new job instance.
     */
    public function __construct($model, string $language)
    {
        $this->model = $model;
        $this->language = $language;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Choose fields to translate dynamically
        $translatable = property_exists($this->model, 'translatable')
            ? $this->model->translatable
            : [];

        if (empty($translatable)) {
            return;
        }

        $payload = [
            'data' => $this->model->only($translatable),
            'to' => [$this->language],
        ];
        $response = Http::post('http://localhost:8080/translate', $payload);
        if ($response->successful()) {
            $translations = $response->body();
            $this->model->update([
                'translation' => $translations,
            ]);
        }
    }
}
