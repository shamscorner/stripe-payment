<template>
    <form
        class="needs-validation"
        action="/checkout"
        method="post"
        id="payment-form"
        @submit.prevent="pay()"
        novalidate
    >
        <!-- CSRF Field -->
        <input type="hidden" name="_token" :value="csrf" />

        <div class="form-group">
            <label for="inputEmail"
                >Email address
                <span class="text-danger font-weight-bold">*</span></label
            >
            <input
                type="email"
                class="form-control"
                id="inputEmail"
                aria-describedby="emailHelp"
                name="email"
                required
            />
            <div class="invalid-feedback">Email is required!</div>
        </div>
        <div class="form-group">
            <label for="inputNameOnCard"
                >Name on Card
                <span class="text-danger font-weight-bold">*</span></label
            >
            <input
                type="text"
                class="form-control"
                id="inputNameOnCard"
                aria-describedby="emailHelp"
                name="name_on_card"
                v-model="name_on_card"
                required
            />
            <div class="invalid-feedback">Name on Card is required!</div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="inputAddress">Address</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputAddress"
                    name="address"
                    v-model="address"
                />
            </div>
            <div class="col-md-4 mb-3">
                <label for="inputCity">City</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputCity"
                    name="city"
                />
            </div>
            <div class="col-md-4 mb-3">
                <label for="inputProvince">Province</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputProvince"
                    name="province"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="inputPostalCode">Postal Code</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputPostalCode"
                    name="postal_code"
                />
            </div>
            <div class="col-md-4 mb-3">
                <label for="inputCountry">Country</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputCountry"
                    name="country"
                />
            </div>
            <div class="col-md-4 mb-3">
                <label for="inputPhone">Phone</label>
                <input
                    type="text"
                    class="form-control"
                    id="inputPhone"
                    name="phone"
                />
            </div>
        </div>
        <div class="form-group">
            <label for="card-element">Credit Card</label>
            <stripe-card-element></stripe-card-element>
        </div>

        <div class="text-center mt-4 mb-3">
            <button type="submit" class="c-btn-main">Submit Payment</button>
        </div>
    </form>
</template>

<script>
import { createToken } from "vue-stripe-elements-plus";

export default {
    data() {
        return {
            csrf: document.head.querySelector('meta[name="csrf-token"]')
                .content,
            name_on_card: "",
            address: ""
        };
    },

    methods: {
        pay() {
            // createToken returns a Promise which resolves in a result object with
            // either a token or an error key.
            // See https://stripe.com/docs/api#tokens for the token object.
            // See https://stripe.com/docs/api#errors for the error object.
            // More general https://stripe.com/docs/stripe.js#stripe-create-token.

            var options = {
                name: this.name_on_card,
                address: this.address
            };

            createToken(options).then(result => {
                if (result.token) {
                    var hiddenInput = document.createElement("input");
                    hiddenInput.setAttribute("type", "hidden");
                    hiddenInput.setAttribute("name", "stripeToken");
                    hiddenInput.setAttribute("value", result.token.id);
                    this.$el.appendChild(hiddenInput);

                    // Submit the form
                    this.$el.submit();
                }
            });
        }
    },

    mounted() {
        window.addEventListener(
            "load",
            function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName("needs-validation");
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(
                    form
                ) {
                    form.addEventListener(
                        "submit",
                        function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add("was-validated");
                        },
                        false
                    );
                });
            },
            false
        );
    }
};
</script>
