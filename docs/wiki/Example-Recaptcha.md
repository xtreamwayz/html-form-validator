Sometimes you need to validate javascript generated from fields. This is easy with the `data-input-name` and
`data-validators` attributes.

```html
<form action="#" method="post">
    <div class="form-group g-recaptcha" aria-required="true"
         data-input-name="g-recaptcha-response"
         data-validators="recaptcha{key:{{ recaptcha_priv_key }}}"
         data-sitekey="{{ recaptcha_pub_key }}" data-theme="light"></div>

    <button type="submit">Submit</button>
</form>
```

The `data-sitekey` and `data-theme` are recaptcha settings. The `data-input-name` attribute enables the input filter
for `g-recatcha-response` and the `data-validators` attribute enables the validation.

If you are wondering, the recaptcha keys will be injected into the form by the template renderer. The pub_key is
needed for recaptcha to function and will be send to the user with the form. The priv_key is for server site
validation and will be passed to the recaptcha validator. Before rendering the form, the secret priv_key will be
removed as the `data-validators` attribute will automatically removed for along with other attributes needed for
configuring the FormFactory only.
