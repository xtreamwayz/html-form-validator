The form validator detects HTML5 form elements and adds default validators depending on the used attributes.

```html
<form action="/" method="post">
    <input type="checkbox" name="checkbox" value="value" />

    <input type="color" name="color" />

    <input type="date" name="date" />

    <input type="datetime-local" name="datetime-local" />

    <input type="email" name="email" data-validator-use-mx-check="true" />

    <input type="file" name="file" />

    <input type="month" name="month" />

    <input type="number" name="number" min="1" max="5" />

    <input type="password" name="password" required />
    <input type="password" name="password-confirm" required data-validators="identical{token:password}" />

    <input type="radio" name="gender" value="male" /> Male<br />
    <input type="radio" name="gender" value="female" /> Female<br />
    <input type="radio" name="gender" value="other" /> Other

    <input type="range" name="range" min="1" max="10" step="2" />

    <input type="tel" name="tel" data-country="es" />

    <input type="text" name="name" />

    <input type="text" name="browser" list="browsers" />
    <datalist id="browsers">
        <option value="Edge" />
        <option value="Firefox" />
        <option value="Chrome" />
        <option value="Opera" />
        <option value="Safari" />
    </datalist>

    <input type="time" name="time" />

    <input type="url" name="url" />

    <input type="week" name="week" />

    <select name="car">
        <option value="volvo">Volvo</option>
        <option value="saab">Saab</option>
        <option value="mercedes">Mercedes</option>
        <option value="audi">Audi</option>
    </select>

    <textarea name="textarea"></textarea>
</form>
```
