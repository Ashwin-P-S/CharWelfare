<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\ClassificationController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('train:dataset', function () {
    $this->comment("Training the dataset...");

    // Create an instance of ClassificationController
    $controller = App::make(ClassificationController::class);

    // Create a dummy request object
    $request = new Request();

    // Call the trainModel method
    try {
        $controller->trainModel($request);
        $this->info("Model training completed successfully.");
    } catch (Exception $e) {
        $this->error("Error during model training: " . $e->getMessage());
    }
})->purpose('Train the dataset for the Random Forest model');
