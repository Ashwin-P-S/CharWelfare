@extends('layouts.app')

@php
    $heading = "Welcome to ChatWelfare";
    $subHead = "An Interactive Chatbot for Accessing Government Schemes";
    $description = "ChatWelfare is a chatbot designed to provide information on government schemes, especially for
        physically challenged individuals in Tamil Nadu. It serves as an easy-to-use interface for accessing
        information about various welfare programs offered by the government.";

    $features = [
        "Get information about government schemes for physically challenged individuals.",
        "Use voice input for easy interaction.",
        "Regional languages for Chatbot interaction"
    ];

    $how_to_use = [
        "Click on <img src=\"assets\img\bot_icon.jpg\" style=\"border-radius: 100%; height: 30px;\"> to open the Chatbot.",
        "Type \"your_disability scheme\" in the input field to get desired schemes.",
        "Alternatively, click on <i class=\"fa-solid fa-microphone\"></i> to use voice input for your Messages.",
        "The chatbot will respond with information about government schemes based on your queries."
    ];
@endphp

@section('content')
    <div class="container text-white p-5"
         style="background: #1F2544; border-radius: 50px 0; box-shadow: 0 2px 10px black;">
        <h1> {{ $translator->translate($heading) }} </h1>
        <h3> {{ $translator->translate($subHead) }} </h3>
        <h5> {{ $translator->translate($description) }} </h5>
        <div class="pt-4">
            <button id="voiceInputBtn" class="btn btn-danger rounded rounded-circle border-2">
                <i class="fa-solid fa-microphone"></i>
            </button>
        </div>
        <br>
        <h3> {{ $translator->translate("Features:") }} </h3>
        <ul>
            @foreach($features as $feature)
                <li> {{ $translator->translate($feature) }} </li>
            @endforeach
        </ul>
        <h3> {{ $translator->translate("How to Use:") }} </h3>
        <ol>
            @foreach($how_to_use as $use)
                <li> <?php echo $translator->translate($use); ?> </li>
            @endforeach
        </ol>
        <li class="text-center list-unstyled">
            &copy; ChatWelfare
        </li>
    </div>
@endsection
