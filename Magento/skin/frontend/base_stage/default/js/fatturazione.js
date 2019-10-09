/**
 * Created with JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 30/08/13
 * Time: 17.00
 * To change this template use File | Settings | File Templates.
 */

function toggleIndirizzoAziendale(indirizzoAziendale,prefix){
    if(indirizzoAziendale){
        $(prefix+'partita_iva').addClassName('required-entry');
        $(prefix+'company').addClassName('required-entry');
        $('partita_iva_container').show();
        $('company_container').show();
        toggleCodiceFiscale(true,prefix);


    }else{
        $(prefix+'partita_iva').removeClassName('required-entry');
        $(prefix+'partita_iva').value = '';
        $(prefix +'company').removeClassName('required-entry');
        $(prefix+'company').value = '';
        $('partita_iva_container').hide();
        $('company_container').hide();
        toggleCodiceFiscale(true,prefix);
    }
}

function toggleCodiceFiscale(richiestaFattura,prefix){
    if($(prefix+'indirizzo_aziendale').checked){
        richiestaFattura = true;
    }
    if(richiestaFattura){
        $(prefix+'vat_id').addClassName('required-entry');
        $$('#vat_id_container label em').each(function(element){
            element.show();
        });
        $$('#vat_id_container label').each(function(element){
            element.addClassName('required');
        });


    }else{
        $(prefix+'vat_id').removeClassName('required-entry');
        $$('#vat_id_container label em').each(function(element){
            element.hide();
        });
        $$('#vat_id_container label').each(function(element){
            element.removeClassName('required');
        });
    }
}

function validaCodiceFiscale(cf){

    var validi, i, s, set1, set2, setpari, setdisp;
    if( cf == '' )  return true;
    cf = cf.toUpperCase();
    if( cf.length != 16 )
        return false;
    validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for( i = 0; i < 16; i++ ){
        if( validi.indexOf( cf.charAt(i) ) == -1 )
            return false;
    }
    set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
    s = 0;
    for( i = 1; i <= 13; i += 2 )
        s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    for( i = 0; i <= 14; i += 2 )
        s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
    if( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) )
        return false;
    return true;
}

function validaPartitaIva(pi){
    if( pi == '' )  return true;
    if( pi.length != 11 )
        return false;
    validi = "0123456789";
    for( i = 0; i < 11; i++ ){
        if( validi.indexOf( pi.charAt(i) ) == -1 )
            return false;
    }
    s = 0;
    for( i = 0; i <= 9; i += 2 )
        s += pi.charCodeAt(i) - '0'.charCodeAt(0);
    for( i = 1; i <= 9; i += 2 ){
        c = 2*( pi.charCodeAt(i) - '0'.charCodeAt(0) );
        if( c > 9 )  c = c - 9;
        s += c;
    }
    if( ( 10 - s%10 )%10 != pi.charCodeAt(10) - '0'.charCodeAt(0) )
        return false;
    return true;
}

Validation.addAllThese([['validate-codicefiscale', 'Inserisci un codice fiscale corretto.', function(cf,elm) {
    var val1 = validaCodiceFiscale(cf);
    console.log(val1);
    var val2 = validaPartitaIva(cf);
    console.log(val2);
    var val = val1 || val2;
    return val;

}],
    ['validate-partitaiva', 'Inserisci un numero di partita IVA corretto.', function(pi,elm) {
        return validaPartitaIva(pi);

    }]]);

