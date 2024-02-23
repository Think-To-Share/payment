<html>
    <head>
        <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    </head>
    <body>
        
    </body>
    <script>

    const cashfree = Cashfree({
        mode:"{{ config('payment.sandbox',false) ? 'sandbox' : 'production' }}" //sandbox or production
    });

    let checkoutOptions = {
        paymentSessionId: "{{ $session_id }}",
        redirectTarget: "_self" 
    }

    cashfree.checkout(checkoutOptions) 
    </script>
</html>