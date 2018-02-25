document.querySelector('input[data-switch="name"]').addEventListener('change', function()
{
    var radios = document.querySelectorAll('div[data-filter-name="name"] input[type=radio]');
    if (this.checked === true) {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = false;
        }
    } else {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = "disabled";
        }
    }
}, false);

document.querySelector('input[data-switch="address"]').addEventListener('change', function()
{
    var radios = document.querySelectorAll('div[data-filter-name="address"] input[type=radio]');
    if (this.checked === true) {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = false;
        }
    } else {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = "disabled";
        }
    }
}, false);

document.querySelector('input[data-switch="stars"]').addEventListener('change', function()
{
    var radios = document.querySelectorAll('div[data-filter-name="stars"] input[type=radio]');
    if (this.checked === true) {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = false;
        }
    } else {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = "disabled";
        }
    }
}, false);

document.querySelector('input[data-switch="contact"]').addEventListener('change', function()
{
    var radios = document.querySelectorAll('div[data-filter-name="contact"] input[type=radio]');
    if (this.checked === true) {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = false;
        }
    } else {
        for ( i = 0, ii = radios.length; i < ii; i++ ) {
            radios[i].disabled = "disabled";
        }
    }
}, false);