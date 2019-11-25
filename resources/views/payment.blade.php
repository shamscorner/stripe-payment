<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Stripe Payment Demo Project</title>

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
                <div class="payment-container col-md-6 offset-md-3">
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

                            <form class="needs-validation" action="{{ route('post.checkout') }}" method="post" id="payment-form" novalidate>
                                @csrf
                                <div class="form-group">
                                    <label for="inputEmail">Email address <span class="text-danger font-weight-bold">*</span></label>
                                    <input type="email" class="form-control" id="inputEmail" aria-describedby="emailHelp" name="email" required>
                                    <div class="invalid-feedback">Email is required!</div>
                                </div>
                                <div class="form-group">
                                    <label for="inputNameOnCard">Name on Card <span class="text-danger font-weight-bold">*</span></label>
                                    <input type="text" class="form-control" id="inputNameOnCard" aria-describedby="emailHelp" name="name_on_card" required>
                                    <div class="invalid-feedback">Name on Card is required!</div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="inputAddress">Address</label>
                                        <input type="text" class="form-control" id="inputAddress" name="address">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="inputCity">City</label>
                                        <input type="text" class="form-control" id="inputCity" name="city">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="inputProvince">Province</label>
                                        <input type="text" class="form-control" id="inputProvince" name="province">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="inputPostalCode">Postal Code</label>
                                        <input type="text" class="form-control" id="inputPostalCode" name="postal_code">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="inputCountry">Country</label>
                                        <input type="text" class="form-control" id="inputCountry" name="country">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="inputPhone">Phone</label>
                                        <input type="text" class="form-control" id="inputPhone" name="phone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="card-element">Credit Card</label>
                                    <div id="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <div class="text-center mt-4 mb-3">
                                    <button type="submit" class="c-btn-main">Submit Payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
           </div>
        </div>
        {{-- app.js --}}
        <script src="{{ asset('js/app.js') }}"></script>
        <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
                });
            }, false);
        })();
        </script>

        <script>
        (function() {
            // Create a Stripe client.
            var stripe = Stripe('{{ config('services.stripe.key') }}');

            // Create an instance of Elements.
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Nunito", sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style, 
                hidePostalCode: true
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                var options = {
                    name: document.getElementById('inputNameOnCard').value,
                    address: document.getElementById('inputAddress').value,
                }

                stripe.createToken(card, options).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            // Submit the form with the token ID.
            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }
        })();
        </script>
    </body>
</html>
