Sometimes you need to validate javascript generated from fields. This is easy with the ``data-input-name`` and
``data-validators`` attributes.

```html
<form action="#" method="post">
    <div class="form-group g-recaptcha" aria-required="true"
         data-input-name="g-recaptcha-response"
         data-validators="recaptcha{key:{{ recaptcha_priv_key }}}"
         data-sitekey="{{ recaptcha_pub_key }}" data-theme="light"></div>

    <button type="submit">Submit</button>
</form>
```
