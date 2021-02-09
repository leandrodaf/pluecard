<html>

<head>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
</head>

<body>
    <form action="http://localhost/payments/items/1/gateways/mercado-pago" method="post" id="paymentForm">
        <h3>Detalhe do comprador</h3>
        <div>
            <div>
                <label for="email">E-mail</label>
                <input id="email" name="payer[email]" type="text" value="test@test.com" />
            </div>
            <div>
                <label for="docType">Tipo de documento</label>
                <select id="docType" name="payer[identification][type]" data-checkout="docType" type="text"></select>
            </div>
            <div>
                <label for="docNumber">Número do documento</label>
                <input id="docNumber" name="payer[identification][number]" data-checkout="docNumber" type="text" value="48989553040" />
            </div>

            <div>
                <label for="name">Nome:</label>
                <input id="name" name="payer[first_name]" type="text" value="Raul" />
            </div>

            <div>
                <label for="surname">Sobrenome:</label>
                <input id="surname" name="payer[last_name]" type="text" value="Alves" />
            </div>

            <!-- <div>
                <label for="area_code">DDD:</label>
                <input id="area_code" name="payer[phone][area_code]" type="text" value="011" />
            </div>

            <div>
                <label for="number">Número do telefone:</label>
                <input id="number" name="payer[phone][number]" type="text" value="995447361" />
            </div> -->

            <div>
                <label for="street_name">Rua:</label>
                <input id="street_name" name="payer[address][street_name]" type="text" value="Rua Santa Bernadete" />
            </div>

            <div>
                <label for="street_number">Número da rua:</label>
                <input id="street_number" name="payer[address][street_number]" type="text" value="654" />
            </div>

            <div>
                <label for="zip_code">CEP</label>
                <input id="zip_code" name="payer[address][zip_code]" type="text" value="09941300" />
            </div>
        </div>
        <h3>Detalhes do cartão</h3>
        <div>
            <div>
                <label for="cardholderName">Titular do cartão</label>
                <input id="cardholderName" data-checkout="cardholderName" type="text" value="Jozãozito Fonstes">
            </div>
            <div>
                <label for="">Data de vencimento</label>
                <div>
                    <input type="text" placeholder="MM" id="cardExpirationMonth" data-checkout="cardExpirationMonth" onselectstart="return false" onpaste="return false" oncopy="return false" oncut="return false" ondrag="return false" ondrop="return false" autocomplete=off value="11">
                    <span class="date-separator">/</span>
                    <input type="text" placeholder="YY" id="cardExpirationYear" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" oncopy="return false" oncut="return false" ondrag="return false" ondrop="return false" autocomplete=off value="25">
                </div>
            </div>
            <div>
                <label for="cardNumber">Número do cartão</label>
                <input type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" oncopy="return false" oncut="return false" ondrag="return false" ondrop="return false" autocomplete=off value="5031433215406351">
            </div>
            <div>
                <label for="securityCode">Código de segurança</label>
                <input id="securityCode" data-checkout="securityCode" type="text" onselectstart="return false" onpaste="return false" oncopy="return false" oncut="return false" ondrag="return false" ondrop="return false" autocomplete=off value="123">
            </div>
            <div id="issuerInput">
                <label for="issuer">Banco emissor</label>
                <select id="issuer" name="issuer_id" data-checkout="issuer"></select>
            </div>
            <div>
                <label for="installments">Parcelas</label>
                <select type="text" id="installments" name="installments"></select>
            </div>
            <div>
                <input type="hidden" name="transaction_amount" id="transaction_amount" value="100" />
                <input type="hidden" name="payment_method_id" id="payment_method_id" />
                <input type="hidden" name="description" id="description" />
                <br>
                <button type="submit">Pagar</button>
                <br>
            </div>
        </div>
    </form>
</body>

<script>
    window.Mercadopago.setPublishableKey("TEST-f6a28683-f329-4a85-a2dc-3bd5b84c7b1c");
    window.Mercadopago.getIdentificationTypes();


    window.Mercadopago.getPaymentMethod({
        "bin": 503143
    }, setPaymentMethod);

    // document.getElementById('cardNumber').addEventListener('change', guessPaymentMethod);

    // function guessPaymentMethod(event) {
    //     let cardnumber = document.getElementById("cardNumber").value;
    //     if (cardnumber.length >= 6) {
    //         let bin = cardnumber.substring(0, 6);
    //         console.log('>>>>>',bin);
    //         window.Mercadopago.getPaymentMethod({
    //             "bin": bin
    //         }, setPaymentMethod);
    //     }
    // };

    function setPaymentMethod(status, response) {
        if (status == 200) {
            let paymentMethod = response[0];
            document.getElementById('payment_method_id').value = paymentMethod.id;

            getIssuers(paymentMethod.id);
        } else {
            alert(`payment method info error: ${response}`);
        }
    }

    function getIssuers(payment_method_id) {
        window.Mercadopago.getIssuers(
            payment_method_id,
            setIssuers
        );
    }

    function setIssuers(status, response) {
        if (status == 200) {
            let issuerSelect = document.getElementById('issuer');
            response.forEach(issuer => {
                let opt = document.createElement('option');
                opt.text = issuer.name;
                opt.value = issuer.id;
                issuerSelect.appendChild(opt);
            });

            getInstallments(
                document.getElementById('payment_method_id').value,
                document.getElementById('transaction_amount').value,
                issuerSelect.value
            );
        } else {
            alert(`issuers method info error: ${response}`);
        }
    }

    function getInstallments(payment_method_id, transaction_amount, issuerId) {
        window.Mercadopago.getInstallments({
            "payment_method_id": payment_method_id,
            "amount": parseFloat(transaction_amount),
            "issuer_id": parseInt(issuerId)
        }, setInstallments);
    }

    function setInstallments(status, response) {
        if (status == 200) {
            document.getElementById('installments').options.length = 0;
            response[0].payer_costs.forEach(payerCost => {
                let opt = document.createElement('option');
                opt.text = payerCost.recommended_message;
                opt.value = payerCost.installments;
                document.getElementById('installments').appendChild(opt);
            });
        } else {
            alert(`installments method info error: ${response}`);
        }
    }

    doSubmit = false;
    document.getElementById('paymentForm').addEventListener('submit', getCardToken);

    function getCardToken(event) {
        event.preventDefault();
        if (!doSubmit) {
            let $form = document.getElementById('paymentForm');
            window.Mercadopago.createToken($form, setCardTokenAndPay);
            return false;
        }
    };

    function setCardTokenAndPay(status, response) {
        if (status == 200 || status == 201) {
            let form = document.getElementById('paymentForm');
            let card = document.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', response.id);
            form.appendChild(card);
            doSubmit = true;
            form.submit();
        } else {
            alert("Verify filled data!\n" + JSON.stringify(response, null, 4));
        }
    };
</script>

</html>