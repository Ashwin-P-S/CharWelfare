<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\Session;
use Phpml\Exception\FileException;
use Phpml\Exception\SerializeException;
use Phpml\ModelManager;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BotManController extends Controller
{

    protected $modelManager;
    protected $classifier;
    protected $botman;
    protected $schemes;

    public function __construct()
    {
        $this->botman = app('botman');

        $this->schemes = Scheme::select('disability')
            ->groupBy('disability')
            ->get();

        $this->modelManager = new ModelManager();
        $this->classifier = $this->modelManager->restoreFromFile(storage_path('app/randomForestModel.phpml'));
    }

    public function handle(): void
    {
        $botman = $this->botman;

        $botman->hears(".*", function ($botman) {
            $message = $botman->getMessage()->getText();
            $this->detectLanguageAndProcessInformation($message, $botman);
        });

        $botman->listen();
    }

    public function showAllAvailableSchemes($botman): void
    {
        $schemesButton = [];

        foreach ($this->schemes as $scheme) {
            $button = Button::create(
                $this->detectAndTranslateLanguage(strtoupper($scheme->disability))
            )->value($scheme->disability . " schemes");

            $schemesButton[] = $button;
        }

        $question = Question::create($this->detectAndTranslateLanguage("Schemes based on disabilities:"))
            ->callbackId('disability_schemes')
            ->addButtons($schemesButton);

        $botman->typesAndWaits(1);
        $botman->reply($question);
    }

    public function detectLanguageAndProcessInformation($message, $botman): void
    {
        $lang = Session::get("lang");

        if ($lang != "en")
            $message = $this->translateInformation($message, "en");

        // Greeting!
        if (preg_match("/Hello|Hi|hi|hello/", $message)) {
            $this->showMessage($botman, "Hello!");
            $this->showInformation($botman);
        }

        // Get all available Schemes
        else if ($message == "list")
            $this->showAllAvailableSchemes($botman);

        // Helper function to provide the Chatbot Information
        else if ($message == "help" || preg_match("/help|Help/i", $message))
            $this->showInformation($botman);

        // Get the Schemes using the provided information
        else if (preg_match("/(.*)\s+schemes?/i", $message)) {
            // $disability = preg_split("/scheme|schemes/", $message)[0];
            $disability = explode(" ", $message)[0];
            $this->findSchemes($botman, $disability);
        }

        // Show some Gratitude!
        else if (preg_match("/thank|you/i", $message)) {
            $message = "It's my duty to help you!";
            $this->showMessage($botman, $message);
        }

        // Handling invalid user input
        else {
            $errorMessage = "Sorry, I can't understand. Please type 'help' for more information.";
            $this->showMessage($botman, $errorMessage);
        }
    }

    public function detectAndTranslateLanguage($message): string
    {
        $lang = Session::get("lang");

        if ($lang != "en") {
            $message = $this->translateInformation($message, $lang);
        }

        return $message;
    }

    public function translateInformation($message, $lang): string
    {
        $translator = new GoogleTranslate();
        return $translator->setTarget($lang)->translate($message);
    }

    public function showInformation($botman): void
    {
        $botman->typesAndWaits(1);

        $message = "ChatWelfare is an chatbot, which finds various Tamil Nadu Gov Schemes for PwD's.";
        $message .= "<br><br>Please provide the following details:";
        $message .= "<br>< Your_Disability > scheme";
        $message .= "<br><br>(or) Type \"list\" to display all available Schemes.";

        $message = $this->detectAndTranslateLanguage($message);
        $botman->reply($message);
    }

    public function showMessage($botman, $message): void
    {
        $message = $this->detectAndTranslateLanguage($message);
        $botman->typesAndWaits(1);
        $botman->reply($message);
    }

    public function findSchemes($botman, $disabilities): void
    {
        $schemes = Scheme::where("disability", "like", "%" . strtolower($disabilities) . "%")->get();

        if (count($schemes) == 0) {
            $message = "Currently, No schemes available for the given requirements.";
            $this->showMessage($botman, $message);
            return;
        }

        $message = "Totally " . $schemes->count() . " Schemes are available for \"" . $disabilities . "\" disability:";
        $this->showMessage($botman, $message);
        $this->displaySchemeInformation($botman, $schemes);
    }

    public function findAllSchemes($botman): void
    {
        $schemes = Scheme::all();
        if (count($schemes) == 0) {
            $message = "Currently, No schemes are available.";
            $this->showMessage($botman, $message);
            return;
        }

        $message = "Totally " . $schemes->count() . " Schemes are available: ";
        $this->showMessage($botman, $message);
        $this->displaySchemeInformation($botman, $schemes);
    }

    public function displaySchemeInformation($botman, $schemes): void
    {
        foreach ($schemes as $scheme) {
            $botman->typesAndWaits(1);
            $answer = "Scheme Name: " . $scheme->name . "<br><br>";
            $answer .= "Type of Disability: " . $scheme->disability . "<br><br>";
            $answer .= "Description: <br>" . $scheme->description . "<br><br>";
            $answer .= "How to Apply / Application Form:";

            $answer = $this->detectAndTranslateLanguage($answer);
            $answer .= $scheme->how_to_apply;
            $botman->reply($answer);
        }
    }
}
