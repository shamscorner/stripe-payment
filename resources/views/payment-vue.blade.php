<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Stripe Payment Using Vue Demo Project</title>

        <!-- stripe -->
        <script src="https://js.stripe.com/v3/"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                font-family: 'Nunito', sans-serif;
                margin: 0;
                padding: 10px 0px 10px 5px;
            }
            /**
            * The CSS shown here will not be introduced in the Quickstart guide, but shows
            * how you can use CSS to style your Element's container.
            */
            .StripeElement {
                box-sizing: border-box;
                height: 40px;
                padding: 10px 12px;
                border: 1px solid #ccd0d2;
                border-radius: 4px;
                background-color: white;
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
                -webkit-transition: box-shadow 150ms ease;
                transition: box-shadow 150ms ease;
            }

            .StripeElement--focus {
                box-shadow: 0 1px 3px 0 #cfd7df;
            }

            .StripeElement--invalid {
                border-color: #fa755a;
            }

            .StripeElement--webkit-autofill {
                background-color: #fefde5 !important;
            }

            #card-errors {
                color: #fa755a;
            }
        </style>

        <!-- app.css -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app" class="container min-vh-100">
           <div class="row min-vh-100 align-items-center">
                <div class="col-md-6 offset-md-3">
                    <div class="payment-container">
                        <div class="container-back">
                            <div class="container-front">
                                <h1 class="text-center">Pay Here</h1>
                                <hr>
    
                                @if (session()->has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session()->get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
    
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
    
                                <payment-form></payment-form>
                            </div>
                        </div>
                    </div>    
                </div> 
           </div>
        </div>
        {{-- app.js --}}
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
