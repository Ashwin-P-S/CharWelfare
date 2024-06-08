<?php

namespace App\Http\Controllers;

require 'vendor/autoload.php';

use Phpml\Pipeline;
use Phpml\ModelManager;
use Illuminate\Http\Request;
use Phpml\Dataset\CsvDataset;
use Phpml\Exception\FileException;
use Phpml\Exception\SerializeException;
use Phpml\Exception\InvalidOperationException;
use Phpml\Classification\Ensemble\RandomForest;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TokenCountVectorizer;

class ClassificationController extends Controller
{
    /**
     * @throws FileException
     * @throws SerializeException
     * @throws InvalidOperationException
     */
    public function trainModel(Request $request): void
    {
        $dataset = new CsvDataset(storage_path('app/dataset.csv'), 1);

        // Extract queries and labels
        $userQueries = $dataset->getSamples();
        $labels = $dataset->getTargets();

        $queries = array();
        array_walk_recursive($userQueries, function ($a) use (&$queries) {
            $queries[] = $a;
        });

        // Create a pipeline with a vectorizer and the Random Forest classifier
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $classifier = new RandomForest();

        $pipeline = new Pipeline([$vectorizer], $classifier);

        // Train the model
        $pipeline->train($queries, $labels);

        // Save the trained model
        $modelManager = new ModelManager();
        $modelManager->saveToFile($pipeline, storage_path('app/randomForestModel.phpml'));
    }
}
