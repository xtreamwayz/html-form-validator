The form validator detects HTML5 form elements and adds default validators depending on the used attributes.

## The input element

### Hidden state (type=hidden)

*Attributes:*
[[value|API Attributes#value]].

```html
<input type="hidden" name="element_name" value="" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#hidden-state-(type=hidden))

### Text state (type=text) and Search state (type=search)

The difference between the Text state and the Search state is primarily stylistic.

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="text" name="name" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#text-(type=text)-state-and-search-state-(type=search))

### Telephone state (type=tel)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="tel" name="tel" data-country="es" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#telephone-state-(type=tel))

### URL state (type=url)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="url" name="url" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#url-state-(type=url))

### E-mail state (type=email)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[multiple|API Attributes#multiple]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="email" name="email" data-validator-use-mx-check="true" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#e-mail-state-(type=email))

### Password state (type=password)

*Attributes:*
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="password" name="password" required />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#password-state-(type=password))

### Date state (type=date)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="date" name="date" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#date-state-(type=date))

### Month state (type=month)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="month" name="month" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#month-state-(type=month))

### Week state (type=week)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="week" name="week" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#week-state-(type=week))

### Time state (type=time)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="time" name="time" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#time-state-(type=time))

### Local Date and Time state (type=datetime-local)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<fieldset>
    <legend>Destination</legend>
    <p>
        <label>Airport:</label>
        <input type="text" name="to" list="airports">
    </p>
    <p>
        <label>Departure time:</label>
        <input type="datetime-local" name="totime" step="3600">
    </p>
</fieldset>
<datalist id="airports">
    <option value="ATL" label="Atlanta">
    <option value="MEM" label="Memphis">
    <option value="LHR" label="London Heathrow">
    <option value="LAX" label="Los Angeles">
    <option value="FRA" label="Frankfurt">
</datalist>
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#local-date-and-time-state-(type=datetime-local))

### Number state (type=number)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<label>How much do you want to charge? $</label>
<input type="number" min="0" step="0.01" name="price">
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#number-state)

### Range state (type=range)

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[multiple|API Attributes#multiple]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<form ...>
    <fieldset>
        <legend>Outbound flight time</legend>
        <select ...>
            <option>Departure
            <option>Arrival
        </select>
        <p>
            <output name=o1>00:00</output> – <output name=o2>24:00</output>
        </p>
        <input type=range multiple min=0 max=24 value=0,24 step=1.0 ...
               oninput="o1.value = valueLow + ':00'; o2.value = valueHigh + ':00'">
    </fieldset>
    ...
</form>
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#range-state-(type=range))

### Colour state (type=color)

*Attributes:*
[[list|API Attributes#list]],
[[disabled|API Attributes#disabled]],
[[value|API Attributes#value]].

```html
<input type="color" name="color" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#color-state-(type=color))

### Checkbox state (type=checkbox)

*Attributes:*
[[checked|API Attributes#checked]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="checkbox" name="checkbox" value="value" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#checkbox-state-(type=checkbox))

### Radio Button state (type=radio)

*Attributes:*
[[checked|API Attributes#checked]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="radio" name="gender" value="male" /> Male<br />
<input type="radio" name="gender" value="female" /> Female<br />
<input type="radio" name="gender" value="other" /> Other
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#radio-button-state-(type=radio))

### File Upload state (type=file)

*Attributes:*
[[accept|API Attributes#accept]],
[[multiple|API Attributes#multiple]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="file" name="file" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#file-upload-state-(type=file))

### Submit Button state (type=submit)

*Attributes:*
[[value|API Attributes#value]].

```html
<input type="submit" name="element_name" value="" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#submit-button-state-(type=submit))

### Image Button state (type=image)

*Attributes:*
[[value|API Attributes#value]].

```html
<input type="submit" name="element_name" value="" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#image-button-state-(type=image))

### Button state (type=button)

*Attributes:*
[[value|API Attributes#value]].

```html
<input type="submit" name="element_name" value="" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#button-state-(type=button))

## The select element

*Attributes:*
[[multiple|API Attributes#multiple]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<select name="car">
    <option value="volvo">Volvo</option>
    <option value="saab">Saab</option>
    <option value="mercedes">Mercedes</option>
    <option value="audi">Audi</option>
</select>
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#the-select-element)

## The textarea element

*Attributes:*
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]].

```html
<textarea name="textarea"></textarea>
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#the-textarea-element)
