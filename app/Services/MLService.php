<?php

namespace App\Services;

use Phpml\Exception\FileException;
use Phpml\Exception\InvalidArgumentException;
use Phpml\Exception\SerializeException;
use Phpml\ModelManager;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;

class MLService
{
    protected $model;
    protected $vectorizer;

    /**
     * @throws FileException
     * @throws SerializeException
     */
    public function __construct()
    {
        $this->model = (new ModelManager())->restoreFromFile(storage_path('app\public\svm_model.pkl'));
        $this->vectorizer = (new ModelManager())->restoreFromFile(storage_path('app\public\vectorizer.pkl'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function predict($message)
    {
        $tfidf = new TfIdfTransformer();
        $tokenizer = new WhitespaceTokenizer();

        $tokens = $tokenizer->tokenize($message);
        $tfidf->transform($tokens);

        return $this->model->predict($tokens);
    }
}
