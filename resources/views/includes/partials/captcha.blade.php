<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
<div id="_g-recaptcha"></div>
<style>.grecaptcha-badge{{ $hidden }}</style>
<div class="g-recaptcha"
    data-sitekey="{{ $sitekey }}"
    data-size="invisible"
    data-callback="_submitForm"
    data-badge="{{ $location }}">
</div>
<script src="https://www.google.com/recaptcha/api.js{{ $lang }}" async defer></script>
<script>
    var _submitForm, _captchaForm, _captchaSubmit, _execute = true;
    window.addEventListener('load', _loadCaptcha);
    function _loadCaptcha() {
        if ({{ $hidden }}) {
            document.querySelector('.grecaptcha-badge').style = '{{ $hidden }}';
        }
        _captchaForm = document.querySelector("#_g-recaptcha").closest("form");
        _captchaSubmit = _captchaForm.querySelector('[type=submit]');
        _submitForm = function() {
            if(typeof _submitEvent === "function"){
                _submitEvent();
                grecaptcha.reset();
            } else {
                _captchaForm.submit();
            }
        };
        _captchaForm.addEventListener('submit',function(e){
            e.preventDefault();
            if(typeof _beforeSubmit === 'function'){
                _execute = _beforeSubmit(e);
            }
            if(_execute){
                grecaptcha.execute();
            }
        });
    }
</script>
