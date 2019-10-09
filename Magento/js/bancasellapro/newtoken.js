if (typeof BitbullTokenization == 'undefined') {

    var BitbullTokenization =  {

        init : function (config){

            this.formId = config.formId;
            this.merchantId = config.merchantId;
            this.stringEnctipt = config.stringEnctipt;
            this.waitImage = config.waitImage;
            this.GestPayExternalClass = config.GestPayExternalClass;
            this.successRedirect = config.successRedirect;
            this.enableFormToIframe= config.enableFormToIframe;
            this.code= config.code;
            this.disableProfileRedirect = config.disableProfileRedirect;

            if(!this.enableFormToIframe ){
                this.suspendProfilePage();
            }else {
                this.start();
            }
        },
        start : function(){
            this.showWait();
            this.GestPayExternalClass.CreatePaymentPage( this.merchantId, this.stringEnctipt, this.paymentPageLoad);
        },
        paymentPageLoad : function( Result ){
            BitbullTokenization.hideWait();
            if(Result.ErrorCode != 10){
                BitbullTokenization.suspendProfilePage(Result.ErrorDescription);
            }
        },
        showWait : function(){
            Dialog.info('<img src="'+BitbullTokenization.waitImage+'" class="v-middle" />'+ Translator.translate('Attendi...'),
                { className:'magento',
                    width:150,
                    height:50,
                    zIndex:1000
                }
            );
        },
        getFormContent: function(name){
            return $F(BitbullTokenization.code+name);
        },
        sendPaymentIframe : function (){

            BitbullTokenization.showWait();
            BitbullTokenization.GestPayExternalClass.SendPayment ({
                    CC : BitbullTokenization.getFormContent('_cc_number'),
                    EXPMM : BitbullTokenization.getFormContent('_cc_exp_mm'),
                    EXPYY : BitbullTokenization.getFormContent('_cc_exp_yy'),
                    CVV2 : BitbullTokenization.getFormContent('_cc_cvv'),
                    Name: BitbullTokenization.getFormContent('_cc_name'),
                    Email: BitbullTokenization.getFormContent('_cc_email')
                },
                function ( Result ) {
                    BitbullTokenization.hideWait();
                    BitbullTokenization.analizeResponse.delay(0.8,Result);
                }
            );
            return true;
        },
        hideWait: function(){
            Dialog.closeInfo();
        },

        analizeResponse : function(Result){

            if (Result.ErrorCode != 0){
                if (Result.ErrorCode == 8006){
                    //non gestiamo il 3d secure perché non è possibile effettuare pagamenti ricorrenti
                    BitbullTokenization.suspendProfilePage();
                }else{

                    var idErrorInput = '';
                    if(Result.ErrorCode == 1119 || Result.ErrorCode == 1120){
                        idErrorInput= BitbullTokenization.code+'_cc_number';
                    }else
                    if(Result.ErrorCode == 1124 || Result.ErrorCode == 1126){
                        idErrorInput= BitbullTokenization.code+'_cc_exp_mm'
                    } else
                    if(Result.ErrorCode == 1125){
                        idErrorInput= BitbullTokenization.code+'_cc_exp_yy'
                    }else
                    if(Result.ErrorCode == 1149){
                        idErrorInput= BitbullTokenization.code+'_cc_cvv'
                    }else
                    {
                        BitbullTokenization.suspendProfilePage();
                        return false;
                    }
                    BitbullTokenization.showErrorMessageCC(idErrorInput, Result.ErrorDescription);
                    return false;
                }
            }else{
                //pagamento effettuato con successo oppure l'utente ha annullato il 3dsecure;
                url = BitbullTokenization.successRedirect + '?a='+ BitbullTokenization.merchantId + '&b='+ Result.EncryptedString;
                location.href = url;
                return;
            }
        },

        showErrorMessageCC: function (idError, message){
            BitbullTokenization.hideWait();
            alert(message);
        },

        suspendProfilePage : function(description){
            url = BitbullTokenization.disableProfileRedirect
            location.href = url;
            return;
        }

    }
}